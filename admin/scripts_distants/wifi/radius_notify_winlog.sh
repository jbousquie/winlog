#!/bin/bash
#
# Script de log des connexions Eduroam dans Winlog
# Ce script est programmé dans la crontab à intervalle régulier
# Il copie le fichier /var/log/freeradius/radius.log localement
# Il compare cette copie à la version précédente de la copie pour déterminer le nombre de nouvelles inscrites dans le log depuis le dernier traitement
# Il réduit le fichier de travail à ces nouvelles lignes desquelles il extrait les lignes de connexions réussies
# Il émet une requête POST à Winlog avec les données de connexion extraites

ficLog=/var/log/freeradius/radius.log
ficCopie=/root/radius.copie.log
ficDerniereCopie=/root/radius.precedent.log
ficTravail=/root/radius.connexions.log
winlogURL=http://winlog.iut.rdz/wifi/index.php

# copie du fichier de log dans un fichier de travail local
cp $ficLog $ficCopie

# comparaison du delta entre la copie et la précédente
if [ ! -f $ficDerniereCopie ]; then
	touch $ficDerniereCopie
fi
nbLignesCopie=$(wc -l < $ficCopie)
nbLignesPrec=$(wc -l < $ficDerniereCopie)
delta=$(($nbLignesCopie-$nbLignesPrec))

# si le log actuel a plus de lignes que la derniere copie (delta > 0), alors on copie les delta dernières lignes dans le fichier de travail
# sinon, le fichier de log a été réinitialisé (logrotate), le fichier de travail devient directement la copie du fichier du log courant entier
if test "$delta" -gt 0; then
	tail -n $delta $ficCopie > $ficTravail
else
	cp $ficCopie $ficTravail
fi
# dans tous les cas, on copie le fichier de log actuel dans la fichier "derniere copie"
cp $ficCopie $ficDerniereCopie


# Tout le traitement se fait maintenant sur le fichier de travail uniquement
#
# Sélection des lignes contenant "Login OK" uniquement
connexionsOK=$(cat $ficTravail | grep 'Login OK' | cut -d ':'  -f6)
# Remplacement de tous les espaces par * pour itérer ligne par ligne dans la boucle for (et pas par mot)
connexions=${connexionsOK// /*}


for connexion in $connexions; do
	# Récupération du username dans les colonnes de la ligne
	username=$(echo $connexion | cut -d'*' -f2)
	# suppression de tous les caractères à partir du / final
	username=${username/\/*}
	# suppression du [ initial
	username=${username/[}

	# Récupération de la MAC ADDRESS
	mac=$(echo $connexion | cut -d'*' -f12)
	# suppression du ) final
	mac=${mac/)}

	# Envoi de la requête POST à Winlog
	postData=agent=EDUROAM\&ip=$mac\&username=$username
	wget -qO- --post-data=$postData $winlogURL
done
