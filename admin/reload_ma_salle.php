<?php 
include_once("connexions.php");
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
/*
Function Get_user_connected($ip) {
	$user = array();
	$r = new HttpRequest("https://winlog.iut.rdz/admin/con_ip.php?ip=$ip", HttpRequest::METH_GET);
	try {
		$r->send();
		if ($r->getResponseCode() == 200) {
	        	$r->getResponseBody();
			$user = json_decode($r->getResponseBody());
	    		}
		} 
	catch (HttpException $ex) {
		echo $ex;
		}
	return $user;
	}
*/
if ( isset($_SERVER["HTTP_X_FORWARDED_FOR"]) )
        $ip_connectee = $_SERVER["HTTP_X_FORWARDED_FOR"];
    else
        $ip_connectee = $_SERVER["REMOTE_ADDR"];

$con = Con_ip($ip_connectee);
if (empty($con) || $con[6] !== "Enseignant") { echo($msg_salle_live_non_autorise."<br/>adresse IP : ".$ip_connectee); return; } 	// on quitte immédiatement si non autorisé
$nom = $con[4];
$prenom = $con[5];
$hote = $con[2];
$machines = Machines();
$salle = $machines[$hote][0];
if ( is_null($salle) ) { echo "hors d'une salle"; return; } //on quitte immédiatement si on n'est pas sur une machine d'une salle

$bloque = '<i><a href="bloque_salle.php?a=b&s='.strtolower($salle).'">bloque</a></i>';
$debloque = '<i><a href="bloque_salle.php?a=d&s='.strtolower($salle).'">debloque</a></i>';
$lien = $bloque;
//$salles_bloquees = Get_salles_bloquees();
//if (in_array(strtolower($salle), $salles_bloquees)) { $lien = $debloque; }
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
			if ($cpt[2]=="Enseignant") { $style = "<b>"; $fin_style="</b>"; }
			echo "<tr>";
			echo "<td>".$style.$con["machine"].$fin_style."</td>";
			echo "<td>".$style.date("H:i:s",$con["stamp"]).$fin_style."</td>";
			echo "<td>".$style.$con["ip"].$fin_style."</td>";
			echo "<td>".$style.$username.$fin_style."</td>"; 
			echo "<td>".$style.$cpt[1]." ".$cpt[0].$fin_style."</td>";
			echo "<td>".$style.$cpt[2].$fin_style."</td>";
			echo "</tr>\n";
			}
	}
echo "</table>";
 ?>
