<?php
// Ce script récupère une action (shutdown ou restart) et un tableau json de machines
// Il émet une requête POST à Ghost:81 pour executer le psshutdown sur le domaine
require_once 'HTTP/Request2.php';


// URL du script stop.php sur Ghost:81
$url = "http://10.5.0.15:81/stop.php";

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
$hosts = $_POST["host"]; // on récupère une chaîne de caractères représentant un tableau json

// on choisit la valeur de l'option à passer à la commande shutdown sur Ghost
if (in_array($action, $action_logout)) { $act = "l"; } 
if (in_array($action, $action_stop)) { $act = "s"; }
if (in_array($action, $action_restart)) { $act = "r"; }
if ($act != "") {
  $http = new HTTP_Request2( $url, HTTP_Request2::METHOD_POST);
  $http->addPostParameter(array('act'=>$act, 'hosts'=>$hosts ));
  try { 
    $http->send(); 
  } 
  catch (HTTP_Request2_Exception $ex) { 
    echo $ex;
 }
}
header('Location: salles_live.php');
?>
