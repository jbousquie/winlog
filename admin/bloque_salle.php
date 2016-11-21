<?php
require_once 'HTTP/Request2.php';
include_once('winlog_admin_conf.php');

Function bascule_salle($url) {
	$salles_bloquees = array();
	$r = new HTTP_Request2($url, HTTP_Request2::METHOD_GET);
	try {
		$response = $r->send();
        if (200 == $response->getStatus()) {
	        	$body = $response->getBody();
				$reponse = json_decode($body);
	    }
	} 
	catch (HTTP_Request2_Exception $ex) {
		echo $$ex->getMessage();
	}
	return $reponse;
}

$action = $_GET["a"];
$salle  = $_GET["s"];
$url = "";
$param = "?s=$salle"; 
if ($action === "b") { 
	$url = $url_bloque . $param;
}
if ($action === "d") { 
	$url = $url_debloque . $param;
}

if ($action != "" ) { 
	bascule_salle($url); 
}

$precedent = $_SERVER["HTTP_REFERER"];
header("Location: $precedent");
?>
