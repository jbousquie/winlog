<?php
include_once('session.php');
$username = Username();
$profil = Profil($username);
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
    <p class="header">WINLOG</p>
    <p>compte : <i><?php echo($username); ?></i><br/>rôle : <i><?php echo($role); ?></i></p>
    <p>Vous n'avez pas les autorisations requises pour utiliser la console d'administration de Winlog</p>
    <?php 
        if ($profil >= $niveaux[$roles[1]]) {
    ?>
    <p>Peut-être souhaitez-vous simplement voir les <a href="ma_salle_live.php">connexions en cours de votre salle de cours</a> ?</p>
    <?php
        }
    ?>
</body>
</html>