<?php
// Formulaire d'authentification LDAP
include_once('winlog_admin_conf.php');
// on sort immédiatement si login_ldap.php est appelé hors du auth_mode LDAP
if ($auth_mode !="LDAP") {
    header('Location: interdit.php');
    exit();
}

$username = $_POST['username'];
$password  = $_POST['password'];

include_once('ldap_conf.php');
// Connexion au serveur LDAP
$ldapconn = ldap_connect($auth_ldap_server, $auth_ldap_port) or die("Connexion au serveur LDAP impossible.");
if ($auth_ldap_AD) {
    ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);          // option nécessaire pour une recherche depuis la racine du domaine
    ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);   // option nécessaire pour une recherche sur AD
}
if($ldapconn) {
    // authentification avec le compte $auth_ldapuser
    $ldapbind = ldap_bind($ldapconn, $auth_ldap_user, $auth_ldap_pass) or die ("Erreur LDAP sur bind initial: ".ldap_error($ldapconn));
    if ($ldapbind) {
        $s2 = false;
        $dn = false;
        $srl = false;
        $entry = false;
        // recherche du DN de l'utilisateur $username dans l'annuaire
        $filtre = "($auth_ldap_attribut_identifier=$username)";
        $srl = ldap_search($ldapconn , $auth_ldap_basedn , $filtre, ["dn"]);
        if ($srl) {
            $entry = ldap_first_entry($ldapconn , $srl);
            if ($entry) {
                $dn = ldap_get_dn($ldapconn , $entry);
            }
        }
        // tentative d'authentification de l'utilisateur $username sur l'annuaire
        if ($dn) {
            $s2 = ldap_bind($ldapconn, $dn, $password);
        }
        if (($s2) && ($password != "") && ($dn != ""))
        {
            session_start();
        	$_SESSION['username'] = $username;
            header('Location: '.$winlog_url);
            exit();
        }
    }
}
?>
<!DOCTYPE HTML>
<html lang="fr">
<head>
    <title>Winlog</title>
    <meta charset="utf-8">
    <link rel="stylesheet" media="screen" type="text/css" title="default" href="default.css">
</head>
<body>
    <p class="header">WINLOG</p>
    <p>Compte ou mot de passe inconnu</p>
    <br/><br/><a href="index.php"><i>Menu principal</i></a>
    <p class="footer">version <?php echo($winlog_version); ?></p>
</body>
</html>
