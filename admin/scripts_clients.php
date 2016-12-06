<?php
include_once('winlog_admin_conf.php');
include_once('session.php');

$username = Username();

$profil = Profil($username);
FiltreProfil($profil);

if ($profil < $niveaux[$roles[3]]) {
    header('Location: '.$winlog_url);
}
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
    <h2>Génération des scripts clients</h2>
    <div>
        <p>Le fichier <i><b>logon.vbs</b></i> doit être joué par une GPO d'<u>ouverture</u> de session Windows : <a href="genere_vbs.php?f=logon">télécharger logon.vbs</a><br/>
        Par exemple, dans l'outil de Gestion de Stratégie de Groupes, faire <i>"modifier"</i> sur <i>"Domain Users"</i>, puis dans l'éditeur de gestion des stratégies, ouvrir <i>"Configuration utilisateur/Stratégies/Paramètres Windows/Scripts/Ouverture de session"</i> et ajouter logon.vbs à cet endroit.</p>
    </div>
    <div>
        <p>Le fichier <i><b>logout.vbs</b></i> doit être joué par une GPO de <u>fermeture</u> de session Windows : <a href="genere_vbs.php?f=logout">télécharger logout.vbs</a><br/>
        Par exemple, dans l'outil de Gestion de Stratégie de Groupes, faire <i>"modifier"</i> sur <i>"Domain Users"</i>, puis dans l'éditeur de gestion des stratégies, ouvrir <i>"Configuration utilisateur/Stratégies/Paramètres Windows/Scripts/Fermeture de session"</i> et ajouter logout.vbs à cet endroit.</p>   
    </div>
    <div>
        <p>Le fichier <i><b>matos.vbs</b></i> doit être joué par une GPO d'<u>arrêt du système</u> : <a href="genere_vbs.php?f=matos">télécharger matos.vbs</a><br/>
        Par exemple, dans l'outil de Gestion de Stratégie de Groupes, faire "modifier" sur l'OU <i>"Salles / Ordinateurs"</i>, puis dans l'éditeur de gestion des stratégies, ouvrir <i>"Configuration ordinateur/Stratégies/Paramètres Windows/Scripts/Arrêt du système"</i> et ajouter matos.vbs à cet endroit.</p>
    </div>
    <p><a href="index.php">Retour menu principal</a></p>
    <p class="footer">version <?php echo($winlog_version); ?></p>
</body>
</html>