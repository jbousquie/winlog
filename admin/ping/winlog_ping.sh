#!/bin/bash
# Usage : winlog_ping.sh fichier_adresses_ip fichier_adresses_pinguées timeout
# Ce script lit le fichier des adresses IP produit par Winlog, nom du fichier passé en paramètre.
# Pour chaque adresse récupérée, il effectue un fping.
# Si le fping répond, un timestamp est mis à jour sur la ligne de l'adresse IP dans le fichier.
# timeout par défaut = 100ms

# Test arguments
if [[ ! $# -eq 3 ]]; then
    echo 'Erreur de paramètres : les noms des fichiers de la liste des adresses IP et des adresses ayant répondu au ping et la durée du timeout du ping en ms.'
    echo 'Usage : winlog_ping.sh fichierIN_adresses_ip fichierOUT_adresses_pinguées'
    exit 1
fi;
fichierIN=$1
fichierOUT=$2
timeout=$3
if [ ! -e $fichierIN ]; then
    echo 'Erreur : fichier '$1' non trouvé.'
    exit 1
fi;
if [ ! -e $fichierOUT ]; then
    echo 'Erreur : fichier '$2' non trouvé.'
    exit 1
fi;

# Boucle infinie : fping sur chaque adresse du fichier
# La première ligne écrite dans le fichier est le timestamp
# Si réponse ping, alors ajout de l'adresse dans le fichier OUT
# fping -t$timeout -f$fichier | grep alive | cut -d ' ' -f1 : récupère les ip alive depuis fping
while :
do
    actives=`fping -t$timeout -f$fichierIN | grep alive | cut -d ' ' -f1`
    timestamp=$(date +"%Y-%m-%d %H:%M:%S")
    echo $timestamp>$fichierOUT
    i=0
    for ip  in $actives
    do
        echo $ip >> $fichierOUT
        i=$(($i+1))
    done
    echo $timestamp " : " $i "réponses au ping"
done