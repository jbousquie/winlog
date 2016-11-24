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

$orix["A203"] = 100;
$oriy["A203"] = 150;
$largeur["A203"] = 120;
$hauteur["A203"] = 60;
$police["A203"] = 11;

$orientation["A203"][0]="H";
$ligne_coord["A203"][0] = array(350, 20);
$ligne_machines["A203"][0] = array('A203Z1');
$shift["A203"][0] = 0;

$orientation["A203"][1]="H";
$ligne_coord["A203"][1] = array(0, 100);
$ligne_machines["A203"][1] = array('A203A4','A203A3','A203A2','A203A1');
$shift["A203"][1] = 115;

$orientation["A203"][2]="H";
$ligne_coord["A203"][2] = array(0, 200);
$ligne_machines["A203"][2] = array('A203B6','A203B5','A203B4','A203B3','A203B2','A203B1');
$shift["A203"][2] = 20;

$orientation["A203"][3]="H";
$ligne_coord["A203"][3] = array(0, 300);
$ligne_machines["A203"][3] = array('A203C6','A203C5','A203C4','A203C3','A203C2','A203C1');
$shift["A203"][3] = 20;

$orientation["A203"][4]="H";
$ligne_coord["A203"][4] = array(0, 400);
$ligne_machines["A203"][4] = array('A203D6','A203D5','A203D4','A203D3','A203D2','A203D1');
$shift["A203"][4] = 20;

$orientation["A203"][5]="H";
$ligne_coord["A203"][5] = array(0, 500);
$ligne_machines["A203"][5] = array('A203E6','A203E5','A203E4','A203E3','A203E2','A203E1');
$shift["A203"][5] = 20;

$orien_porte["A203"][0]="H";
$porte_coord["A203"][0]=array(650,0);
?>
