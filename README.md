# WINLOG

Winlog est une architecture simple et un programme léger de surveillance en temps réels des connexions Windows de PC inscrits dans Active Directory.   
Les connexions sont archivées dans une base de données et peuvent faire l'objet ensuite de statistiques, de mesures ou de recherche : qui était connecté quand et sur quelle machine ?  

Voir la [documentation](https://github.com/jbousquie/winlog/wiki)   

version : 1.5.0   _([Change Log](https://github.com/jbousquie/winlog/wiki/Change-Log))_  
note : depuis la version 1.4.0 proposant la fonctionnalité de démarrer un script sur une machine distante, Winlog se nomme Winlog-R (remote) pour le différentier des versions précédentes.  

*Licence [CeCILL-B](http://www.cecill.info/)*   
auteur : Jérôme Bousquié.  

Dépendances :   
php-ldap  
php-http-request2  pour les fonctionnalités externes (contrôle Web, contrôle PC)
```
pear install http_request2
```
  
bash et fping pour le démon ping
```
// bash est présent par défaut sur les système Linux
// debian / ubuntu : installation de fping
sudo apt install fping
```
  
Pour le contrôle Web :  
* Squid + SquidGuard  

Pour le contrôle des PC à distance :  
* Windows Server + Apache/PHP + commande `shutdown`  du Server ToolKit  

![connexions en cours](http://jerome.bousquie.fr/winlog/images/ConnexionsEnCours.png)



© Jérôme Bousquié
