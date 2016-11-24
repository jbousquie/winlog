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

$orix["A200"] = 100;
$oriy["A200"] = 150;
$largeur["A200"] = 120;
$hauteur["A200"] = 60;
$police["A200"] = 11;

$orientation["A200"][0]="V";
$ligne_coord["A200"][0] = array(0, 0);
$ligne_machines["A200"][0] = array('VM-A20024','VM-A20023','VM-A20022','VM-A20021');
$shift["A200"][0] = 20;

$orientation["A200"][1]="V";
$ligne_coord["A200"][1] = array(130, 0);
$ligne_machines["A200"][1] = array('VM-A20020','VM-A20019','VM-A20018','VM-A20017');
$shift["A200"][1] = 20;

$orientation["A200"][2]="V";
$ligne_coord["A200"][2] = array(300, 0);
$ligne_machines["A200"][2] = array('VM-A20016','VM-A20015','VM-A20014','VM-A20013');
$shift["A200"][2] = 20;

$orientation["A200"][3]="V";
$ligne_coord["A200"][3] = array(430, 0);
$ligne_machines["A200"][3] = array('VM-A20012','VM-A20011','VM-A20010','VM-A20009');
$shift["A200"][3] = 20;

$orientation["A200"][4]="V";
$ligne_coord["A200"][4] = array(600, 0);
$ligne_machines["A200"][4] = array('VM-A20008','VM-A20007','VM-A20006','VM-A20005');
$shift["A200"][4] = 20;

$orientation["A200"][5]="V";
$ligne_coord["A200"][5] = array(730, 0);
$ligne_machines["A200"][5] = array('VM-A20004','VM-A20003','VM-A20002','VM-A20001');
$shift["A200"][5] = 20;

$orientation["A200"][6]="H";
$ligne_coord["A200"][6] = array(0, 370);
$ligne_machines["A200"][6] = array('VM-A20030','VM-A20028','VM-A20027','VM-A20026','VM-A20025');
$shift["A200"][6] = 20;

$orientation["A200"][7]="H";
$ligne_coord["A200"][7] = array(500, 450);
$ligne_machines["A200"][7] = array('VM-A20029');
$shift["A200"][7] = 20;

$orien_porte["A200"][0]="V";
$porte_coord["A200"][0]=array(850,350);

?>
