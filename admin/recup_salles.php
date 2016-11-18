<?php
// Récupération des salles et des machines dans l'AD, chargement dans la base
include_once('winlog_admin_conf.php');
include_once('db_access.php');


// Connexion à la base winlog
$db = db_connect();
$req_purge_machine = "TRUNCATE machines";
$req_purge_salle = "TRUNCATE salles";
$req_purge_compte = "TRUNCATE comptes";

// connexion LDAP à l'AD
$ldap_con = ldap_connect($ldap_host, $ldap_port);
$ldap_auth = ldap_bind($ldap_con, $ldap_rdn, $ldap_passwd);

// Lecture des salles dans AD : machines pédagogiques
$res_salles = ldap_search($ldap_con, $base_salles, $filtre_salles, $attr_salles);
$entry_salles = ldap_get_entries($ldap_con, $res_salles);

// Lecture des salles dans AD : machines personnels
$res_salles_personnel = ldap_search($ldap_con, $base_salles_personnel, $filtre_salles, $attr_salles);
$entry_salles_personnel = ldap_get_entries($ldap_con, $res_salles_personnel);

// Insertion des machines
db_query($db, $req_purge_machine);

	// salles pédagiques
$salles = array();  // Ce tableau indexé contiendra les salles qui possèdent des machines
for ($i = 0; $i < $entry_salles["count"]; $i++) {
	$dn_tab = ldap_explode_dn($entry_salles[$i]["dn"],1);
	$salle = $dn_tab[1];  // le nom de la salle dans laquelle elle se trouve est le 2° élément du DN d'une machine
	$salles[$i] = $salle; // tableau des salles construit à la volée
	$machine_id = $entry_salles[$i]["cn"][0];
	$os = "";
	$os_sp = "";
	$os_version = "";
	if (array_key_exists("operatingsystem", $entry_salles[$i])) { $os = addslashes($entry_salles[$i]["operatingsystem"][0]); }
	if (array_key_exists("operatingsystemservicepack", $entry_salles[$i])) { $os_sp = addslashes($entry_salles[$i]["operatingsystemservicepack"][0]); }
	if (array_key_exists("operatingsystemversion", $entry_salles[$i])) { $os_version = addslashes($entry_salles[$i]["operatingsystemversion"][0]); }

	$req_machine = "INSERT INTO machines (machine_id, salle, os, os_sp, os_version) VALUES ('{$machine_id}', '{$salle}', '{$os}', '{$os_sp}', '{$os_version}')";
	db_query($db, $req_machine);
}

	// machines personnels
$salles_perso = array();  // Ce tableau indexé contiendra les salles qui possèdent des machines
for ($i = 0; $i < $entry_salles_personnel["count"]; $i++) {
	$dn_tab = ldap_explode_dn($entry_salles_personnel[$i]["dn"],1);
	$salle_perso = $dn_tab[1];  		// le nom de la salle dans laquelle elle se trouve est le 2° élément du DN d'une machine
	$salles_perso[$i] = $salle_perso; 	// tableau des salles construit à la volée
	$machine_id = $entry_salles_personnel[$i]["cn"][0];
	$os = "";
	$os_sp = "";
	$os_version = "";
	if (array_key_exists("operatingsystem", $entry_salles[$i])) { $os = addslashes($entry_salles[$i]["operatingsystem"][0]); }
	if (array_key_exists("operatingsystemservicepack", $entry_salles[$i])) { $os_sp = addslashes($entry_salles[$i]["operatingsystemservicepack"][0]); }
	if (array_key_exists("operatingsystemversion", $entry_salles[$i])) { $os_version = addslashes($entry_salles[$i]["operatingsystemversion"][0]); }
	$req_machine = "INSERT INTO machines (machine_id, salle, os, os_sp, os_version) VALUES ('{$machine_id}', '{$salle_perso}', '{$os}', '{$os_sp}', '{$os_version}')";
	db_query($db, $req_machine);
}

// Insertion des salles pédagogiques
$salles = array_unique($salles); // suppression des doublons dans le tableau des salles
db_query($db, $req_purge_salle);
foreach($salles as $s) {
	$req_salle = "INSERT INTO salles (salle_id) VALUES ('{$s}')";
	db_query($db, $req_salle);
}

// Insertion des salles personnels
$salles_perso = array_unique($salles_perso); // suppression des doublons dans le tableau des salles
foreach($salles_perso as $s) {
	$req_salle = "INSERT INTO salles (salle_id) VALUES ('{$s}')";
	db_query($db, $req_salle);
}





// Fonction d'insertion des personnes dans la base de données à partir des base, filtre et attributs LDAP
// Si le $libelle_type est passé, il remplace la valeur du champ groupe dans la table
function Insere_personnes($ldap_con, $ldap_base, $ldap_filtre, $ldap_attr, $db, $libelle_type) {

	$libelle_type = db_escape_string($db, $libelle_type);
	$res = ldap_search($ldap_con, $ldap_base, $ldap_filtre, $ldap_attr);
	$entry = ldap_get_entries($ldap_con, $res);

	for ($i = 0; $i < $entry["count"]; $i++) {
		$dn_tab = ldap_explode_dn($entry[$i]["dn"], 1);
		$groupe = array_key_exists(1, $dn_tab) ? $dn_tab[1] : "";		// s'il existe, le groupe est le $dn_tab[1]
		$username = array_key_exists("samaccountname", $entry[$i]) ? $entry[$i]["samaccountname"][0] : $entry[$i]["cn"][0];	// le username est le samaccountname s'il existe, le cn sinon
		$libelle_groupe = $libelle_type ? $libelle_type : $groupe;
		$prenom = "";
		$nom = "";
		if (array_key_exists("givenname", $entry[$i])) { 
			$prenom = db_escape_string($db, $entry[$i]["givenname"][0]);
		}
		if (array_key_exists("sn", $entry[$i])) { 
			$nom = db_escape_string($db, $entry[$i]["sn"][0]); 
		}
		$req = "INSERT INTO comptes (username, prenom, nom, groupe) VALUES ('{$username}', '{$prenom}', '{$nom}', '{$libelle_groupe}')";
		db_query($db, $req);		
	}
};


// Insertion des personnes
// =======================

// purge initiale de la table
db_query($db, $req_purge_compte);

// boucle sur chaque branche déclarée dans $ldap_personnes
foreach ($ldap_personnes as $ldap_branche) {
	$ldap_base = $ldap_branche["base"];
	$ldap_filtre = $ldap_branche["filtre"];
	$ldap_attr = $ldap_branche["attr"];
	$libelle_type = $ldap_branche["type"];
	Insere_personnes($ldap_con, $ldap_base, $ldap_filtre, $ldap_attr, $db, $libelle_type);
}

// fermeture ldap et base de données
ldap_close($ldap_con);
db_free($db);

// retour sur la page salles_live.php
header('Location: '.$winlog_url.'/admin/salles_live.php');
?>
