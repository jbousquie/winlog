<?php
// Ce script renvoie un object JSON des connexions en cours
// on pourra mettre ici l'include d'une lib d'authentification

include_once('../admin/connexions.php');

header('Content-Type: application/json; charset=utf-8');

$connexions = Connexions();

$json_connexions = json_encode($connexions);
echo($json_connexions);
?>