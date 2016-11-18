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

// fonction d'insertion des machines dans la base de données à partir des base, filtre et attributs LDAP
// $salles est explicitement passé par référence
function Insere_machines($ldap_con, $ldap_base, $ldap_filtre, $ldap_attr, $db, &$salles) {
	$res = ldap_search($ldap_con, $ldap_base, $ldap_filtre, $ldap_attr);
	$entry = ldap_get_entries($ldap_con, $res);
	for ($i = 0; $i < $entry["count"]; $i++) {
		$dn_tab = ldap_explode_dn($entry[$i]["dn"],1);
		$nom_salle = $dn_tab[1];  			// le nom de la salle dans laquelle elle se trouve est le 2° élément du DN d'une machine
		array_push($salles, $nom_salle); 	// tableau des salles construit à la volée
		$machine_id = $entry[$i]["cn"][0];
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

		$req_machine = "INSERT INTO machines (machine_id, salle, os, os_sp, os_version) VALUES ('{$machine_id}', '{$nom_salle}', '{$os}', '{$os_sp}', '{$os_version}')";
		db_query($db, $req_machine);
	}
};

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


// Insertion des machines
// ======================
$salles = array();						// tableau des salles contenant des machines
db_query($db, $req_purge_machine);

// boucle sur chaque branche déclarée dans $ldap_machines
foreach ($ldap_machines as $ldap_branche) {
	$ldap_base = $ldap_branche["base"];
	$ldap_filtre = $ldap_branche["filtre"];
	$ldap_attr = $ldap_branche["attr"];
	Insere_machines($ldap_con, $ldap_base, $ldap_filtre, $ldap_attr, $db, $salles);
}


// Insertion des salles à partir du tableau $salles remplis par Insere_machines()
// ==============================================================================
array_unique($salles); 					// suppression des doublons dans le tableau des salles
db_query($db, $req_purge_salle);
foreach($salles as $s) {
	$req_salle = "INSERT INTO salles (salle_id) VALUES ('{$s}')";
	db_query($db, $req_salle);
}


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

// fermeture ldap 
ldap_close($ldap_con);

// retour sur la page salles_live.php
header('Location: '.$winlog_url.'/admin/salles_live.php');
?>
