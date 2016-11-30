<?php
include("salles.conf");
$req = $_GET["s"];
$salle_demandee = $salle[$req];
if ($salle_demandee) {
      exec("sudo /usr/local/bin/debloque_salle.sh $salle_demandee");
      }
?>
