<?php
// Menu principal de Winlog
header ('Content-Type: text/html; charset=utf-8');
include_once('libhome.php');
include_once('winlog_admin_conf.php');
include_once('connexions.php');
include_once('session.php');

// récupération du username CAS et stockage en session PHP.
// note : la session PHP est déjà démarrée par la lib phpCAS.
$username = phpCAS::getUser();
$_SESSION['username'] = $username;

$profil = Profil($username);
FiltreProfil($profil);
$role = $roles[$profil];

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
    <h3>Monitoring</h3>
    <div class="menu"><a href="salles_live.php">Connexion Windows en cours dans les salles</a></div>
    <div class="menu"><a href="../wifi/index.php">Connexion Wifi en cours</a></div>
    <br/>
    <h3>Recherche</h3>
    <br/>
    <h3>Statistiques</h3>
    <br/>
<?php
if ($profil == 2) { ?>
    <h3>Gestion</h3>
    <div class="menu"><a href="scripts_clients.php">Génération des fichiers VBS</a> : re-génére les fichiers de scripts clients à déployer dans les GPO de Active Directory</div> 
    <div class="menu"><a href="recup_comptes.php">Rechargement des comptes</a> : recharge tous les comptes utilisateurs depuis Active Directory</div>
    <div class="menu"><a href="recup_salles.php?p=u">Ajout/mise à jour de machines ou de salles</a> : ajoute les nouvelles salles ou machines et met à jour les existantes</div>
    <br/>
    <div class="menu"><a href="recup_salles.php">Rechargement intégral des machines et des salles</a> (attention : ràz de toutes les machines) : à faire quand les machines ou les salles ont changé de nom par exemple</div>
    <p id="msg_archive"><?php echo($msg_archive); ?></p>
<?php
} 
?>
    <p class="footer">version <?php echo($winlog_version); ?></p>
</body>
</html>
