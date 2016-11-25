<?php
include_once('libhome.php');
include_once('winlog_admin_conf.php');
include_once('client_http.php');

$username = phpCAS::getUser();
$admin = false;                                     // booleen : utilisateur administrateur ?
if (in_array($username, $administrateurs)) {
    $admin = true;
}

Function bascule_salle($url) {
    GetURL($url);
}

if ($admin) {

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
}
$precedent = $_SERVER["HTTP_REFERER"];
header("Location: $precedent");
?>
