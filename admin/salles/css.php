<?php
// CSS généré dynamiquement pour chaque plan de salle.
// Place les <div> de machines en fonction de leurs coordonnées dans les fichiers de salles.

$salle = $_GET["salle"];
if ($salle == '') {
    header('Location: ../salles_live.php'); 
    exit;
}

// on envoit un header de type CSS
header('content-type: text/css');


// Chargement des définitions des salles
// =====================================
include('A102.php');
include('A103.php');
include('A104.php');
include('A105.php');
include('A200.php');
include('A201.php');
include('A202.php');
include('A203.php');
include('A205.php');
include('A300.php');
include('A304.php');
include('A307.php');
include('B501.php');
include('B502.php');
include('CRDOC.php');
include('C200.php');
include('TEST.php');
include('STOCK.php');
include('batA.php');
include('batB.php');
include('batC.php');


// Variables générales
$rangee = $ligne_machines[$salle];
$rangee_coord = $ligne_coord[$salle];
$ori_x = $orix[$salle];
$ori_y = $oriy[$salle];
$larg = $largeur[$salle];
$haut = $hauteur[$salle];
$esp = $shift[$salle];
$orient = $orientation[$salle];
$xy_portes = $porte_coord[$salle];

// Styles globaux
$coul_libre = "#E0E0E0"; 
$coul_occupe = "#b1d7e8";
$coul_hs10 = "#f3e575";
$coul_hs20 = "#e17b17";
$coul_hs30 = "#d5382c";
echo "body {font-family: arial;}\n";
echo "#plan {background-color: Beige; border:1px solid black; position: absolute; top: ".$oriy_salle[$salle]."px; left: ".$orix_salle[$salle]."px; height: ".$hauteur_salle[$salle]."px; width: ".$largeur_salle[$salle]."px; z-index: -1;}\n";
echo ".pc {font-size: ".$police[$salle]."pt; background-color:".$coul_libre.";  width: ".$larg."px;\n  height: ".$haut."px;\n  border:1px dotted black;\n }\n";
echo ".conn {background-color:".$coul_occupe."; }\n";
echo ".j10 {background-color:".$coul_hs10."; }\n";
echo ".j20 {background-color:".$coul_hs20."; }\n";
echo ".j30 {background-color:".$coul_hs30."; }\n";
echo ".ip {font-size: 80%; }\n";

// Calcul et placement des div

// boucle sur les rangées
foreach($rangee as $key=>$machines) {
    // on se place aux origines du plan de la salle
    $x_ran = $ori_x + $rangee_coord[$key][0];
    $y_ran = $ori_y + $rangee_coord[$key][1];
    $x = $x_ran;
    $y = $y_ran;

    // boucle sur les machines de la rangée
    foreach($machines as $machine) {
        $id_machine = "#".$machine." {\n  position: absolute;\n  top: ".$y."px;\n  left: ".$x."px;\n  border: 1px solid black;\n}\n";
        echo $id_machine;
        if ($orient[$key] == "V") { 
            $y = $y + $haut + $esp[$key]; 
        } else { 
            $x = $x + $larg + $esp[$key]; 
        }
    }
}

// boucle sur les portes de la salle
foreach($xy_portes as $key=>$xy_porte) {
    $x = $xy_porte[0]+$ori_x;
    $y = $xy_porte[1]+$ori_y;
    $img_porte = "porteH.png";
    // pour la lisibilité du plan, une porte a une longueur double de celle d'une machine et une largeur fixée à 30px
    $long_p = 2*$larg;
    $larg_p = 30;
    $bcksze = $long_p."px ".$larg_p."px";
    $divsze = "width: ".$long_p."px;\n  height: ".$larg_p."px;";
    if ($orien_porte[$salle][$key] == "V") { 
      $img_porte = "porteV.png"; $bcksze=$larg_p."px ".$long_p."px"; $divsze = "width: ".$larg_p."px;\n  height: ".$long_p."px;";
    }
    $id_porte = "#porte".$key." {\n  position: absolute;\n  top: ".$y."px;\n  left: ".$x."px;\n  background-size: ".$bcksze.";\n  background-image:url(".$img_porte.");\n  ".$divsze."\n  background-repeat: no-repeat;\n}\n";
    echo $id_porte;
}
?>
