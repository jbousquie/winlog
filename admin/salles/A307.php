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

$orix_salle["A307"] = 50;
$oriy_salle["A307"] = 200;
$largeur_salle["A307"] = 850;
$hauteur_salle["A307"] = 600;
$orix["A307"] = 30;
$oriy["A307"] = 30;
$largeur["A307"] = 120;
$hauteur["A307"] = 60;
$police["A307"] = 11;

$orientation["A307"][0]="V";
$ligne_coord["A307"][0] = array(0, 0);
$ligne_machines["A307"][0] = array('A307D7','A307D6','A307D5','A307D4','A307D3','A307D2','A307D1');
$shift["A307"][0] = 20;

$orientation["A307"][1]="V";
$ligne_coord["A307"][1] = array(160, 0);
$ligne_machines["A307"][1] = array('A307C7','A307C6','A307C5','A307C4','A307C3','A307C2','A307C1');
$shift["A307"][1] = 20;

$orientation["A307"][2]="V";
$ligne_coord["A307"][2] = array(320, 0);
$ligne_machines["A307"][2] = array('A307B7','A307B6','A307B5','A307B4','A307B3','A307B2','A307B1');
$shift["A307"][2] = 20;

$orientation["A307"][3]="V";
$ligne_coord["A307"][3] = array(480, 0);
$ligne_machines["A307"][3] = array('A307A7','A307A6','A307A5','A307A4','A307A3','A307A2','A307A1');
$shift["A307"][3] = 20;

$orientation["A307"][4]="H";
$ligne_coord["A307"][4] = array(640, 240);
$ligne_machines["A307"][4] = array('A307Z1');
$shift["A307"][4] = 20;

$orien_porte["A307"][0]="H";
$porte_coord["A307"][0]=array(550,600);


?>
