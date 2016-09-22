<?php
include_once('winlogconf.php');
include_once('admin/db_access.php');

// ne traiter que sur des requêtes POST sur le port 443
if ( $_SERVER["REQUEST_METHOD"] == "POST" && $_SERVER["SERVER_PORT"] == "443") {

    $action = $_POST["action"];
	$username = $_POST["username"];
	$computer = $_POST["computer"];
	$code = $_POST["code"];
	$ip = $_SERVER["REMOTE_ADDR"];

	if (strcmp($code, $server_code)!=0) { exit; } // se protéger des POST anonymes par un code partagé entre client et serveur

	$db = db_connect();
	
	// requête de purge d'une éventuelle connexion restée ouverte sur une machine (multi-session non autorisée sur les PC)
	$req_purge_C = 'UPDATE connexions SET close = 1 WHERE close = 0 AND hote = "'.$computer.'"';
	// requête de création de l'enregistrement de connexion
	$req_con_C ='INSERT INTO connexions (username, hote, ip, debut_con, close) VALUES ("'.$username.'", "'.$computer.'", "'.$ip.'", CURRENT_TIMESTAMP(),0)';
	//requête de mise à jour (fermeture) de la connexion
	$req_con_D = 'UPDATE connexions SET close = 1 WHERE close = 0 AND username = "'.$username.'" AND hote = "'.$computer.'"';
	// si action = C alors $req = $req_con_C, sinon $req_con_D
	$req = $action == "C" ? $req_con_C:$req_con_D;
	
	if ($action == "C") { db_query($db, $req_purge_C); } // on commence par purger avant de créer une connexion
	$res = db_query($db, $req);
	}
?>
