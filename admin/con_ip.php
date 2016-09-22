<?php
header('Content-type: application/json; charset=utf-8');
include('connexions.php');
$ip = $_GET["ip"];
$con_ip = Con_ip($ip);
$tab = "";
if ($con_ip) {
	$tab = json_encode($con_ip);
	}
echo $tab;
?>
