<?php
// Formulaire d'authentification simple
include_once('winlog_admin_conf.php');
include_once('password.php');

// on sort immédiatement si login.php est appelé hors du auth_mode simple
if ($auth_mode !="simple") {
    header('Location: interdit.php');
    exit();
}

$username = $_POST['username'];
$password = $_POST['password'];

if (array_key_exists($username, $passwords) && $password == $passwords[$username]) {
    session_start();
    $_SESSION['username'] = $username;
    header('Location: '.$winlog_url);
    exit();
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