#!/bin/bash
# Usage : winlog_start_ping.sh fichier_ping.conf
# Ce script lance le shell winlog_ping.sh en arrière plan et rend la main aussitôt.
# Il arrête auparavant les éventuels processus de winlog_ping.sh.

# Test arguments
if [[ $# -eq 0 ]]; then
    echo 'Erreur de paramètre : le fichier de configuration du ping doit être passé'
    echo 'Usage : winlog_start_ping.sh fichier_ping.conf'
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


# Arrêt des éventuels processus actifs de winlog_ping.sh
for proc in `pgrep winlog_ping`
do
    kill -9 $proc
done

# Lancement de winlog_ping.sh en arrière plan
/var/www/html/admin/ping/winlog_ping.sh $1 &