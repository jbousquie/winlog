<?php
    include_once('winlog_admin_conf.php');
    $delayMs = $delay * 1000;
?>
<!DOCTYPE HTML>
<html lang="fr">
<head>  
    <title>Winlog : Connexions en cours dans la salle</title>
    <meta charset="utf-8">
    <link rel="stylesheet" media="screen" type="text/css" title="default" href="default.css">
</head>
<body>
    <form>
    <!-- faire un bouton bloquer/débloquer l'accès Web de cette salle -->
    <!-- faire un bouton (sur un autre formulaire) bloquer/débloquer l'accès Windows de cette salle -->
    </form>
    <div class="salle"></div>
    <div class="connexions">
        <div id="loaddiv">
          <?php
              include('reload_ma_salle.php'); 
          ?>
        </div>
    </div>
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

    // fonction init
    var init = function() {
        var div = document.getElementById('loaddiv');
        if (div) {
            url = 'reload_ma_salle.php';
            window.setInterval(function() {
              reload(url, div);
            }, <?php echo $delayMs ?>);
        }

    };

    window.onload = init;
    </script>
</body>
</html>
