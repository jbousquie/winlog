<?php
// Page des connexions Wifi en cours
// 

include_once('winlog_admin_conf.php');
include_once('connexions.php');
include_once('session.php');
$delayMs = $delay * 1000;
$username = Username();

// test profil utilisateur
$profil = Profil($username);
FiltreProfil($profil);
$admin = ($profil == $niveaux[$roles[3]]);

?>
<!DOCTYPE HTML>
<html lang="fr">
<head>   
    <title>Winlog</title>
    <meta charset="utf-8">
    <link rel="stylesheet" media="screen" type="text/css" title="default" href="default.css">
</head>
<body>
    <h2>Connexions Wifi du jour</a></h2>
    <a href="index.php"><i>Menu principal</i></a> 
    <div id='reload'></div>
    <br/><br/><a href="index.php"><i>Menu principal</i></a> 
    <p class="footer"><?php echo($winlog_version); ?></p>
    <script>

    // fonction d'affichage d'erreur dans la console
    var erreurXHR = function(url, xhr) {
        console.log("erreur chargement" + url + " : " + xhr.statusText);
    };

    // emission requête XHR et récupération du résultat dans div
    var reload = function(url, div) {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", url);
        xhr.onload = function(e) {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    div.innerHTML = xhr.responseText;

                } else {
                    erreurXHR(url, xhr);
                }
            }
        };
        xhr.onerror = function(e) {
            erreurXHR(url, xhr);
        };

    xhr.send(null);  // initie la requête xhr
    };

    // init
    var url = 'reload_wifi.php';
    var init = function() {
        var div = document.getElementById('reload');
        if (div) {
            window.setInterval(function() {
                reload(url, div);
                }, <?php echo($delayMs); ?>);
            reload(url, div);
        }
    };
    window.onload = init;

    </script>
</body>
</hmtl>