<?php
include("salles.conf");
$req = $_GET["s"];
$salle_demandee = $salle[$req];
if ($salle_demandee) {
    exec("sudo /usr/local/bin/bloque_salle.sh $salle_demandee");
    }
?>
