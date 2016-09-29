<?php
// URL hôte
$winlog_url = "http://winlog.iut.rdz";

// Paramètres LDAP
$ldap_host = "10.5.0.4";
$ldap_port = 389;
$ldap_rdn = "CN=ldapview,CN=Users,DC=iut,DC=local";
$ldap_passwd ="ldapzdr12";
$base_salles = "OU=Salles,DC=iut,DC=local";
$base_salles_personnel = "OU=Salles Admin,DC=iut,DC=local";
$base_enseignants = "OU=Enseignants,DC=iut,DC=local";
$base_etudiants = "OU=Etudiants,DC=iut,DC=local";
$filtre_salles = "ObjectClass=computer";
$attr_salles = array("cn", "operatingSystem", "operatingSystemServicePack", "operatingSystemVersion");
$filtre_enseignants = "ObjectClass=user";
$attr_enseignants = array("sAMAccountname", "givenName", "sn");
$filtre_etudiants = "ObjectClass=user";
$attr_etudiants = array("cn", "givenName", "sn");

// Paramètres MySQL
$db_server = "p:localhost";         // "p:host" pour des connexions persistantes
$db_dbname ="winlog";
$db_user = "root";
$db_passwd = "zedor12";

// Délai de rafraichissement en secondes
$delay = 6;

// Seuils de couleur, durée en jours sans connexion
$j10 = 1;      // jaune :  au moins 10 jours sans connexion
$j20 = 2;      // orange : au moins 20 jours sans connexion
$j30 = 30;      // rouge : plus de 30 jours sans connexion

// Salle à ne pas afficher
$salles_invisibles = array("STOCK","TEST", "SOUK"); // liste des salles à ne pas afficher

// Utilisateurs autorisés
$autorises = array("jerome.bousquie", "rosalie.viala", "monique.malric", "dominique.seryies", "nicolas.gaven", "systeme.ut1", "thierry.deltort");

// Comportement par défaut des listes de connexions : enroulé/déroulé 
// défaut : $deroule = true;
$deroule = false;

// Messages
// ma_salle_live.php : non connecté depuis une salle
$msg_salle_live_non_autorise = "Vous n'&ecirc;tes actuellement pas authentifi&eacute; comme enseignant dans une salle informatique de l'IUT.<br/>";

// Version
$version = "0.9";
?>
