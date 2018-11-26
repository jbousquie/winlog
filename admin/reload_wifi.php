<?php
// Cette page affiche le code HTML (sans les en-têtes) de la liste des connexions wifi
// Elle est incluse dans un <div> et rechargée à intervalles réguliers par le script wifi.php.
include_once("winlog_admin_conf.php");
include_once('connexions.php');
include_once('session.php');

$username = Username();
$profil = Profil($username);
FiltreProfil($profil);

$trombino = false;
if ($trombino_url != "") {
    $trombino = true;
}

$connexions_wifi = Connexions_wifi();
$html = "<p><i>Nb connexions déjà établies : ". count($connexions_wifi);
$html = $html . "<table>\n<th>nom</th><th>prénom</th><th>compte</th><th>groupe</th><th>heure connexion</th><th>adresse IP</th><th>browser</th><th>id connexion</th>\n";
$lignes = "";
$pair = false;
$bold = "style=\"font-weight: bold;\"";
foreach ($connexions_wifi as $i => $con_wifi) {
    $nom = $con_wifi["nom"];
    $prenom = $con_wifi["prenom"];
    $username = $con_wifi["username"];
    $groupe = $con_wifi["groupe"];
    $debut = $con_wifi["debut"];
    $ip = $con_wifi["ip"];
    $browser = $con_wifi["browser"];
    $id = $con_wifi["id"];
    $style = ($pair) ? "even" : "odd";
    $div_trombi = "<div>";
    $fin_div = "</div>";
    if ($trombino) {
        $url_photo = $trombino_url."/".$username.$trombino_extension_fichier;
        $div_trombi = "<div class='trombi'><img src='".$url_photo."' onerror=\"this.error=null;this.src='".$trombino_defaut_url."';\">";
    }
    $weight = ($groupe == $lib_personnel) ? $bold : "";
    $lignes = $lignes . "<tr class=\"$style\" $weight><td>$div_trombi$nom$fin_div</td><td>$div_trombi$prenom$fin_div</td><td>$div_trombi$username$fin_div</td><td>$groupe</td><td>$debut</td><td>$ip</td><td>$browser</td><td>$id</td></tr>\n"; 
    $pair = !$pair;
}

$html = $html . $lignes . "</table>\n";
echo($html);
?>
