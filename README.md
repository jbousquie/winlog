# WINLOG

Winlog est une architecture simple et un programme léger de surveillance en temps réels des connexions Windows de PC inscrits dans Active Directory.   
Les connexions sont archivées dans une base de données et peuvent faire l'objet ensuite de statistiques, de mesures ou de recherche : qui était connecté quand et sur quelle machine ?  

Voir la [documentation](https://github.com/jbousquie/winlog/wiki)   

version : 1.1.2   
 
*Licence [CeCILL-B](http://www.cecill.info/)*   
auteur : Jérôme Bousquié.  

Dépendances :   
php-ldap  
php-http-request2  pour les fonctionnalités externes (contrôle Web, contrôle PC)
```
pear install http_request2
```

Pour le contrôle Web :  
* Squid + SquidGuard  

Pour le contrôle des PC à distance :  
* Windows Server + Apache/PHP + commande `shutdown`  du Server ToolKit  

![connexions en cours](http://jerome.bousquie.fr/winlog/images/ConnexionsEnCours.png)
  
  
  
© Jérôme Bousquié