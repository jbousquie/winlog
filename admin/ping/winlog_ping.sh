#!/bin/bash
# Usage : winlog_ping.sh fichier_ping.conf
# Ce script lit le fichierIN des adresses IP produit par Winlog.
# Pour chaque adresse récupérée, il effectue un fping.
# Si le fping répond, une ligne est ajoutée dans le fichierOUT. La première ligne du fichier est le timestamp de fin du ping global.

# Test arguments
if [[ $# -eq 0 ]]; then
    echo 'Erreur de paramètre : le fichier de configuration du ping doit être passé'
    echo 'Usage : winlog_ping.sh fichier_ping.conf'
    exit 1
fi;
# Source de la configuration et tests fichiers
if [ ! -e $1 ]; then
    echo 'Erreur : fichier '$1' non trouvé.'
    exit 1
fi;
source $1;
if [ ! -e $fichierIN ]; then
    echo 'Erreur : fichier '$fichierIN' non trouvé.'
    exit 1
fi;
if [ ! -e $fichierOUT ]; then
    echo 'Erreur : fichier '$fichierOUT' non trouvé.'
    exit 1
fi;

# Boucle infinie : fping sur chaque adresse du fichier
# Si réponse ping, alors ajout de l'adresse dans le fichier OUT
# fping -t$timeout -f$fichier | grep alive | cut -d ' ' -f1 : récupère les ip alive depuis fping
# Une fois le fichier rempli, on exécute admin/get_ping.php qui va récupérer le contenu du fichier dans la base Winlog
while :
do
    actives=`fping -a -t$timeout -f$fichierIN`
    timestamp=$(date +"%Y-%m-%d %H:%M:%S")
    >$fichierOUT
    i=0
    for ip  in $actives
    do
        echo $ip >> $fichierOUT
        i=$(($i+1))
    done
    requetePing=$pathPHP' '$getPing
    eval $requetePing
    echo $timestamp " : " $i "réponses au ping"
done