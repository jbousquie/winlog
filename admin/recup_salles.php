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
for ($i=0; $i<$entry_salles["count"];$i++) {
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
for ($i=0; $i<$entry_salles_personnel["count"];$i++) {
	$dn_tab = ldap_explode_dn($entry_salles_personnel[$i]["dn"],1);
	$salle_perso = $dn_tab[1];  // le nom de la salle dans laquelle elle se trouve est le 2° élément du DN d'une machine
	$salles_perso[$i] = $salle_perso; // tableau des salles construit à la volée
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
$salles_perso = array_unique($salles); // suppression des doublons dans le tableau des salles
db_query($db, $req_purge_salle);
foreach($salles_perso as $s) {
	$req_salle = "INSERT INTO salles (salle_id) VALUES ('{$s}')";
	db_query($db, $req_salle);
	}

// Lecture des enseignants dans AD
$res_enseignants = ldap_search($ldap_con, $base_enseignants, $filtre_enseignants, $attr_enseignants);
$entry_enseignants = ldap_get_entries($ldap_con, $res_enseignants);

// Insertions des enseignants
db_query($db, $req_purge_compte);
for ($i=0; $i<$entry_enseignants["count"];$i++) {
	$dn_tab = ldap_explode_dn($entry_salles[$i]["dn"],1);
	$username = $entry_enseignants[$i]["samaccountname"][0];
	$prenom = "";
	$nom = "";
	if (array_key_exists("givenname", $entry_enseignants[$i])) { $prenom = addslashes($entry_enseignants[$i]["givenname"][0]); }
	if (array_key_exists("sn", $entry_enseignants[$i])) { $nom = addslashes($entry_enseignants[$i]["sn"][0]); }
	$req_enseignant = "INSERT INTO comptes (username, prenom, nom, groupe) VALUES ('{$username}', '{$prenom}', '{$nom}', 'Enseignant')";
	db_query($db, $req_enseignant);
	}

// Lecture des étudiants dans AD
$res_etudiants = ldap_search($ldap_con, $base_etudiants, $filtre_etudiants, $attr_etudiants);
$entry_etudiants = ldap_get_entries($ldap_con, $res_etudiants);

// Insertions des étudiants
for ($i=0; $i<$entry_etudiants["count"];$i++) {
	$dn_tab = ldap_explode_dn($entry_etudiants[$i]["dn"],1);
	$groupe = $dn_tab[1];
	$username = $entry_etudiants[$i]["cn"][0];
	$prenom = "";
	$nom = "";
	if (array_key_exists("givenname", $entry_etudiants[$i])) { $prenom = addslashes($entry_etudiants[$i]["givenname"][0]); }
	if (array_key_exists("sn", $entry_etudiants[$i])) { $nom = addslashes($entry_etudiants[$i]["sn"][0]); }
	
	$req_etudiant = "INSERT INTO comptes (username, prenom, nom, groupe) VALUES ('{$username}', '{$prenom}', '{$nom}', '{$groupe}')";
	db_query($db, $req_etudiant);
	}

ldap_close($ldap_con);
header('Location: '.$winlog_url.'/admin/salles_live.php');
?>
