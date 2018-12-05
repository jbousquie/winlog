<?php
// Formulaire d'arrêt du démon ping
include_once('winlog_admin_conf.php');
include_once('session.php');

$username = Username();
// test profil utilisateur
$profil = Profil($username);
FiltreProfil($profil);
if ($profil < $niveaux[$roles[3]]) {
    header('Location: '.$winlog_url);
}

// Fonction d'arrêt du démon de ping
Function Stop_demon_ping() {
	global $winlog_ping_conf;
	global $winlog_stop_ping;
	global $winlog_ping_error;
	global $winlog_ping_debug;
	$redirect = ' > /dev/null';
	if ($winlog_ping_debug) {
		$redirect = ' >> '. $winlog_ping_error;
	}

	$command = $winlog_stop_ping . ' ' . $winlog_ping_conf . $redirect . ' 2>&1';
	//echo $command;
	exec($command);
};

// Arrêt du démon
Stop_demon_ping();

?>
<!DOCTYPE HTML>
<html lang="fr">
<html lang="fr">
<head>   
    <title>Winlog</title>
    <meta charset="utf-8">
    <link rel="stylesheet" media="screen" type="text/css" title="default" href="default.css">
</head>
<body>
	<p class="header">WINLOG-R</p>
	<h3>Arrêt Ping</h3>
	<p>Le démon ping qui tournait éventuellement en arrière-plan vient d'être stoppé.<p/>
    <?php
        if ($mode_ping) { ?>
    <p>Si besoin, le démon ping peut être maintenant <a href="recup_salles.php?p=u">relancé à la demande</a> en récupérant les salles.
    <?php    } 
    ?>
	<p><a href="index.php">Retour menu principal</a></p>
	<p class="footer">version <?php echo($winlog_version); ?></p>
</body>
</html>