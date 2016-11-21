<?php
// Ce fichier contient les fonctions de customisation de Winlog pour des besoins spécifiques à chaque établissement.
// Exemple : affecter le nom d'une salle à une machine à partir de son nom et pas de son OU de rangement dans AD

// Retourne un nom de salle à partir d'un nom de machine ou retourne un nom par défaut
function SalleDeMachine($machine, $defaut) {
    $salle = $defaut;

    // traitement local spécifique
    // ===========================

    // Uniformisation du nom VM ou pas
    $machineNonVM = str_replace("VM-", "", $machine);

    // Si le nom de la machine commence par un nom de bâtiment, alors $salle = 4 premier cars de $machine
    if ($machineNonVM[0] == "A" || $machineNonVM[0] == "B" || $machineNonVM[0] == "C") {
        $salle = substr($machineNonVM, 0, 4);
    }
    // Cas particulier CRDOC
    if (strstr($machineNonVM, "CRDOC") != false) {
        $salle = "CRDOC";
    };

    // retourne le nom de la salle
    return $salle;
};

?>