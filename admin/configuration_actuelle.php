<?php
header ('Content-Type: text/html; charset=utf-8');
// Affichage de la configuration en cours dans Winlog
include_once('winlog_admin_conf.php');
include_once('connexions.php');
include_once('session.php');

// connexion LDAP à l'AD
$ldap_con = ldap_connect($ldap_host, $ldap_port);
$ldap_auth = ldap_bind($ldap_con, $ldap_rdn, $ldap_passwd);
$test_ldap = "Échec";
if ($ldap_auth) {
    $test_ldap = "OK";
};

// connexion à la base de donnée
$conn = db_connect();
$test_db = "OK";
if ($conn->connect_errno) {
    $test_db = "Échec de connexion à la base : " . $conn->connect_error;
}

// accès au fichier ping
$fichier = fopen($fichier_ping, "r+");
$test_fichier_ping = "Échec, fichier non accessible";
if ($fichier) {
    $test_fichier_ping = "OK";
}

$username = Username();
$profil = Profil($username);
FiltreProfil($profil);
$role = $roles[$profil];

?>
<!DOCTYPE HTML>
<html lang="fr">
<head>
    <title>Winlog</title>
    <meta charset="utf-8">
    <link rel="stylesheet" media="screen" type="text/css" title="default" href="default.css">
</head>
<body>
    <p class="header">WINLOG-R</p>
    <p>compte : <i><?php echo($username); ?></i><br/>rôle : <i><?php echo($role); ?></i></p>
    <br/>
<?php
if ($profil == $niveaux[$roles[3]]) { ?>
    <h3>Serveur Winlog</h3>
    <p>Mode d'authentification activé : <b><?php echo($auth_mode) ?></b></p>
    <p>URL d'administration de Winlog : <b><?php echo($winlog_url ) ?></b></p>
    <p>URL de collecte des requêtes des PC du parc : <b><?php echo($server_url) ?></b></p>
    <p>Code partagé dans la requête : <b><?php echo($server_code) ?></b></p>
    <br/>
    <h3>Serveur Active Directory</h3>
    <p>Nom ou adresse du serveur AD : <b><?php echo($ldap_host) ?></b></p>
    <p>Port du serveur AD : <b><?php echo($ldap_port) ?></b></p>
    <p>DN d'authentification LDAP sur le serveur AD : <b><?php echo($ldap_rdn) ?></b></p>
    <!-- <p>Mot de passe associé : <b><?php //echo($ldap_passwd) ?></b></p> -->
    <p>Test connexion LDAP : <b><?php echo($test_ldap); ?></b></p>
    <p>Base de recherche AD des salles informatiques : <b><?php echo($base_salles) ?></b></p>
    <p>Base de recherche AD des PC des personnels : <b><?php echo($base_salles_personnel) ?></b></p>
    <p>Base de recherche AD des personnels/enseignants : <b><?php echo($base_enseignants) ?></b></p>
    <p>Base de recherche AD des étudiants : <b><?php echo($base_etudiants) ?></b></p>
    <p>Les OU à exclure de la recherche des comptes : <b><?php print_r($OU_personnes_exclusion) ?></b></p>
    <p>Les OU à exclure de la recherche des machines : <b><?php print_r($OU_machines_exclusion) ?></b></p>
    <br/>
    <h3>MySQL</h3>
    <p>Test de connexion à la base : <b><?php echo($test_db); ?></b></p>
    <i>à compléter ...</i>
    <br/>
    <h3>Ping</h3>
    <p>Fichier liste des adresses IP : <b><?php echo($fichier_ping); ?></b></p>
    <p>Test accès fichier : <b><?php echo($test_fichier_ping); ?></b></p>
<?php 
}
?>
<p><a href="index.php">Retour menu principal</a></p>
<p class="footer">version <?php echo($winlog_version); ?></p>
</body>
</html>