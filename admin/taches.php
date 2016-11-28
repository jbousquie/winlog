<?php
// Ce script affiche les processus en cours sur une machine windows
// Pour ceci il interroge (http) un serveur windows du domaine sur le lequel sera exécuté une commande tasklist /s $host
include_once('winlog_admin_conf.php');
include_once('connexions.php');
include_once('client_http.php');
include_once('session.php');
$username = Username();

$profil = Profil($username);
FiltreProfil($profil);

Function Get_tasks($url) {
    $taches = array();
    $res = GetURL($url);
    if ($res != "") {
        $taches = json_decode($res);
    }
    return $taches;
}

// Variables
$host = $_GET['host'];
$machines = Machines();
$machine = $machines[$host];
$os = $machine[1];
$os_version = $machine[3];
$adresse_ip = $machine[4];
$marque = $machine[5];
$modele = $machine[6];
$arch = $machine[7];
$mac_addr = $machine[8];
$mac_descr = $machine[9];

$url = $url_taches . "?host=" . $host;           // Ghost + apache, port 81
$processus_utilisateur = 'IUT';                  // motif identifiant un processus utilisateur dans la task list
$msg = "";


$proc = Get_tasks($url);
if (sizeof($proc) == 0) { 
    $msg = "La machine ".$host." n'a renvoyé aucune réponse.<br/>Causes possibles : machine arrêtée, en veille ou non accesible par le réseau."; 
}
else {
    $msg="<table>\n<th>Processus</th><th>mémoire</th><th>Propriétaire</th>\n";
    $lig_proc = array();
    foreach($proc as $li) {
        $li = str_replace('ÿ','',$li);
        $li = str_replace('"','',$li);
        $lig_prog = explode(',', $li);
        $class_user = "";
        if (substr($lig_prog[5], 0, strlen($processus_utilisateur)) == $processus_utilisateur) { 
            $class_user = " p_user"; 
        }
        $msg = $msg."<tr class='proc".$class_user."'><td><a href=\"https://www.google.fr/search?q=".$lig_prog[0]."\" target=\"_blank\" >".$lig_prog[0]."</a></td><td>".$lig_prog[4]."</td><td>".$lig_prog[5]."</td></tr>\n";
    }
    $msg = $msg."</table>";
}

echo($msg);
?>

