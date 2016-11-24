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


$orix["A300"] = 100;
$oriy["A300"] = 150;
$largeur["A300"] = 120;
$hauteur["A300"] = 60;
$police["A300"] = 11;

$orientation["A300"][0]="V";
$ligne_coord["A300"][0] = array(0, 0);
$ligne_machines["A300"][0] = array('A300F4','A300F3','A300F2','A300F1');
$shift["A300"][0] = 20;

$orientation["A300"][1]="V";
$ligne_coord["A300"][1] = array(130, 0);
$ligne_machines["A300"][1] = array('A300E4','A300E3','A300E2','A300E1');
$shift["A300"][1] = 20;

$orientation["A300"][2]="V";
$ligne_coord["A300"][2] = array(300, 0);
$ligne_machines["A300"][2] = array('A300D4','A300D3','A300D2','A300D1');
$shift["A300"][2] = 20;

$orientation["A300"][3]="V";
$ligne_coord["A300"][3] = array(430, 0);
$ligne_machines["A300"][3] = array('A300C4','A300C3','A300C2','A300C1');
$shift["A300"][3] = 20;

$orientation["A300"][4]="V";
$ligne_coord["A300"][4] = array(600, 0);
$ligne_machines["A300"][4] = array('A300B4','A300B3','A300B2','A300B1');
$shift["A300"][4] = 20;

$orientation["A300"][5]="V";
$ligne_coord["A300"][5] = array(730, 0);
$ligne_machines["A300"][5] = array('A300A4','A300A3','A300A2','A300A1');
$shift["A300"][5] = 20;

$orientation["A300"][6]="H";
$ligne_coord["A300"][6] = array(0, 370);
$ligne_machines["A300"][6] = array('A300G4','A300G3','A300G2','A300G1');
$shift["A300"][6] = 20;

$orientation["A300"][7]="H";
$ligne_coord["A300"][7] = array(420, 450);
$ligne_machines["A300"][7] = array('A300Z1');
$shift["A300"][7] = 20;

$orien_porte["A300"][0]="V";
$porte_coord["A300"][0]=array(850,350);


?>
