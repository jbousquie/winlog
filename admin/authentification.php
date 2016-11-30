<?php
// Formulaire d'authentification simple
include_once('winlog_admin_conf.php');
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
    <form id="login" name="login" action="login.php" method="POST">
        <div class="login"><label for="username">Compte : </label><input type="text" id⁼"username" name="username" /></div>
        <div class = "login"><label for="password">Mot de passe : </label><input type="password" id="password" name="password" /></div>
        <button type="submit" class ="bouton_valide">Valider</button>
    </form>
    <p class="footer">version <?php echo($winlog_version); ?></p>
</body>
</html>