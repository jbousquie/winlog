<?php
// Cette page présente toutes les connexions en cours de toutes les salles.
// Elle recharge simplement dans un <div> la page reload_salles.php à intervalle donné par la variable $delay du fichier de configuration
//

header ('Content-Type: text/html; charset=utf-8');
include_once('libhome.php');
include_once('winlog_admin_conf.php');
include_once('connexions.php');
$delayMs = $delay * 1000;
$username = phpCAS::getUser();

function ListeSalles() {
    $salles = Salles();
    global $salles_invisibles;
    $liste = "<div id=\"liste_salles\">\n";
    foreach ($salles as $sal) {
        if (!in_array($sal, $salles_invisibles)) {
            $liste = $liste."<a href=\"#$sal\" id=\"h-$sal\" class=\"lien_salle\">$sal</a> <span id=\"hj-$sal\" class=\"jours\">&nbsp;&nbsp;&nbsp;&nbsp;</span>";
        }
    }
    $liste = $liste;
    return $liste;
}

function InfoWinlog() {
    global $delay;
    $nb = NbConnexions();
    $debut = date("d/m/Y", strtotime(PremiereConnexion()));
    $info = "nb connexions stockées : ".$nb."\n";
    $info = $info."initiées le : ".$debut."\n";
    $info = $info."rafraichissement données : ".$delay." s\n";
    return $info;
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
            // header
            $liste_salles = ListeSalles();
            $infobulle = InfoWinlog();
            echo('<div id="menu_salles">');
            echo("<p title=\"$infobulle\"><b>Connexions Windows en cours par salle</b></p>");
            echo($liste_salles);
            echo("<br/><a href=\"recup_salles.php\" class=\"right\"><i>rechargement</i></a>\n</div>\n");
            echo('</div>'."\n");
            // salles et connexions
            echo('<div id="loaddiv">'."\n");
            include('reload_salles.php');
            echo('</div>');
            // footer
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

    // Met à jour les indicateurs de jours des salles du menu header à partir des valeurs de la liste des salles/connexions
    var jourListeSalle = function(div) {
        var salles = div.getElementsByClassName('jours');
        var liste = {};
        for (var i = 0; i < salles.length; i++) {
            var id = (salles[i].id).replace('j-', 'hj-');
            var classJ = salles[i].className;
            var el = document.getElementById(id);
            el.className = classJ;
        }
    }

    // flash : clignotement des lignes correspondant au dataset "rejected" du <div> blacklist
     var flash = function() {
        var div_blacklist = document.getElementById("blacklist");           // récupération du div blacklist 
        var ips = JSON.parse(div_blacklist.dataset.rejected);               // récupération du dataset de ce div
        var rgbaString = "rgba(255, 140, 0, x)";
        for (var i = 0; i < ips.length; i++) {
            // recuperation des <tr> ip et des elements salles du header
            var ip = ips[i]["ip"].replace(/\./g, '-');                      // remplacement des '.' par des '-'
            var tr_ip = document.getElementById(ip);                        // récupération de la ligne de la connexion par son IP
            var boolSalle = false;                                          // booleen : la connexion vient d'une salle ?
            if (ips[i]["salle"]) {                                          // récupération de la salle si elle est présente
                var salleH = 'h-' + ips[i]["salle"];                        
                var el_salleH = document.getElementById(salleH);            // élément salle dans le menu header
                var salleL = 'l-' + ips[i]["salle"];
                var el_salleL = document.getElementById(salleL);            // élément salle dans la liste
                boolSalle = true;
            }
            // styles
            var s = tr_ip.style;
            s.backgroundColor = rgbaString.replace("x", "0");
            if (boolSalle) {
                var sH = el_salleH.style;
                var sL = el_salleL.style;
                sH.backgroundColor = rgbaString.replace("x", "0");
                sL.backgroundColor = rgbaString.replace("x", "0");
            }
                
            var alpha = 0;
            var bright = false;
            var finished = false;
            (function fade() {
                s.backgroundColor = rgbaString.replace("x", String(alpha));
                if (boolSalle) {
                    sH.backgroundColor = rgbaString.replace("x", String(alpha));
                    sL.backgroundColor = rgbaString.replace("x", String(alpha));
                }
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

    // init
    var url = 'reload_salles.php';
    var init = function() {
        var div = document.getElementById('loaddiv');
        if (div) {
            window.setInterval(function() {
                reload(url, div);
                }, <?php echo($delayMs); ?>);
            reload(url, div);
            jourListeSalle(div);
        }
    };
    window.onload = init;
    </script>
</body>
</html>
