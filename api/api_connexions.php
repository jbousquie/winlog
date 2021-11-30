<?php
// Ce script renvoie un object JSON des connexions en cours
include_once('../admin/connexions.php');

header('Content-Type: application/json; charset=utf-8');

$connexions = Connexion_machine();
$tableau_connexions = [];
foreach ($connexions as $machine => $conn) {
    $conn["machine"] = $machine;         // ajout d'un champ dans le hash $conn
    array_push($tableau_connexions, $conn);
}

$json_connexions = json_encode($tableau_connexions);
echo($json_connexions);

?>