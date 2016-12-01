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
    <h2 style="text-align: center;">Recherche de connexions Windows</h2>
    <div class = "criteres">
    <form id="recherche" action="recherche.php" method="POST">
        <table>
        <input type="hidden" name="objet" value="connexions" />
        <tr><td><label for="machine" title="nom d'une machine">machine : </label></td><td><input type="text" name="machine" id="machine" /></td/></tr>
        <tr><td><label for="compte" title="compte utilisateur">compte : </label></td><td><input type="text" name="compte" id="compte" /></td></tr>
        <tr><td><label for="salle" title="nom d'une salle">salle : </label></td><td><input type="text" name="salle" id="salle" /></td></tr>
        <tr><td><label for="ip" title="adresse IP">adresse IP : </label></td><td><input type="text" name="ip" id="ip" /></td></tr>
        <tr><td><label for="date_debut" title="date ou date début">date ou depuis le : </label></td><td><input type="text" name="date_debut" id="date_debut" /></td></tr>
        <tr><td><label for="date_fin" title="date fin">jusqu'au : </label></td><td><input type="text" name="date_fin" id="date_fin" /></td></tr>
        </table>
        <p style="text-align: center;">Écrire les dates sous la forme <i>JJ/MM/AAAA</i>.<br/>Les dates non valides seront ignorées.</p>
        <button class="bouton_valide">Rechercher</button>
    </form>
    </div>
    <p><a href="index.php">Retour menu principal</a></p>
    <p class="footer">version <?php echo($winlog_version); ?></p>
</body>
</html>