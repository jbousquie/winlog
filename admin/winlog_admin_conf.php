<?php
// Version
$winlog_version = "1.0";
// URL administration de Winlog
$winlog_url = "https://winlog.iut-rodez.fr/admin/";
// URL serveur Winlog : URL sur laquelle les requêtes des PC clients arrivent
$server_url = "https://winlog.iut.local/";
// Code partagé entre client et serveur
$server_code = "HK9!-uu";           // ne pas mettre les caractères "&" ou "="


// Paramètres LDAP
$ldap_host = "10.5.0.4";
$ldap_port = 389;
$ldap_rdn = "CN=ldapview,CN=Users,DC=iut,DC=local";
$ldap_passwd ="ldapzdr12";
$base_salles = "OU=Salles,DC=iut,DC=local";
$base_salles_personnel = "OU=Salles Admin,DC=iut,DC=local";
$filtre_salles = "ObjectClass=computer";
$attr_salles = array("cn", "operatingSystem", "operatingSystemServicePack", "operatingSystemVersion");

    // ces variables ne servent qu'à rendre plus lisible le tableau $ldap_personnes requis
$base_enseignants = "OU=Enseignants,DC=iut,DC=local";
$base_etudiants = "OU=Etudiants,DC=iut,DC=local";
$filtre_enseignants = "ObjectClass=user";
$attr_enseignants = array("sAMAccountname", "givenName", "sn");
$filtre_etudiants = "ObjectClass=user";
$attr_etudiants = array("cn", "givenName", "sn");

    // tableaux de configuration requis : $ldap_personnes et $ldap_machines, utilisés par recup_salles.php
$ldap_personnes = array(
    array("base" => $base_enseignants, "filtre" => $filtre_enseignants, "attr" => $attr_enseignants, "type" => "Enseignant"),
    array("base" => $base_etudiants,   "filtre" => $filtre_etudiants,   "attr" => $attr_etudiants,   "type" => NULL)
);

$ldap_machines = array(
    array("base" => $base_salles, "filtre" => $filtre_salles, "attr" => $attr_salles),
    array("base" => $base_salles_personnel, "filtre" => $filtre_salles, "attr" => $attr_salles)
);

// Liste des OU (dn) à ne pas récupérer dans les tableaux précédents
$OU_machines_exclusion = array( "TEST" );
$OU_personnes_exclusion = array( "TEST" );

// Paramètres MySQL
$db_server = "p:localhost";         // "p:host" pour des connexions persistantes
$db_dbname ="winlog";
$db_user = "root";
$db_passwd = "zedor12";

// Délai de rafraichissement en secondes
$delay = 15;

// Seuils de couleur, durée en jours sans connexion
$j10 = 1;      // jaune :  au moins 10 jours sans connexion
$j20 = 2;      // orange : au moins 20 jours sans connexion
$j30 = 30;      // rouge : plus de 30 jours sans connexion

// Salle à ne pas afficher
$salles_invisibles = array("STOCK","TEST", "SOUK"); // liste des salles à ne pas afficher

// Utilisateurs autorisés : les superviseurs peuvent tout voir, les administrateurs peuvent modifier
$administrateurs = array("jerome.bousquie", "rosalie.viala", "caroline.pons");
$superviseurs = array("dominique.seryies", "nicolas.gaven", "systeme.ut1", "thierry.deltort");

// Noms des rôles. Seuls les libellés sont modifiables ici, pas les index du tableau.
$lib_personnel = "Enseignant";          // libellé par défaut
$roles = array();
$roles[0] = "Utilisateur";              // libellé utilisateur sans droit
$roles[1] = $lib_personnel;             // libellé utilisateur avec droit minimal
$roles[2] = "Superviseur";              // libellé Superviseur : accès global en lecture
$roles[3] = "Administrateur";           // libellé Administrateur : accès global

// Niveau des rôles
$niveaux = array();
$niveaux[$roles[3]] = 3;                // niveau Administrateur
$niveaux[$roles[2]] = 2;                // niveau Superviseur
$niveaux[$roles[1]] = 1;                // niveau utilisateur autorisé
$niveaux[$roles[0]] = 0;                // niveau utilisateur sans droit

// Comportement par défaut des listes de connexions : enroulé/déroulé 
// défaut : $deroule = true;
$deroule = true;

// Messages
// ma_salle_live.php : non connecté depuis une salle
$msg_salle_live_non_autorise = "Vous n'&ecirc;tes actuellement pas authentifi&eacute; comme enseignant dans une salle informatique de l'IUT.<br/>";

// Codes couleurs blacklist
// key : dest (ou target) de squidguard
// value : [r,g,b] color (https://web.njit.edu/~kevin/rgb.txt.html)
$blacklist_colors = array(
    "adult" => [255, 192, 203],         // Pink
    "haine" => [165, 42, 42],           // Brown
    "telechargement" => [255, 165, 0],  // Orange
    "jeu" => [112, 219, 147],           // Aquamarine
    "tricheur" => [255, 215, 0],        // Gold
    "proxies" => [30, 144, 255],        // DodgerBlue
    "warez" => [143, 188, 143]          // DarkSeaGreen
    );

$blacklist_default_color = [255, 140, 0];  // DarkOrange

// Blocage Web des salles :
$url_bloque = "http://cache.iut-rodez.fr/salles/bloque_salle.php";
$url_debloque = "http://cache.iut-rodez.fr/salles/debloque_salle.php";
$url_salles_bloquees = "http://cache.iut-rodez.fr/salles/salles_bloquees.php";

// Serveur Windows pour liste des tâches
$url_taches = "http://10.5.0.15:81/task.php";
$url_stop = "http://10.5.0.15:81/stop.php";

// Répertoire des plans de salles
$repertoire_salles = "./salles/";

// Répertoire des templates de scripts VBS
$repertoire_scripts = "./vbs/";
?>
