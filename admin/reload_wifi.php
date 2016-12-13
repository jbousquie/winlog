<?php
// Cette page affiche le code HTML (sans les en-têtes) de la liste des connexions wifi
// Elle est incluse dans un <div> et rechargée à intervalles réguliers par le script wifi.php.
include_once("winlog_admin_conf.php");
include_once('connexions.php');
include_once('session.php');

$username = Username();
$profil = Profil($username);
FiltreProfil($profil);

$connexions_wifi = Connexions_wifi();
$html = "<table>\n<th>nom</th><th>prénom</th><th>compte</th><th>heure connexion</th><th>adresse IP</th><th>browser</th><th>id connexion</th>\n";
$lignes = "";
$pair = false;
foreach ($connexions_wifi as $i => $con_wifi) {
    $nom = $con_wifi["nom"];
    $prenom = $con_wifi["prenom"];
    $username = $con_wifi["username"];
    $debut = $con_wifi["debut"];
    $ip = $con_wifi["ip"];
    $browser = $con_wifi["browser"];
    $id = $con_wifi["id"];
    $style = ($pair) ? "even" : "odd";
    $lignes = $lignes . "<tr class=\"$style\"><td>$nom</td><td>$prenom</td><td>$username</td><td>$debut</td><td>$ip</td><td>$browser</td><td>$id</td></tr>\n"; 
    $pair = !$pair;
}

$html = $html . $lignes . "</table>\n";
echo($html);
?>


