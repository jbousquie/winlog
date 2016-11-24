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

$orix_salle["A202"] = 50;
$oriy_salle["A202"] = 200;
$largeur_salle["A202"] = 1250;
$hauteur_salle["A202"] = 500;
$orix["A202"] = 20;
$oriy["A202"] = 40;
$largeur["A202"] = 120;
$hauteur["A202"] = 60;
$police["A202"] = 11;

$orientation["A202"][0]="H";
$ligne_coord["A202"][0] = array(0, 0);
$ligne_machines["A202"][0] = array('A202H3','A202H2','A202H1');
$shift["A202"][0] = 20;

$orientation["A202"][1]="H";
$ligne_coord["A202"][1] = array(0, 80);
$ligne_machines["A202"][1] = array('A202G3','A202G2','A202G1');
$shift["A202"][1] = 20;

$orientation["A202"][2]="H";
$ligne_coord["A202"][2] = array(480, 0);
$ligne_machines["A202"][2] = array('A202D4','A202D3','A202D2','A202D1');
$shift["A202"][2] = 20;

$orientation["A202"][3]="H";
$ligne_coord["A202"][3] = array(480, 80);
$ligne_machines["A202"][3] = array('A202C4','A202C3','A202C2','A202C1');
$shift["A202"][3] = 20;

$orientation["A202"][4]="H";
$ligne_coord["A202"][4] = array(0, 200);
$ligne_machines["A202"][4] = array('A202F3','A202F2','A202F1');
$shift["A202"][4] = 20;

$orientation["A202"][5]="H";
$ligne_coord["A202"][5] = array(0, 280);
$ligne_machines["A202"][5] = array('A202E3','A202E2','A202E1');
$shift["A202"][5] = 20;

$orientation["A202"][7]="H";
$ligne_coord["A202"][7] = array(480, 280);
$ligne_machines["A202"][7] = array('A202A4','A202A3','A202A2','A202A1');
$shift["A202"][7] = 20;

$orientation["A202"][8]="H";
$ligne_coord["A202"][8] = array(480, 200);
$ligne_machines["A202"][8] = array('A202B4','A202B3','A202B2','A202B1');
$shift["A202"][8] = 20;

$orientation["A202"][9]="H";
$ligne_coord["A202"][9] = array(1030, 240);
$ligne_machines["A202"][9] = array('A202Z1');
$shift["A202"][9] = 20;

$orien_porte["A202"][0]="H";
$porte_coord["A202"][0]=array(60,400);

$orien_porte["A202"][1]="H";
$porte_coord["A202"][1]=array(950,400);

?>
