# WINLOG

Winlog est une architecture simple et un programme léger de surveillance en temps réels des connexions Windows de PC inscrits dans Active Directory.   
Les connexions sont archivées dans une base de données et peuvent faire l'objet ensuite de statistiques, de mesures ou de recherche : qui était connecté quand et sur quelle machine ?  

NOTE IMPORTANTE : pour l'instant le code de Winlog est lisible/forkable selon les termes de service de Github, mais le copyright demeure applicable (auteur Jérôme Bousquié) et Winlog n'est pas un logiciel gratuit.  

Dépendances :   
php-ldap  
php-http-request2  
```
pear install http_request2
```