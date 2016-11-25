<?php
// Menu principal de Winlog
header ('Content-Type: text/html; charset=utf-8');
include_once('libhome.php');
include_once('winlog_admin_conf.php');
include_once('connexions.php');

$username = phpCAS::getUser();

// test profil utilisateur
$admin = false;                     // booleen : utilisateur administrateur ?
$supervis = false;                  // booleen : utilisateur superviseur ?
$role = "";
if (in_array($username, $administrateurs)) {
    $admin = true;
    $role = "Administrateur";
}
if (in_array($username, $superviseurs)) {
    $supervis = true;
    $role = "Superviseur";
}
// on quitte immédiatement si non autorisé
if (!$supervis and !$admin) {
    header("Location: interdit.php");
    exit();
}


// archive les connexions de la veille
$nb_archives = ArchiveConnexions();
$msg_archive = "";
if ($nb_archives > 0) {
    $msg_archive = "{$nb_archives} connexions viennent d'être archivées.";
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
    <p>compte : <i><?php echo($username); ?></i><br/>rôle : <i><?php echo($role); ?></i></p>
    <div class="menu"><a href="salles_live.php">Connexion Windows en cours dans les salles</a></div>
    <div class="menu"><a href="">Connexion Wifi en cours</a></div>
    <br/>
    <br/>
    <br/>
    <div class="menu"><a href="recup_comptes.php">Rechargement des comptes</a></div>
    <div class="menu"><a href="recup_salles.php">Rechargement des salles</a></div>
    <p id="msg_archive"><?php echo($msg_archive); ?></p>
    <p class="footer">version <?php echo($winlog_version); ?></p>
</body>
</html>
