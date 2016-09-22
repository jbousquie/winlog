<?php
Function bascule_salle($url) {
	$salles_bloquees = array();
	$r = new HttpRequest($url, HttpRequest::METH_GET);
	try {
		$r->send();
		if ($r->getResponseCode() == 200) {
	        	$r->getResponseBody();
			$reponse = json_decode($r->getResponseBody());
	    		}
		} 
	catch (HttpException $ex) {
		echo $ex;
		}
	return $r->getResponseCode();
}
$action = $_GET["a"];
$salle  = $_GET["s"];
$url = "";
if ($action === "b") { $url = "http://cache.iut-rodez.fr/salles/bloque_salle.php?s=$salle"; }
if ($action === "d") { $url = "http://cache.iut-rodez.fr/salles/debloque_salle.php?s=$salle"; }

if ($action != "" ) { bascule_salle($url); }
$precedent = $_SERVER["HTTP_REFERER"];
header("Location: $precedent");
?>
