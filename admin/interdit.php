<?php
include_once('libhome.php');
$username = phpCAS::getUser();
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
    <p>compte : <i><?php echo($username); ?></i></p>
    <p>Vous n'avez pas les autorisations requises pour utiliser Winlog</p>
</body>
</html>