<?php
// Ce fichier contient la gestion de la gestion applicative Winlog, différente de la session CAS/
include_once('winlog_admin_conf.php');

// fonction Username() vérifie que la session PHP existe et retourne $username, sinon renvoie sur $winlog_url
function Username() {
    session_start();
    global $_SESSION, $winlog_url;
    if (!isset($_SESSION['username'])) {
        header('Location: '.$winlog_url);
        exit();
    }
    return $_SESSION['username'];
}

// Fonction Profil() : renvoie la valeur du role de $username
// administrateur   => 2
// superviseur      => 1
// autre            => 0
function Profil($username) {
    global $administrateurs, $superviseurs;
    global $roles, $niveaux, $lib_personnel;
    if (in_array($username, $administrateurs)) {
        return $niveaux[$roles[3]];
    } 
    elseif (in_array($username, $superviseurs)) {
        return $niveaux[$roles[2]];
    }
    else {
        $compte = Compte($username);
        if ($compte[2] == $lib_personnel) {
            return $niveaux[$roles[1]];
        }
        else
        {
            return $niveaux[$roles[0]];
        }
    }
};

// Fonction FiltreProfil() : redirige vers interdit.php si profil pas autorisé
function FiltreProfil($profil) {
    if ($profil == 0) {
        header('Location: interdit.php');
        exit();
    }

};


?>