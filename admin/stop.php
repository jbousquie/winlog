<?php
// Ce script récupère une action (shutdown ou restart) et un tableau json de machines
// Il émet une requête POST à $url_stop pour executer le shutdown sur le domaine
include_once('winlog_admin_conf.php');
include_once('client_http.php');
include_once('session.php');

$username = Username();

$profil = Profil($username);
FiltreProfil($profil);

$url = $url_stop;

if ($profil == $niveaux[$roles[3]]) {

    $act = "";
    $logout = "fermer la session";
    $logout_salle = "fermer toutes les sessions";
    $eteindre = "éteindre cette machine";
    $eteindre_salle = "éteindre toute la salle";
    $restart = "redémarrer cette machine";
    $restart_salle = "redémarrer toute la salle";
    $start_process = "start processus distant";
    $stop_process = "stop processus distant";
    $start_machine = "allumer cette machine";
    $start_salle = "démarrer toute la salle";

    $action_logout = array($logout, $logout_salle);
    $action_stop = array($eteindre, $eteindre_salle);
    $action_restart = array($restart, $restart_salle);
    $action_start_remote = array($start_process);
    $action_stop_remote = array($stop_process);
    $action_wake = array($start_machine, $start_salle);

    $action = $_POST["stop"];
    $host_json = $_POST["host"];         // on récupère une chaîne de caractères représentant un tableau json
    $mac_json = $_POST["mac"];
    $hosts = json_decode($host_json);
    $macs = json_decode($mac_json);

    // on choisit la valeur de l'option à passer à la commande shutdown sur Ghost
    if (in_array($action, $action_logout)) { 
        $act = "l"; 
    } 
    if (in_array($action, $action_stop)) { 
        $act = "s"; 
    }
    if (in_array($action, $action_restart)) { 
        $act = "r"; 
    }
    if (in_array($action, $action_start_remote)) { 
        $act = "p"; 
        $url = $rpc_url;
    }
    if (in_array($action, $action_stop_remote)) { 
        $act = "k"; 
        $url = $rpc_url;
    }
    if (in_array($action, $action_wake)) { 
        $act = "w"; 
        $url = $wake_url;
    }
    if ($act != "") {
        foreach ($hosts as $key => $host) {
            //echo $host . " " . $mac[$key]. "<br/>";
            PostURL($url, array('act'=>$act, 'host'=>$host, 'mac'=>$macs[$key]));
        }
    }
}
if ($url == $url_stop) {
    header('Location: salles_live.php');
}
else {
    header('Location: '.$_SERVER['HTTP_REFERER']);
}
