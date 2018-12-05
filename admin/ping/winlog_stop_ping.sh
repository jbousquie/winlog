#!/bin/bash
# Usage : winlog_stop_ping.sh fichier_ping.conf
# Ce script stoppe le shell winlog_ping.sh en arrière plan et rend la main aussitôt.

# Test arguments
if [[ $# -eq 0 ]]; then
    echo 'Erreur de paramètre : le fichier de configuration du ping doit être passé'
    echo 'Usage : winlog_stop_ping.sh fichier_ping.conf'
    exit 1
fi;

# Source de la configuration et tests fichiers
if [ ! -e $1 ]; then
    echo 'Erreur : fichier '$1' non trouvé.'
    exit 1
fi;
source $1;

# Arrêt des éventuels processus actifs de winlog_ping.sh
for proc in `pgrep winlog_ping`
do
    kill -9 $proc
done