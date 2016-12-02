<?php
// On ne définit que des rangées de machines. Toutes les tailles ou coordonnées sont exprimées en pixels px.
// Une rangée est constituée de :
// - un tableau ligne_machine de machines alignées par salle $salle et par rangée $i dans la salle, contenant les machines dans l'ordre 
// (gauche à droite et haut en bas): $ligne_machine[$salle][$i] = array(machine1, machine2, ..., machineN)
// - les coordonnées (left, top) de départ dans le plan de chaque ligne $i de la salle $salle: $ligne_coord[$salle][$i] = array(top, left)
// - le sens d'affichage V/H (vertical ou horizontal) de la ligne $i de la salle $salle : $orientation[$salle][$i]= ["V" | "H" ]
// - l'espacement entre chaque machine d'une ligne $i de la salle $salle : $shift[$salle][$i] = valeur
//
// Les autres valeurs sont communes à l'ensembles des machines de la salle :
// - la taille d'une machine ($largeur[$salle], $hauteur[$salle]) est définie pour toute la salle.
// - la taille de la salle ($largeur_salle, $hauteur_salle) exprimée en pixels.
// - les coordonnées d'origine de la salle $orix_salle et $oriy_salle dans la page. Changer ces valeurs permet de déplacer le plan dans la page
// sans modifier les coordonnées relatives de chaque ligne de machine.
// - les coordonnées d'origine des machines  $orix et $oriy exprimées à partir du coin supérieur gauche de la salle. 
// - la taille de police du texte affiché dans les machines: $police[$salle]
//
// Les portes de la salles sont définies individuellement par leur orientation $orien_porte[$i][$salle]="V" | "H" (vertical/horinzontal)
// et par leur coordonnées dans le plan $porte_coord[$salle]($i]=array(x,y).
// $i représente la i-ème porte de la salle $salle.
//
// Exemple pour une salle AAA :
// La salle est placée à (50, 200) pixels du haut à gauche de la page
// $orix_salle["A102"] = 50;
// $oriy_salle["A102"] = 200;
// La salle est définie par sa largeur et sa hauteur en pixels
// $largeur_salle["A102"] = 1050;
// $hauteur_salle["A102"] = 570;
// L'origine des machines est ensuite placée à l'intérieur de la salle à (10, 10) pixels du coin gauche supérieur de la salle
// $orix["AAA"] = 10;
// $oriy["AAA"] = 10;
// Chaque machine est représentée dans un rectangle de 100x50 pixels
// $largeur["AAA"] = 100;
// $hauteur["AAA"] = 50;
// Le texte contenu aura une taille de 10pt
// $police["AAA"] = 10;
// La première ligne (0) de machines est horizontale (H), commence aux coordonnées 100,100 dans le plan,
// elle contient les 7 machines du tableau déclarées dans l'ordre de gauche à droite.
// Les machines sont espacées de 30 pixels les unes des autres.
// $orientation["AAA"][0]="H";
// $ligne_coord["AAA"][0] = array(100, 100);
// $ligne_machines["AAA"][0] = array('AAAZ1','AAAD1','AAAD2','AAAD3','AAAD4','AAAD5','AAAD6');
// $shift["AAA"][0] = "30";
// La salle a deux portes :
// $orien_porte["AAA"][0]="H";
// $porte_coord["AAA"][0]=array(0,600);
// $orien_porte["AAA"][1]="H";
// $porte_coord["AAA"][1]=array(800,600);

$orix_salle["B502"] = 50;
$oriy_salle["B502"] = 200;
$largeur_salle["B502"] = 1130;
$hauteur_salle["B502"] = 450;
$orix["B502"] = 10;
$oriy["B502"] = 10;
$largeur["B502"] = 120;
$hauteur["B502"] = 60;
$police["B502"] = 11;

$orientation["B502"][0]="H";
$ligne_coord["B502"][0] = array(280, 320);
$ligne_machines["B502"][0] = array('B502A1','B502A2','B502A3','B502A4');
$shift["B502"][0] = 20;

$orientation["B502"][1]="H";
$ligne_coord["B502"][1] = array(280, 240);
$ligne_machines["B502"][1] = array('B502B1','B502B2','B502B3','B502B4','B502B5','B502B6');
$shift["B502"][1] = 20;

$orientation["B502"][2]="H";
$ligne_coord["B502"][2] = array(280, 160);
$ligne_machines["B502"][2] = array('B502C1','B502C2','B502C3','B502C4','B502C5','B502C6');
$shift["B502"][2] = 20;

$orientation["B502"][3]="H";
$ligne_coord["B502"][3] = array(280, 80);
$ligne_machines["B502"][3] = array('B502D1','B502D2','B502D3','B502D4','B502D5','B502D6');
$shift["B502"][3] = 20;

$orientation["B502"][4]="H";
$ligne_coord["B502"][4] = array(0, 0);
$ligne_machines["B502"][4] = array('B502E1','B502E2','B502E3','B502E4','B502E5','B502E6');
$shift["B502"][4] = 20;

$orientation["B502"][5]="H";
$ligne_coord["B502"][5] = array(980, 320);
$ligne_machines["B502"][5] = array('B502Z1');
$shift["B502"][4] = 20;

$orien_porte["B502"][0]="H";
$porte_coord["B502"][0]=array(0,50);

?>
