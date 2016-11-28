<?php
// Récupération des comptes dans l'AD, chargement dans la base
include_once('winlog_admin_conf.php');
include_once('db_access.php');
include_once('session.php');
$username = Username();
// test profil utilisateur
$profil = Profil($username);
FiltreProfil($profil);

// Connexion à la base winlog
$db = db_connect();
$req_purge_compte = "TRUNCATE comptes";

// connexion LDAP à l'AD
$ldap_con = ldap_connect($ldap_host, $ldap_port);
$ldap_auth = ldap_bind($ldap_con, $ldap_rdn, $ldap_passwd);

// Fonction d'insertion des personnes dans la base de données à partir des base, filtre et attributs LDAP
// Si le $libelle_type est passé, il remplace la valeur du champ groupe dans la table
// retourne le nombre d'enregistrements ajoutés
function Insere_personnes(&$ldap_con, $ldap_base, $ldap_filtre, &$ldap_attr, &$exclusion, &$db, $libelle_type) {

    $libelle_type = db_escape_string($db, $libelle_type);
    $res = ldap_search($ldap_con, $ldap_base, $ldap_filtre, $ldap_attr);
    $entry = ldap_get_entries($ldap_con, $res);
    $count = 0;

    for ($i = 0; $i < $entry["count"]; $i++) {
        $dn_tab = ldap_explode_dn($entry[$i]["dn"], 1);
        if ( in_array($dn_tab, $exclusion) ) {
            continue;
        }
        $groupe = array_key_exists(1, $dn_tab) ? $dn_tab[1] : "";       // s'il existe, le groupe est le $dn_tab[1]
        $username = array_key_exists("samaccountname", $entry[$i]) ? $entry[$i]["samaccountname"][0] : $entry[$i]["cn"][0]; // le username est le samaccountname s'il existe, le cn sinon
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
        $count = $count + 1;
    }
    return $count;
};

// Insertion des personnes
// =======================

// purge initiale de la table
db_query($db, $req_purge_compte);

// boucle sur chaque branche déclarée dans $ldap_personnes
$nb_total = 0;
$nb = 0;
foreach ($ldap_personnes as $ldap_branche) {
    $ldap_base = $ldap_branche["base"];
    $ldap_filtre = $ldap_branche["filtre"];
    $ldap_attr = $ldap_branche["attr"];
    $libelle_type = $ldap_branche["type"];
    $nb = Insere_personnes($ldap_con, $ldap_base, $ldap_filtre, $ldap_attr, $OU_personnes_exclusion, $db, $libelle_type);
    $nb_total = $nb_total + $nb;
}

// fermeture ldap 
ldap_close($ldap_con);
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
    <h3>Récupération des comptes Windows</h3>
    <p>Ce traitement vient de récupérer les comptes Windows dans Active Directory.<br/>
        Les informations relatives aux comptes (noms des comptes, groupe d'affectation, etc) préalablement dans la base Winlog viennent d'être effacées.</p>
    <p>Nombre de comptes chargés dans la base : <?php echo($nb_total); ?></p>
    <p><a href="index.php">Retour menu principal</a></p>
    <p class="footer">version <?php echo($winlog_version); ?></p>
</body>
</html>