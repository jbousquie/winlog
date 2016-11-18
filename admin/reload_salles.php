<?php
// Cette page affiche le code HTML (sans les en-têtes) de la liste des connexions par salle.
// Elle est incluse dans un <div> et rechargée à intervalles réguliers par le script salles_live.php.

include_once('connexions.php');

$machines = Machines();                             // récupération de toutes les machines connues
$machines_de_salle = Machines_de_salle($machines);  // range les machines dans le tableau $machines_de_salle
$connexion_machine = Connexion_machine();           // récupère toutes les connexions en cours

Function Get_salles_bloquees() {
    $salles_bloquees = array();
    $r = new HttpRequest("http://cache.iut-rodez.fr/salles/salles_bloquees.php", HttpRequest::METH_GET);
    try {
        $r->send();
        if ($r->getResponseCode() == 200) {
            $r->getResponseBody();
            $salles_bloquees = json_decode($r->getResponseBody());
        }
    } 
    catch (HttpException $ex) {
        echo $ex;
    }
    return $salles_bloquees;
}


// connexions dans les salles
//$salles_bloquees = Get_salles_bloquees();

while ($mdc = current($machines_de_salle)) {
    $salle = key($machines_de_salle);
    if (!in_array($salle, $salles_invisibles)) {
        $bloque = '<i><a href="bloque_salle.php?a=b&s='.strtolower($salle).'">bloque</a></i>';
        $debloque = '<i><a href="bloque_salle.php?a=d&s='.strtolower($salle).'">debloque</a></i>';
        $lien = $bloque;
        // calcul dernière plus ancienne connexion
        $jours_last_con = Connexion_doyenne_salle($machines_de_salle[$salle]);
        $class_jour ='jours j-10';
        if ($jours_last_con >= $j10) { $class_jour = 'jours j10'; }
        if ($jours_last_con >= $j20) { $class_jour = 'jours j20'; }
        if ($jours_last_con >= $j30) { $class_jour = 'jours j30'; }

        // lien bloque/debloque
        //if (in_array(strtolower($salle), $salles_bloquees)) { $lien = $debloque; }
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
        echo "<div class=\"salle\"><span id=\"b-$salle\" class=\"toggler_style toggler\"></span><a href=\"salles/?salle=$salle\" id=\"l-$salle\">$salle</a> ($i connexions sur $nb_machines_salle machines) <span id='j-".$salle."' class='".$class_jour."' title='".$class_jour."'>&nbsp;&nbsp;&nbsp;&nbsp;</span> ($lien)</div>\n";
        echo "<div class=\"connexions\" id=\"c-$salle\"><table>\n";

        if (!empty($connexion_machine)) {
        // parcours des machines connectées de la salle courante
            foreach($mdc as $mac) { 
                if (array_key_exists($mac, $connexion_machine)) {   // on n'affiche que les machines connectées
                    $username = $connexion_machine[$mac]["username"];
                    $cpt = Compte($username);                       // récupère les informations sur l'utilisateur courant
                    $style = "";
                    $fin_style = "";
                    if ($cpt[2]=="Enseignant") { $style = "<b>"; $fin_style="</b>"; }
                    echo "<tr id=\"".str_replace('.','-',$connexion_machine[$mac]["ip"])."\">";
                    echo "<td><a href=\"taches.php?machine=".$mac."\">".$style.$mac.$fin_style."</a></td>";
                    echo "<td>".$style.date("H:i:s",$connexion_machine[$mac]["stamp"]).$fin_style."</td>";
                    echo "<td>".$style.$connexion_machine[$mac]["ip"].$fin_style."</td>";
                    echo "<td>".$style.$username.$fin_style."</td>"; 
                    echo "<td>".$style.$cpt[1]." ".$cpt[0].$fin_style."</td>";
                    echo "<td>".$style.$cpt[2].$fin_style."</td>";
                    echo "</tr>\n";
                }
            }
        }
        echo "</table></div>\n";
    } // fin du test salle invisible
    next($machines_de_salle);
}

// connexions wifi
/*
$connexions_wifi = Connexions_wifi();
echo "<br/>";
echo "<br/>";
echo "<b>Connexions WIFI en cours</b> (".count($connexions_wifi) ." connexions)<br/><br/>";
echo "<div class=\"wifi\">\n";
echo "<table>\n";
// boucle sur les connexions wifi en cours
while ($wc = current($connexions_wifi)) {
$l = '<tr id="'.str_replace('.','-',$wc["ip"]).'"><td>'.date("H:i:s",$wc["debut"]).'</td><td>'.$wc["ip"].'</td><td>'.$wc["username"].'</td><td><i><a href="http://user-agent-string.info/?Fuas='.$wc["browser"].'" target="_blank">'.$wc["browser"]."</a></i></td></tr>\n";
echo $l;
next($connexions_wifi);
}
echo "</table>\n</div>\n";

// on ajoute un appel masqué au script wifi/kanet.php pour forcer la purge des connexions wifi fermées
$lien_purge = "<iframe src='/wifi/kanet.php' width='0' heigth='0' style='display: none;'></iframe>";
echo $lien_purge;
*/

// Récupération des connexions sur la blacklist Squid
// Stockage du résultat dans un dataset d'une <div> dédiée
$connexions_blacklist_live = Connexions_blacklist_live($delay, $machines);
$div_blacklist = '<div id="blacklist" data-rejected=\''.json_encode($connexions_blacklist_live).'\'></div>';
echo($div_blacklist);

?>


