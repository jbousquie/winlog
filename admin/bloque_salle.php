<?php
include_once('winlog_admin_conf.php');
include_once('connexions.php');
include_once('client_http.php');
include_once('session.php');

$username = Username();
$profil = Profil($username);

Function bascule_salle($url) {
    GetURL($url);
}
// blocage autorisé à partir du niveau Personnel autorisé
if ($profil >= $niveaux[$roles[1]]) {

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
