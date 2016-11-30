#!/bin/bash
#
# Script de blocage de salles.
# usage : bloque_salles a200 b300 a201
#

for arg in $*
do
  if [ -f /var/lib/squidguard/salles/$arg ]
   then
    cp /var/lib/squidguard/salles/$arg /var/lib/squidguard/db/salles/
    chown -R proxy: /var/lib/squidguard/db/salles
    touch /var/www/salles/rep_salles/$arg
    recharge=1
   fi
done
if [ $recharge ]
then
  /etc/init.d/squid3 reload
fi
exit
