<?php
// Cette page affiche le code HTML (sans les en-têtes) de la liste des connexions par salle.
// Elle est incluse dans un <div> et rechargée à intervalles réguliers par le script salles_live.php.
include_once("winlog_admin_conf.php");
include_once('connexions.php');
include_once('client_http.php');
include_once('session.php');
$trombino = false;
if ($trombino_url != "") {
    $trombino = true;
}


$username = Username();
$profil = Profil($username);
FiltreProfil($profil);
$now = time();

$machines = Machines();                             // récupération de toutes les machines connues
$machines_de_salle = Machines_de_salle($machines);  // range les machines dans le tableau $machines_de_salle
$connexion_machine = Connexion_machine();           // récupère toutes les connexions en cours

$supervis = ($profil >= $niveaux[$roles[2]]);          // booleen : utilisateur superviseur ?

// Fonction de récupération de la liste des salles bloquées sur SquidGuard
Function Get_salles_bloquees($url) {
    $salles_bloquees = array();
    $res = GetURL($url);
    if ($res != "") {
        $salles_bloquees = json_decode($res);
    }
    return $salles_bloquees;
};

// Timestamps de ping
if ($mode_ping) {
    $ping_timestamps = PingTimestamps();
}

// connexions dans les salles
$salles_bloquees = Get_salles_bloquees($url_salles_bloquees);

while ($mdc = current($machines_de_salle)) {
    $salle = key($machines_de_salle);
    
    if (!in_array($salle, $salles_invisibles)) {
        
        // si utilisateur administrateur alors lien bloque/débloque activé
        $bloque = '<i>autorisé</i>';
        $debloque = '<i>bloqué</i>';
        if ($supervis) {
            $bloque = '<i><a href="bloque_salle.php?a=b&s='.strtolower($salle).'">bloque</a></i>';
            $debloque = '<i><a href="bloque_salle.php?a=d&s='.strtolower($salle).'">debloque</a></i>';
        }
        $lien = $bloque;

        // calcul dernière plus ancienne connexion
        $jours_last_con = Connexion_doyenne_salle($machines_de_salle[$salle]);
        $class_jour ='jours j-10';
        if ($jours_last_con >= $j10) { $class_jour = 'jours j10'; }
        if ($jours_last_con >= $j20) { $class_jour = 'jours j20'; }
        if ($jours_last_con >= $j30) { $class_jour = 'jours j30'; }

        // lien bloque/debloque
        if (in_array(strtolower($salle), $salles_bloquees)) { 
            $lien = $debloque; 
        }

        // calcul nombre de machines connectées / nombre machines de la salle
        $nb_machines_salle = count($mdc);
        $i = 0;
        if (!empty($connexion_machine)) {
            foreach($mdc as $mac) {   
                if ( array_key_exists($mac, $connexion_machine)) { // on ne compte que les machines connectées 
                    $i++;
                }
            }
        }

        // affichage ligne de salle
        echo "<a class=\"anchor\" id=\"$salle\"></a>\n";
        $fichier_salle = $repertoire_salles.$salle.".php";
        $lien_salle = "<span id=\"l-$salle\">$salle</span>";
        if ( file_exists($fichier_salle) ) {
            $lien_salle = "<a href=\"salles/?salle=$salle\" id=\"l-$salle\">$salle</a>";
        }
        echo "<div class=\"salle\"><span id=\"b-$salle\" class=\"toggler_style toggler\"></span>$lien_salle ($i connexions sur $nb_machines_salle machines = ". number_format($i / $nb_machines_salle * 100, 1) ." %) <span id='j-".$salle."' class='".$class_jour."' title='".$class_jour."'>&nbsp;&nbsp;&nbsp;&nbsp;</span> ($lien)</div>\n";
        echo "<div class=\"connexions\" id=\"c-$salle\"><table>\n";

        if (!empty($connexion_machine)) {
        // parcours des machines connectées de la salle courante
            foreach($mdc as $mac) { 
                if (array_key_exists($mac, $connexion_machine)) {   // on n'affiche que les machines connectées
                    $username = $connexion_machine[$mac]["username"];
                    $cpt = Compte($username);                       // récupère les informations sur l'utilisateur courant
                    $style = "";
                    $fin_style = "";
                    $div_trombi = "<div>";
                    $fin_div = "</div>";
                    if ($trombino) {
                        $url_photo = $trombino_url."/".$username.$trombino_extension_fichier;
                        $div_trombi = "<div class='trombi'><img src='".$url_photo."' onerror=\"this.error=null;this.src='".$trombino_defaut_url."';\">";
                    }
                    if ($cpt[2] == $lib_personnel) { 
                        $style = "<b>"; 
                        $fin_style="</b>"; 
                    }
                    
                    $ping_info = "";
                    $class_noping = " noping";
                    if ($mode_ping) {
                        $ping_delta = "indisponible";
                        if (array_key_exists($mac, $ping_timestamps)) {
                            $ping_ts = strtotime($ping_timestamps[$mac]);
                            $ping_delta = "il y a ".FormateDelta(DateDiff($ping_ts, $now));
                            if ($now - $ping_ts <= $seuil_couleur_ping) {
                                $class_noping = "";
                            }
                        }
                        $ping_info = "ping : ".$ping_delta;
                    }
                    
                    echo "<tr class=\"connexion\" id=\"".str_replace('.','-',$connexion_machine[$mac]["ip"])."\">";
                    echo "<td><a href=\"machine.php?id=".$mac."\" target=\"blank\" class=\"$class_noping\" title=\"$ping_info\">".$style.$mac.$fin_style."</a></td>";
                    echo "<td>".$style.date("H:i:s",$connexion_machine[$mac]["stamp"]).$fin_style."</td>";
                    echo "<td>".$style.$connexion_machine[$mac]["ip"].$fin_style."</td>";
                    echo "<td>".$div_trombi.$style.$username.$fin_style.$fin_div."</td>"; 
                    echo "<td>".$div_trombi.$style.$cpt[1]." ".$cpt[0].$fin_style.$fin_div."</td>";
                    echo "<td>".$style.$cpt[2].$fin_style."</td>";
                    echo "</tr>\n";
                }
            }
        }
        echo "</table></div>\n";
    } // fin du test salle invisible
    next($machines_de_salle);
}

// Récupération des connexions sur la blacklist Squid
// Stockage du résultat dans un dataset d'une <div> dédiée
$connexions_blacklist_live = Connexions_blacklist_live($delay, $machines);
$div_blacklist = '<div id="blacklist" data-rejected=\''.json_encode($connexions_blacklist_live).'\'></div>';
echo($div_blacklist);

?>


