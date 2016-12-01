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
    <h2 style="text-align: center;">Recherche d'utilisateurs</h2>
    <div class = "criteres">
    <form id="recherche" action="recherche.php" method="POST">
        <table>
        <input type="hidden" name="objet" value="utilisateurs" />
        <tr><td><label for="compte" title="compte utilisateur">compte utilisateur : </label></td><td><input type="text" name="compte" id="compte" /></td></tr>
        <tr><td><label for="nom" title="nom de l'utilisateur">nom utilisateur : </label></td><td><input type="text" name="nom" id="nom" /></td></tr>
        <tr><td><label for="prenom" title="prénom de l'utilisateur">prénom de l'utilisateur : </label></td><td><input type="text" name="prenom" id="prenom" /></td></tr>
        <tr><td><label for="groupe" title="groupe de l'utilisateur">groupe de l'utilisateur : </label></td><td><input type="text" name="groupe" id="groupe" /></td></tr>
        </table>
        <p style="text-align: center;">Le caractère % permet de remplacer un nombre quelconques de caractères.<br/>Exemple : nom = BOU% renvoie tous les utilisateurs dont le nom commence par BOU.</p>
        <button class="bouton_valide">Rechercher</button>
    </form>
    </div>
    <p><a href="index.php">Retour menu principal</a></p>
    <p class="footer">version <?php echo($winlog_version); ?></p>
</body>
</html>