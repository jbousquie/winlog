<?php
header ('Content-Type: text/html; charset=utf-8');

include_once('../connexions.php');
include_once('../winlog_admin_conf.php');
include_once('../session.php');
$trombino = false;
if ($trombino_url != "") {
    $trombino = true;
}

// récupération du user CAS pour autorisation
$username = Username();
$profil = Profil($username);
FiltreProfil($profil);
$admin = ($profil == $niveaux[$roles[3]]);

// récupération de la salle demandée
$salle = addslashes($_GET['salle']);
if ($salle == '') { 
    header ('Location: ../salles_live.php'); 
}

// fonction renvoyant les machines du plan de la salle
function Machines_plan(&$ligne_machines) {
    $machines_de_la_salle = array();
    foreach ($ligne_machines as $ligne) {
        foreach ($ligne as $machinePlan) {
            $machines_de_la_salle[] = $machinePlan;
        }
    }
    return $machines_de_la_salle;
};

// fonction renvoyant les adresses mac des machines de la salle
function AdressesMac_plan(&$hosts) {
    $machines = Machines();
    $macs_de_la_salle = array();
    foreach($hosts as $host) {
        $macs_de_la_salle[] = $machines[$host][8];
    }
    return $macs_de_la_salle;
}

// fonction d'affichage du plan 2D d'une salle
function Affiche_plan_salle(&$machines_de_la_salle, &$portes) {
    
    $date_now = time();
    $machines_connectees = Connexion_machine();

    global $trombino;
    global $trombino_url;
    global $trombino_defaut_url;
    global $trombino_extension_fichier;
    global $mode_ping;
    global $seuil_couleur_ping;

    // Timestamps du ping
    if ($mode_ping) {
        $ping_timestamps = PingTimestamps();
    }

    echo('<div id="plan">');
      
    // Affichage des machines
    foreach($machines_de_la_salle as $machine) {
        // recherche du nombre de jours passés depuis la dernière utilisation de la machine
        $last_conn = Derniere_connexion_machine($machine);
        if (empty($last_conn)) {
            $date_last = $date_now;
        } 
        else {
            $debut_last = $last_conn["debut"];
            $date_last = strtotime($debut_last);        
        }
        $nb_jours = floor(($date_now - $date_last) / 86400);

        $class_jour ='';
        global $j10, $j20, $j30;
        if ($nb_jours >= $j10) { $class_jour = ' j10'; }
        if ($nb_jours >= $j20) { $class_jour = ' j20'; }
        if ($nb_jours >= $j30) { $class_jour = ' j30'; }


        $ping_info = "";
        $class_ping = "";
        if ($mode_ping) {
            $ping_delta = "indisponible";
            if (array_key_exists($machine, $ping_timestamps)) {
                $ping_ts = strtotime($ping_timestamps[$machine]);
                $ping_delta = "il y a ".FormateDelta(DateDiff($ping_ts, $date_now));
                if ($date_now - $ping_ts <= $seuil_couleur_ping) {
                    $class_ping = " ping";
                }
            }
            $ping_info = "ping : ".$ping_delta;
        }

        $class_connexion = '';
        $link = '<a href=../machine.php?id='.$machine.' target="blank" title="'.$ping_info.'">';
        $username = '';
        $ip = IP_machine($machine);
        // s'il existe une connexion sur la machine
        if (array_key_exists($machine, $machines_connectees)) {
            $class_connexion = ' conn'; 
            $class_ping = '';
            $username = $machines_connectees[$machine]["username"];
            $ip = $machines_connectees[$machine]["ip"];
        }

        $cpt = Compte($username);                       // récupère les informations sur l'utilisateur courant
        $style = "";
        $fin_style = "";
        if ($cpt[2]=="Enseignant") { 
            $style = "<b>"; 
            $fin_style="</b>"; 
        }

        // <div> machine
        $class_trombi = "";
        $img_trombi = "";
        if ($trombino && $username != '') {
            $class_trombi = ' trombi';
            $url_photo = $trombino_url."/".$username.$trombino_extension_fichier;
            $img_trombi = "<img src='".$url_photo."' onerror=\"this.error=null;this.src='".$trombino_defaut_url."';\">";
        }

       
        //$user_affich = $username;
        $div = "<div id='".$machine."' class='pc".$class_ping.$class_connexion.$class_jour.$class_trombi."'>".$link.$machine."</a><br/>".$style.$username.$fin_style.$img_trombi."<br/><span class='ip'>".$ip."</span></div>";
        echo $div;
    }

    // Affichade des portes
    foreach($portes as $key=>$porte) {
        $div_porte = "<div id='porte".$key."' ></div>";
        echo $div_porte;
    }
    echo('</div>');
}
?>
<!DOCTYPE HTML>
<html lang="fr">
<head>   
    <title>Winlog :  Connexions en cours dans les salles</title>
    <meta http-equiv="refresh" content=<?php echo($delay); ?> >
    <meta charset="utf-8">
    <meta http-equiv="Content-Style-Type" content="text/css">
    <meta http-equiv="Content-Language" content="fr">
    <link rel="stylesheet" media="screen" type="text/css" title="default" href="css.php?salle=<?php echo $salle; ?>">
    <link rel="stylesheet" media="screen" type="text/css" title="default" href="../default.css">
</head>
<body>
<?php
// Si le compte est autorisé à voir les salles, on affiche le div
if ($profil > 0) {
    include_once($salle.'.php');
    $machines_du_plan = Machines_plan($ligne_machines[$salle]);
    $macs_du_plan = AdressesMac_plan($machines_du_plan);
    $portes = $porte_coord[$salle];
    $host_json = json_encode($machines_du_plan);
    $mac_json = json_encode($macs_du_plan);

    $info_cours = '&nbsp;&nbsp;&nbsp;&nbsp;<a href="../salles_live.php">[retour]</a>';
    echo("<h3>Salle ".$salle.$info_cours."</h3>");
    if ($admin) {
        $form ='<form action="../stop.php" method="POST">';
        //$form = $form.'<input type="submit" value="fermer toutes les sessions" name="stop">';
        $form = $form.'<input type="submit" value="redémarrer toute la salle" name="stop"><input type="submit" value="éteindre toute la salle" name="stop">';
        $form = $form.'<input type="hidden" name="host" value=\''.$host_json.'\'>';
        $form = $form.'<input type="hidden" name="mac" value=\''.$mac_json.'\'>';
        $form = $form.'<input type="submit" value="démarrer toute la salle" name="stop">';
        $form = $form.'<input type="submit" value="start processus distant" name="stop">';
        $form = $form.'<input type="submit" value="stop processus distant" name="stop">';
        $form = $form.'</form>';
        echo $form;
    }
    echo("<p><span class='conn'>bleu</span> : connecté &nbsp;<span class='pc'>gris</span> : inactif &nbsp;<span class='j10'>jaune</span> : inactif 10j &nbsp;<span class='j20'>orange</span> : inactif 20j &nbsp;<span class='j30'>rouge</span> : inactif 30j &nbsp;<span class='ping'>vert</span> : ping depuis moins de $seuil_couleur_ping s");
    Affiche_plan_salle($machines_du_plan, $portes, $trombino);
}
else {
    // sinon on affiche un message
    echo("Vous n'avez pas l'autorisation d'afficher cette page");
}
?>
</body>
</html>
