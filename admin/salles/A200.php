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

$orix_salle["A200"] = 50;
$oriy_salle["A200"] = 200;
$largeur_salle["A200"] = 950;
$hauteur_salle["A200"] = 640;
$orix["A200"] = 50;
$oriy["A200"] = 50;
$largeur["A200"] = 120;
$hauteur["A200"] = 60;
$police["A200"] = 11;

$orientation["A200"][0]="V";
$ligne_coord["A200"][0] = array(0, 0);
$ligne_machines["A200"][0] = array('A200F4','A200F3','A200F2','A200F1');
$shift["A200"][0] = 20;

$orientation["A200"][1]="V";
$ligne_coord["A200"][1] = array(130, 0);
$ligne_machines["A200"][1] = array('A200E4','A200E3','A200E2','A200E1');
$shift["A200"][1] = 20;

$orientation["A200"][2]="V";
$ligne_coord["A200"][2] = array(300, 0);
$ligne_machines["A200"][2] = array('A200D4','A200D3','A200D2','A200D1');
$shift["A200"][2] = 20;

$orientation["A200"][3]="V";
$ligne_coord["A200"][3] = array(430, 0);
$ligne_machines["A200"][3] = array('A200C4','A200C3','A200C2','A200C1');
$shift["A200"][3] = 20;

$orientation["A200"][4]="V";
$ligne_coord["A200"][4] = array(600, 0);
$ligne_machines["A200"][4] = array('A200B4','A200B3','A200B2','A200B1');
$shift["A200"][4] = 20;

$orientation["A200"][5]="V";
$ligne_coord["A200"][5] = array(730, 0);
$ligne_machines["A200"][5] = array('A200A4','A200A3','A200A2','A200A1');
$shift["A200"][5] = 20;

$orientation["A200"][6]="H";
$ligne_coord["A200"][6] = array(0, 370);
$ligne_machines["A200"][6] = array('A200G4','A200G3','A200G2','A200G1');
$shift["A200"][6] = 20;

$orientation["A200"][7]="H";
$ligne_coord["A200"][7] = array(100, 450);
$ligne_machines["A200"][7] = array('A200H3','A200H2','A200H1','A200Z1');
$shift["A200"][7] = 20;

$orien_porte["A200"][0]="V";
$porte_coord["A200"][0]=array(850,350);

?>
