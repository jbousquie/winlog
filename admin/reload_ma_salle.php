<?php 
include_once("winlog_admin_conf.php");
include_once("connexions.php");
include_once("client_http.php");
$trombino = false;
if ($trombino_url != "") {
    $trombino = true;
}

// Fonction de récupération de la liste des salles bloquées sur SquidGuard
Function Get_salles_bloquees($url) {
	$salles_bloquees = array();
	$res = GetURL($url);
	if ($res != "") {
		$salles_bloquees = json_decode($res);
	}
	return $salles_bloquees;
};


// Récupération de l'IP origine de la requête
if ( isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ) {
    $ip_connectee = $_SERVER["HTTP_X_FORWARDED_FOR"];
}
else {
	$ip_connectee = $_SERVER["REMOTE_ADDR"];
}


// récupération de la personne connectée à l'adresse IP
$con = Con_ip($ip_connectee);
if (empty($con) || $con[6] !== $lib_personnel) { 
	echo($msg_salle_live_non_autorise."<br/>adresse IP : ".$ip_connectee); 
	return; 			// on quitte immédiatement si non autorisé
} 	
$nom = $con[4];
$prenom = $con[5];
$hote = $con[2];
$machines = Machines();
$salle = $machines[$hote][0];
if ( is_null($salle) ) { 
	echo "Connecté hors d'une salle d'une salle connue."; 
	return; 			//on quitte immédiatement si on n'est pas sur une machine d'une salle
} 

$bloque = '<i><a href="bloque_salle.php?a=b&s='.strtolower($salle).'">bloque</a></i>';
$debloque = '<i><a href="bloque_salle.php?a=d&s='.strtolower($salle).'">debloque</a></i>';
$lien = $bloque;
$salles_bloquees = Get_salles_bloquees($url_salles_bloquees);
if (in_array(strtolower($salle), $salles_bloquees)) { 
	$lien = $debloque; 
}

$connexions_de_ma_salle = Connexions_par_salle($salle);
$machines_de_salle = machines_de_salle($machines);
$nb_machines_de_ma_salle = count($machines_de_salle[$salle]);
$nb_connexions = 0;
foreach($connexions_de_ma_salle as $con) {
		if ($con["username"]) { // on ne compte que les machines connectées
			$nb_connexions++;
		}
}
echo "<div class=\"salle\">$salle ($nb_connexions connexions sur $nb_machines_de_ma_salle machines) ($lien)</div>\n";
echo "<table>";
foreach($connexions_de_ma_salle as $con) {
		if ($con["username"]) { // on n'affiche que les machines connectées
			$username = $con["username"];
			$cpt = Compte($username);
			$style = "";
			$fin_style = "";
			$div_trombi = "<div>";
			$fin_div = "</div>";
			if ($trombino) {
				$url_photo = $trombino_url."/".$username.$trombino_extension_fichier;
				$div_trombi = "<div class='trombi'><img src='".$url_photo."' onerror=\"this.error=null;this.src='".$trombino_defaut_url."';\">";
			}
			if ($cpt[2]=="Enseignant") { $style = "<b>"; $fin_style="</b>"; }
			echo "<tr>";
			echo "<td>".$style.$con["machine"].$fin_style."</td>";
			echo "<td>".$style.date("H:i:s",$con["stamp"]).$fin_style."</td>";
			echo "<td>".$style.$con["ip"].$fin_style."</td>";
			echo "<td>".$div_trombi.$style.$username.$fin_style.$fin_div."</td>"; 
			echo "<td>".$div_trombi.$style.$cpt[1]." ".$cpt[0].$fin_style."</td>"; 
			echo "<td>".$style.$cpt[2].$fin_style."</td>";
			echo "</tr>\n";
			}
	}
echo "</table>";

 ?>
