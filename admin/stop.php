<?php
// Ce script récupère une action (shutdown ou restart) et un tableau json de machines
// Il émet une requête POST à $url_stop pour executer le shutdown sur le domaine
include_once('winlog_admin_conf.php');
include_once('client_http.php');
include_once('session.php');

$username = Username();

$profil = Profil($username);
FiltreProfil($profil);

if ($profil == $niveaux[$roles[3]]) {

    $act = "";
    $logout = "fermer la session";
    $logout_salle = "fermer toutes les sessions";
    $eteindre = "éteindre cette machine";
    $eteindre_salle = "éteindre toute la salle";
    $restart = "redémarrer cette machine";
    $restart_salle = "redémarrer toute la salle";

    $action_logout = array($logout, $logout_salle);
    $action_stop = array($eteindre, $eteindre_salle);
    $action_restart = array($restart, $restart_salle);

    $action = $_POST["stop"];
    $host_json = $_POST["host"];         // on récupère une chaîne de caractères représentant un tableau json
    $hosts = json_decode($host_json);

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
    if ($act != "") {
        foreach ($hosts as $host) {
            PostURL($url_stop, array('act'=>$act, 'host'=>$host));
        }
    }
    
}
header('Location: salles_live.php');
?>
