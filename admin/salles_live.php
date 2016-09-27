<?php
// Cette page présente toutes les connexions en cours de toutes les salles.
// Elle recharge simplement dans un <div> la page reload_salles.php à intervalle donné par la variable $delay du fichier de configuration
//

header ('Content-Type: text/html; charset=utf-8');
include_once('libhome.php');
include_once('winlog_admin_conf.php');
include_once('connexions.php');
$delay = $delay * 1000;
$username = phpCAS::getUser();

function ListeSalles() {
    $salles = Salles();
    global $salles_invisibles;
    $liste = "<div id=\"liste_salles\">\n";
    foreach ($salles as $sal) {
        if (!in_array($sal, $salles_invisibles)) {
            $liste = $liste."<a href=\"#$sal\" class=\"lien_salle\">$sal</a>";
        }
    }
    $liste = $liste."\n</div>\n";
    return $liste;
}


?>
<!DOCTYPE HTML>
<html lang="fr">
<head>   
    <title>Winlog : Connexions en cours dans les salles</title>
    <meta charset="utf-8">
    <link rel="stylesheet" media="screen" type="text/css" title="default" href="default.css">
</head>
<body>

    <?php
        // Si le compte est autorisé à voir les salles, on affiche le div
        if (in_array($username, $autorises)) {
            $liste_salles = ListeSalles();
            echo('<div id="menu_salles"><b>Connexions Windows en cours par salle</b><br/>'.$liste_salles.'</div>'."\n");
            echo('<div id="loaddiv">'."\n");
            include('reload_salles.php');
            echo('</div>');
            $texte = '<br/><br/><a href="recup_salles.php"><i>rechargement des comptes, machines et salles</i></a>';
        }
        else
        {
        // sinon on affiche un message
            $texte = "Vous n'avez pas l'autorisation d'afficher cette page";
        }
        echo($texte);
    ?>    
    <script> 
    // fonction d'affichage d'erreur dans la console
    var erreurXHR = function(url) {
        console.log("erreur chargement" + url + " : " + xhr.statusText);
    };

    function flash() {
        var div_blacklist = document.getElementById("blacklist");           // récupération du div blacklist 
        var ips = JSON.parse(div_blacklist.dataset.rejected);               // récupération du dataset de ce div
        var rgbaString = "rgba(255, 140, 0, x)";
        for (var i = 0; i < ips.length; i++) {
            var ip = ips[i]["ip"].replace(/\./g, '-');                      // remplacement des '.' par des '-'
            var tr_ip = document.getElementById(ip);
            var s = tr_ip.style;
            s.backgroundColor = rgbaString.replace("x", "0");
            var alpha = 0;
            var bright = false;
            var finished = false;
            (function fade() {
                s.backgroundColor = rgbaString.replace("x", String(alpha));
                if (!bright) {
                    alpha += 0.05;
                    if (alpha > 1) {
                        bright = true;
                    } 
                } 
                else 
                if (bright) {
                    alpha -= 0.02;
                    if (alpha < 0) {
                        finished = true;
                    }
                }
                if (!finished) {
                    window.setTimeout(fade, 16);
                }
            })();

        }
    }

    // emission requête XHR et récupération du résultat dans div
    var reload = function(url, div) {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", url);
        xhr.onload = function(e) {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    div.innerHTML = xhr.responseText;
                    flash();

                } else {
                    erreurXHR(url);
                }
            }
        };
        xhr.onerror = function(e) {
            erreurXHR(url);
        };

    xhr.send(null);  // initie la requête xhr
    };

    var url = 'reload_salles.php';
    var div = document.getElementById('loaddiv');
    if (div) {
        window.setInterval(function() {
            reload(url, div);
        }, <?php echo($delay); ?>);
    }

    </script>
</body>
</html>
