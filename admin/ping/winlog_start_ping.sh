#!/bin/bash
# Usage : winlog_ping.sh fichier_adresses_ip fichier_adresses_pinguées timeout
# Ce script lance le shell winlog_ping.sh en arrière plan et rend la main aussitôt.
# Il arrête auparavant les éventuels processus de winlog_ping.sh.

# Test arguments
if [[ ! $# -eq 3 ]]; then
    echo 'Erreur de paramètres : les noms des fichiers de la liste des adresses IP et des adresses ayant répondu au ping et la durée du timeout du ping en ms.'
    echo 'Usage : winlog_start_ping.sh fichierIN_adresses_ip fichierOUT_adresses_pinguées'
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

# Arrêt des éventuels processus actifs de winlog_ping
for proc in `ps -ej | grep winlog_ping | grep -v grep | cut -d ' ' -f1`
do
    kill -9 $proc
done

# Lancement de winlog_ping.sh en arrière plan
/var/www/html/admin/ping/winlog_ping.sh $fichierIN $fichierOUT $timeout &