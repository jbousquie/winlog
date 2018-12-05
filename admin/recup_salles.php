<?php
// Récupération des salles et des machines dans l'AD, chargement dans la base
include_once('winlog_admin_conf.php');
include_once('db_access.php');
include_once('custom.php');
include_once('session.php');

$username = Username();
// test profil utilisateur
$profil = Profil($username);
FiltreProfil($profil);
if ($profil < $niveaux[$roles[3]]) {
    header('Location: '.$winlog_url);
}

// paramètre update
$update = false;
if (isset($_GET["p"]) && $_GET["p"] == "u") {
	$update = true;
}

// Connexion à la base winlog
$db = db_connect();
$req_purge_machine = "TRUNCATE machines";
$req_purge_salle = "TRUNCATE salles";

// connexion LDAP à l'AD
$ldap_con = ldap_connect($ldap_host, $ldap_port);
$ldap_auth = ldap_bind($ldap_con, $ldap_rdn, $ldap_passwd);

// Fonction de création du fichier de configuration du démon ping
Function Configuration_ping() {
	global $winlog_ping_conf;
	global $fichier_liste_ip;
	global $fichier_liste_ping;
	global $ping_timeout;
	global $php_path;
	global $winlog_get_ping;
	$fichier = fopen($winlog_ping_conf, "w");
	if ($fichier) {
		$cr = "\n";
		$lignes = [
			"# Fichier de configuration du démon ping de Winlog".$cr,
			"# fichier généré par Winlog, ne pas modifier manuellement".$cr,
			"fichierIN=".$fichier_liste_ip.$cr,
			"fichierOUT=".$fichier_liste_ping.$cr,
			"timeout=".$ping_timeout.$cr,
			"pathPHP=".$php_path.$cr,
			"getPing=".$winlog_get_ping.$cr
		];
		foreach($lignes as $ligne) {
			fwrite($fichier, $ligne);
		}
		fclose($fichier);
	}
};

// Fonction d'écriture des adresses IP des machines dans le fichier liste_ip
Function Liste_ip_fichier(&$db) {
	global $fichier_liste_ip;
	$fichier = fopen($fichier_liste_ip, "w");
	if ($fichier) {
		$req_recup_ip = "SELECT DISTINCT adresse_ip FROM machines WHERE adresse_ip <>''";
		$res = db_query($db, $req_recup_ip);
		while ($ip = db_fetch_row($res)) {
			fwrite($fichier, $ip[0]."\n");
		}
		db_free($res);
		fclose($fichier);
	}
};

// Fonction de lancement du démon de ping
Function Lance_demon_ping() {
	global $winlog_ping_conf;
	global $winlog_start_ping;
	global $winlog_ping_error;
	global $winlog_ping_debug;
	$redirect = ' > /dev/null';
	if ($winlog_ping_debug) {
		$redirect = ' >> '. $winlog_ping_error;
	}

	$command = $winlog_start_ping . ' ' . $winlog_ping_conf . $redirect . ' 2>&1';
	//echo $command;
	exec($command);
};

// Fonction d'insertion des machines dans la base de données à partir des base, filtre et attributs LDAP
// $salles est explicitement passé par référence
// retourne le nombre d'enregistrements ajoutés dans la base
function Insere_machines(&$ldap_con, $ldap_base, $ldap_filtre, &$ldap_attr, &$exclusion, &$db, &$salles, $update) {
	
	$res = ldap_search($ldap_con, $ldap_base, $ldap_filtre, $ldap_attr);
	$entry = ldap_get_entries($ldap_con, $res);
	$count = 0;

	for ($i = 0; $i < $entry["count"]; $i++) {
		$machine_id = $entry[$i]["cn"][0];
		$dn_tab = ldap_explode_dn($entry[$i]["dn"], 1);
		if ( in_array($dn_tab, $exclusion) ) {
			continue;
		}
		$nom_salle = SalleDeMachine($machine_id, $dn_tab[1]);  			// appel custom, le nom de la salle dans laquelle elle se trouve est le 2° élément du DN d'une machine
		array_push($salles, $nom_salle); 	// tableau des salles construit à la volée
		$os = "";
		$os_sp = "";
		$os_version = "";
		if (array_key_exists("operatingsystem", $entry[$i])) { 
			$os = db_escape_string($db, $entry[$i]["operatingsystem"][0]);
		}
		if (array_key_exists("operatingsystemservicepack", $entry[$i])) { 
			$os_sp = db_escape_string($db, $entry[$i]["operatingsystemservicepack"][0]);
		}
		if (array_key_exists("operatingsystemversion", $entry[$i])) { 
			$os_version = db_escape_string($db, $entry[$i]["operatingsystemversion"][0]); 
		}

		// requête permettant de mettre à jour les adresses IP à partir des connexions ouvertes
		$req_adresses = "UPDATE machines INNER JOIN connexions ON  machines.machine_id = connexions.hote SET machines.adresse_ip = connexions.ip WHERE connexions.close=0;";
		
		$req_machine_insert = "INSERT INTO machines (machine_id, salle, os, os_sp, os_version) VALUES ('{$machine_id}', '{$nom_salle}', '{$os}', '{$os_sp}', '{$os_version}')";
		$req_machine_update = "INSERT INTO machines (machine_id, salle, os, os_sp, os_version) VALUES ('{$machine_id}', '{$nom_salle}', '{$os}', '{$os_sp}', '{$os_version}') ON DUPLICATE KEY UPDATE salle = '{$nom_salle}', os = '{$os}', os_sp = '{$os_sp}', os_version = '{$os_version}'";
		$req_machine = ($update) ? $req_machine_update : $req_machine_insert;
		db_query($db, $req_machine);
		db_query($db, $req_adresses);
		$count = $count + 1;
	}
	return $count;
};



// Insertion des machines
// ======================
$salles = array();						// tableau des salles contenant des machines
if (!$update) {
	db_query($db, $req_purge_machine);
}

// boucle sur chaque branche déclarée dans $ldap_machines
$nb_total = 0;
$nb = 0;
foreach ($ldap_machines as $ldap_branche) {
	$ldap_base = $ldap_branche["base"];
	$ldap_filtre = $ldap_branche["filtre"];
	$ldap_attr = $ldap_branche["attr"];
	$nb = Insere_machines($ldap_con, $ldap_base, $ldap_filtre, $ldap_attr, $OU_machines_exclusion, $db, $salles, $update);
	$nb_total = $nb_total + $nb;
}

// Ajout des adresses IP déjà collectées dans le fichier des adresses IP et lancement du démon de ping
// ===================================================================================================
if ($mode_ping) {
	Configuration_ping();
	Liste_ip_fichier($db);
	Lance_demon_ping();
}

// Insertion des salles à partir du tableau $salles remplis par Insere_machines()
// ==============================================================================
$salles_uniques = array_unique($salles); 		// suppression des doublons dans le tableau des salles
db_query($db, $req_purge_salle);
$nb_salles = 0;
foreach($salles_uniques as $s) {
	$req_salle = "INSERT INTO salles (salle_id) VALUES ('{$s}')";
	db_query($db, $req_salle);
	$nb_salles = $nb_salles + 1;
}
// fermeture ldap 
ldap_close($ldap_con);
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
	<h3>Récupération des machines et des salles</h3>
	<p>Ce traitement vient de récupérer les machines et les salles depuis le serveur Active Directory.<br/>
	<?php
	if (!$update) { ?>
		Les informations relatives aux machines et aux salles (noms des salles ou des machines, adresses IP, etc) préalablement dans la base Winlog viennent d'être effacées.<?php } ?></p>
	<p>Nombre de machines chargées dans la base : <?php echo($nb_total); ?><br/>
	   Nombre de salles chargées dans la base : <?php echo($nb_salles); ?><p>
	<p>Le démon de ping des machines vient d'être relancé.</p>
	<p><a href="index.php">Retour menu principal</a></p>
	<p class="footer">version <?php echo($winlog_version); ?></p>
</body>
</html>