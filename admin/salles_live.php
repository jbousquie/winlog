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
            $liste = $liste."<a href=\"#$sal\" id=\"h-$sal\" class=\"lien_salle\">$sal</a> <span id=\"hj-$sal\" class=\"jours\">&nbsp;&nbsp;&nbsp;&nbsp;</span>\n";
        }
    }
    $liste = $liste;
    return $liste;
}

function InfoWinlog() {
    global $delay;
    global $winlog_version;
    $nb = NbConnexions();
    $debut = date("d/m/Y", strtotime(PremiereConnexion()));
    $info = "nb connexions stockées : ".$nb."\n";
    $info = $info."initiées le : ".$debut."\n\n";
    $info = $info."rafraichissement connexions : ".$delay." s\n";
    $info = $info."winlog version : ".$winlog_version."\n";
    return $info;
}

function InfoCouleurs() {
    global $blacklist_colors;
    global $blacklist_default_color;
    $color_table = "<table>\n";
    foreach ($blacklist_colors as $target => $colors) {
        $color_table = $color_table."<tr><td>$target</td><td style=\"background-color: rgb($colors[0], $colors[1], $colors[2]);\">&nbsp;</td></tr>\n";
    }
    $color_table = $color_table."<tr><td>defaut</td><td style=\"background-color: rgb($blacklist_default_color[0], $blacklist_default_color[1], $blacklist_default_color[2]);\">&nbsp;</td></tr>\n";
    $color_table = $color_table."</table>\n";
    return $color_table;
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
    $admin = false;                     // booleen : utilisateur administrateur ?
    $supervis = false;                  // booleen : utilisateur superviseur ?
    if (in_array($username, $administrateurs)) {
        $admin = true;
    }
    if (in_array($username, $superviseurs)) {
        $supervis = true;
    }
    if ($admin or $supervis) {
        // header
        $liste_salles = ListeSalles();
        $infobulle = InfoWinlog();
        $table_couleurs = InfoCouleurs();
        echo("<div id=\"menu_salles\">\n");
        echo("<span title=\"$infobulle\"><b>Connexions Windows en cours par salle</b></span>");
        echo("<span class=\"right\"><div id=\"g-deroule\" class=\"toggler_general toggler\">[+]</div><div id=\"g-enroule\" class=\"toggler_general toggler\">[-]</div>\n<div id=\"bouton-couleurs\">&nbsp;<div id=\"couleurs\">\n$table_couleurs</div></div>\n</span><br/>\n");
        echo($liste_salles);
        echo("<br/><a href=\"index.php\" class=\"right\"><i>menu</i></a>\n</div>\n");
        echo('</div>'."\n");
        // salles et connexions
        echo('<div id="loaddiv">'."\n");
        include('reload_salles.php');
        echo('</div>');
        // footer
        $texte = '<br/><br/><a href="index.php"><i>Menu principal</i></a>';
    }
    else
    {
    // sinon on affiche un message
        $texte = "Vous n'avez pas l'autorisation d'afficher cette page";
    }
    echo($texte);
?>    
    <script> 

    // fonction de permutation enroulé/déroulé de la liste de connexions d'une salle
    // el : element html toggler
    // enrouleurs : tableau de stockage des états des togglers
    // bascule : true => on demande la permutation de l'état enroulé/déroulé
    // force : null ou boolean. Si boolean, c'est la valeur a affecter de force (true = deroule, false = enroule)
    var toggle = function(el, enrouleurs, bascule, force) {
        var htmlDeroule = "[-] ";
        var htmlEnroule = "[+] ";
        var classDeroule = "deroule";
        var classEnroule = "enroule";
        var listeId = (el.id).replace('b-', 'c-');
        var liste = document.getElementById(listeId);
        // si la valeur forcée "force" n'est pas transmise, on permute ou on garde les valeurs existantes deroule/enroule
        // selon la valeur de bascule :bascule == true => on permute, sinon on rétablit les valeurs stockées
        if (force == null || force == undefined) {
            if (enrouleurs[el.id]) {                    // si initialement déroulé
                if (bascule) {
                    liste.className = classEnroule;
                    el.innerHTML = htmlEnroule; 
                    enrouleurs[el.id] = false               
                } else {
                    liste.className = classDeroule;
                    el.innerHTML = htmlDeroule; 
                    enrouleurs[el.id] = true;                    
                }
            } 
            else                                        // si initialement enroulé
            {
                if (bascule) {
                    liste.className = classDeroule;
                    el.innerHTML = htmlDeroule;
                    enrouleurs[el.id] = true;  
                } else {
                    liste.className = classEnroule;
                    el.innerHTML = htmlEnroule;
                    enrouleurs[el.id] = false;               
                }
            }
        }
        // sinon on affecte la valeur forcée
        else {
            enrouleurs[el.id] = force;
            if (force) {
                liste.className = classDeroule;
                el.innerHTML = htmlDeroule;
            } else {
                liste.className = classEnroule;
                el.innerHTML = htmlEnroule;
            }
        }
    };

    // fonction de mise à jour des bouton enrouleurs/dérouleurs
    var enroule = function(enrouleurs, bascule, force) {
        for (var e in enrouleurs) {
            var el = document.getElementById(e);
            toggle(el, enrouleurs, bascule, force);
        }
    };

    // fonction de bascule vers un état enroulé/déroulé
    var bascule = function(el, enrouleurs) {
        if (!el.classList.contains("toggler")) { return; } // ne traite que les éléments toogler
        if (el.classList.contains("toggler_general")) {    // si toggler général, on force la mise à jour de tous les togglers
            var force = (el.id == 'g-deroule') ? true : false;
            enroule(enrouleurs, false, force);
        } 
        else
        {
            toggle(el, enrouleurs, true);               // si toggler individuel, on bascule sa valeur
        }
    };

    // fonction d'affichage d'erreur dans la console
    var erreurXHR = function(url, xhr) {
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

    // renvoie une chaine rgba avec alpha = "x"
    var rgbaString = function(colorArray) {
        var rgba = "rgba(" + String(colorArray[0]) + ", " + String(colorArray[1]) + ", " + String(colorArray[2]) + ", x)";
        return rgba;
    }

    // flash : clignotement des lignes correspondant au dataset "rejected" du <div> blacklist
     var flash = function() {
        var div_blacklist = document.getElementById("blacklist");           // récupération du div blacklist 
        var ips = JSON.parse(div_blacklist.dataset.rejected);               // récupération du dataset de ce div
        var rgbaDefault = rgbaString(defaultColor);                         
        var alpha = 0;
        var bright = false;
        var finished = false;

        var stylesToFade = [];                                               // tableau des styles des éléments à modifier
        for (var i = 0; i < ips.length; i++) {
            // recuperation des <tr> ip et des elements salles du header
            var ip = ips[i]["ip"].replace(/\./g, '-');                      // remplacement des '.' par des '-'
            var target = ips[i]["target"];
            var rgba;
            if (colors[target]) {
                rgba = rgbaString(colors[target]);
            } else {
                rgba = rgbaDefault;
            }
            var tr_ip = document.getElementById(ip);                        // récupération de la ligne de la connexion par son IP
            stylesToFade.push({style: tr_ip.style, rgba: rgba});
            if (ips[i]["salle"]) {                                          // récupération de la salle si elle est présente
                var salleH = 'h-' + ips[i]["salle"];                        
                var el_salleH = document.getElementById(salleH);            // élément salle dans le menu header
                var salleL = 'l-' + ips[i]["salle"];
                var el_salleL = document.getElementById(salleL);            // élément salle dans la liste
                stylesToFade.push({style: el_salleH.style, rgba: rgba}, {style: el_salleL.style, rgba: rgba});
            }
        }

        var fade = function() {
            for (var s = 0; s < stylesToFade.length; s++) {
                var style = stylesToFade[s].style;
                var rgba = stylesToFade[s].rgba;
                style.backgroundColor = rgba.replace("x", alpha);
            }
            // tant que alpha n'a pas atteint 1, il incrémente
            if (!bright) {
                alpha += 0.05;
                if (alpha > 1) {
                    bright = true;
                } 
            } 
            else 
            // dès que alpha a atteint 1 (bright), il décremente jusqu'à 0
            if (bright) {
                alpha -= 0.02;
                if (alpha < 0) {
                    finished = true;
                }
            }
            // tant que alpha n'a pas fait 0-1-0, on rappelle la fonction toutes les 17 ms (environ 60 fps)
            if (!finished) {
                window.setTimeout(fade, 17);
            }
        }

        fade();
    }

    // emission requête XHR et récupération du résultat dans div
    var reload = function(url, div, enrouleurs) {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", url);
        xhr.onload = function(e) {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    div.innerHTML = xhr.responseText;
                    flash();
                    enroule(enrouleurs);

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
    var url = 'reload_salles.php';
    var colors = <?php echo(json_encode($blacklist_colors)); ?>;
    var defaultColor = <?php echo(json_encode($blacklist_default_color)); ?>;
    var init = function() {
        var div = document.getElementById('loaddiv');
        if (div) {
            jourListeSalle(div);
            var enrouleurs = {};        // tableau associatif des enrouleurs pour garder l'état de chaque liste sur reload
            var elems = div.getElementsByClassName("toggler");
            // initialisation des enrouleurs (true = déroulé, par défaut)
            for (var i = 0; i < elems.length; i++) {
                enrouleurs[elems[i].id] = <?php echo(json_encode($deroule)); ?>;
            }

            document.addEventListener("click", function(e) { bascule(e.target, enrouleurs); }, false);

            window.setInterval(function() {
                reload(url, div, enrouleurs);
                }, <?php echo($delayMs); ?>);
            reload(url, div, enrouleurs);
            enroule(enrouleurs, false);         // remise à jour de l'état de tous les togglers de la page
        }
    };
    window.onload = init;
    </script>
</body>
</html>
