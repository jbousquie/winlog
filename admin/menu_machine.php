<?php
// Menu de recherche de connexions dans la base de données
include_once('winlog_admin_conf.php');
include_once('session.php');

$username = Username();
$profil = Profil($username);
FiltreProfil($profil);

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
    <h2 style="text-align: center;">Recherche de machines</h2>
    <div class = "criteres">
    <form id="recherche" action="recherche.php" method="POST">
        <table>
        <input type="hidden" name="objet" value="machines" />
        <tr><td><label for="machine" title="nom de la machine">nom de la machine : </label></td><td><input type="text" name="machine" id="machine" /></td></tr>
        <tr><td><label for="salle" title="nom de la salle de la machine">salle de la machine : </label></td><td><input type="text" name="salle" id="salle" /></td></tr>
        <tr><td><label for="os" title="OS de la machine">système de la machine : </label></td><td><input type="text" name="os" id="os" /></td></tr>
        <tr><td><label for="sp" title="numéro du service pack">service pack : </label></td><td><input type="text" name="sp" id="sp" /></td></tr>
        <tr><td><label for="os_version" title="numéro de version du système">version du système : </label></td><td><input type="text" name="os_version" id="os_version" /></td></tr>
        <tr><td><label for="ip" title="adresse IP de la machine">adresse IP : </label></td><td><input type="text" name="ip" id="ip" /></td></tr>
        <tr><td><label for="marque" title="marque de la machine">marque de la machine : </label></td><td><input type="text" name="marque" id="marque" /></td></tr>
        <tr><td><label for="modele" title="modèle de la machine">modèle de la machine : </label></td><td><input type="text" name="modele" id="modele" /></td></tr>
        <tr><td><label for="arch" title="architecture du système">type du système : </label></td><td><input type="text" name="arch" id="arch" /></td></tr>
        <tr><td><label for="mac" title="adresse MAC de la machine">adresse MAC : </label></td><td><input type="text" name="mac" id="mac" /></td></tr>
        <tr><td><label for="iface" title="description de la carte réseau">descriptif carte réseau : </label></td><td><input type="text" name="iface" id="iface" /></td></tr>
        </table>
        <p style="text-align: center;">Le caractère % permet de remplacer un nombre quelconques de caractères.<br/>Exemple : salle = A% renvoie toutes les salles dont le nom commence par A.</p>
        <button class="bouton_valide">Rechercher</button>
    </form>
    </div>
    <p><a href="index.php">Retour menu principal</a></p>
    <p class="footer">version <?php echo($winlog_version); ?></p>
</body>
</html>