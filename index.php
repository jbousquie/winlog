<?php
include_once('admin/winlog_admin_conf.php');
include_once('admin/db_access.php');

// ne traiter que sur des requêtes POST sur le port 443
if ( $_SERVER["REQUEST_METHOD"] == "POST" && $_SERVER["SERVER_PORT"] == "443" && strcmp(addslashes($_POST["code"]), addslashes($server_code)) == 0 ) {

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
	// requête de mise à jour de l'adresse IP dans la table machines
	$req_ip_machine = 'UPDATE machines SET adresse_ip = "'. $ip .'" WHERE machine_id = "'. $computer .'"';

	
	if ($action == "C") { 
		db_query($db, $req_purge_C); // on commence par purger avant de créer une connexion
		db_query($db, $req_con_C);
		db_query($db, $req_ip_machine);
	} 
	else {
		// requête déconnexion
		db_query($db, $req_con_D);
		// collecte marque, modèle, architecture
		$marque = db_escape_string($db, $_POST["manufacturer"]);
		$modele = db_escape_string($db, $_POST["model"]);
		$type = db_escape_string($db, $_POST["systemType"]);
		// collecte mac address et description à partir des tableaux JSON des itnerfaces de la machine
		$mac = "";
		$descr = "";
		$mac_array = json_decode(str_replace(",]", "]", $_POST["mac"]));	// nettoyage la virgule de fin dans le tableau json avant decode()
		$ip_array = json_decode(str_replace(",]", "]", $_POST["ip"]));
		$descr_array = json_decode(str_replace(",]", "]", $_POST["descr"]));
		$ip_index = array_search($ip, $ip_array);							// recherche de l'IP de connexion dans la liste des IP json
		if (false !== $ip_index) {											// si l'IP existe dans le tableau, on récupère la mac et la description associées
			$mac = db_escape_string($db, $mac_array[$ip_index]);
			$descr = db_escape_string($db, $descr_array[$ip_index]);
		}
		// requête de mise à jour des marque, modèle, archi et mac address de la machine
		$req_modele_machine = 'UPDATE machines SET marque = "'. $marque .'", modele = "'. $modele .'", type_systeme ="'. $type .'", mac = "'. $mac .'", mac_description = "'. $descr .'"  WHERE machine_id = "'. $computer .'"';
		db_query($db, $req_modele_machine);
	}
}
else {
	// renvoi sur le menu principal
	header('Location: '.$winlog_url.'/admin/index.php');
}
?>
