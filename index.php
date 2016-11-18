<?php
include_once('winlogconf.php');
include_once('admin/db_access.php');

// ne traiter que sur des requêtes POST sur le port 443
if ( $_SERVER["REQUEST_METHOD"] == "POST" && $_SERVER["SERVER_PORT"] == "443" && strcmp(addslashes($_POST["code"]), addslashes($server_code)) ) {

	$db = db_connect();

    $action = db_escape_string($db, $_POST["action"]);
	$username = db_escape_string($db, $_POST["username"]);
	$computer = db_escape_string($db, $_POST["computer"]);
	$ip = $_SERVER["REMOTE_ADDR"];

	// requête de purge d'une éventuelle connexion restée ouverte sur une machine (multi-session non autorisée sur les PC)
	$req_purge_C = 'UPDATE connexions SET close = 1 WHERE close = 0 AND hote = "'.$computer.'"';
	// requête de création de l'enregistrement de connexion
	$req_con_C ='INSERT INTO connexions (username, hote, ip, debut_con, close) VALUES ("'.$username.'", "'.$computer.'", "'.$ip.'", CURRENT_TIMESTAMP(),0)';
	//requête de mise à jour (fermeture) de la connexion
	$req_con_D = 'UPDATE connexions SET close = 1 WHERE close = 0 AND username = "'.$username.'" AND hote = "'.$computer.'"';
	
	if ($action == "C") { 
		db_query($db, $req_purge_C); // on commence par purger avant de créer une connexion
		db_query($db, $req_con_C);
	} 
	else {
		db_query($db, $req_con_D);
	}
}
?>
