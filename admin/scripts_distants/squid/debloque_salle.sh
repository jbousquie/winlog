#!/bin/bash
#
# Script de déblocage de salles.
# usage : débloque_salle a200 b300 a201
#
for arg in $*
do
  if [ -f /var/lib/squidguard/salles/$arg ]
  then
    # echo > /var/lib/squidguard/db/salles/$arg 
    rm /var/lib/squidguard/db/salles/$arg
    rm /var/www/salles/rep_salles/$arg
    recharge=1
  fi
done
if [ $recharge ] 
then
  /etc/init.d/squid3 reload
fi
