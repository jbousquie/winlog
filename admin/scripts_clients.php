<?php
include_once('winlog_admin_conf.php');
include_once('session.php');

$username = Username();

$profil = Profil($username);
FiltreProfil($profil);

if ($profil < 2) {
    header('Location: '.$winlog_url);
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
    <div><a href="generate_vbs.php?f=logon">logon.vbs</a></div>
    <div><a href="generate_vbs.php?f=logout">logout.vbs</a></div>
    <div><a href="generate_vbs.php?f=matos">matos.vbs</a></div>

</body>
</html>