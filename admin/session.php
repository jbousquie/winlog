<?php
// Ce fichier contient la gestion de la gestion applicative Winlog, différente de la session CAS/
// Il démarre une session php. Ne pas appeler en même temps que libhome.php qui démarre une session php pour CAS.
include_once('winlog_admin_conf.php');
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: '.$winlog_url);
}

// Fonction Profil() : renvoie la valeur du role de $username
// administrateur   => 2
// superviseur      => 1
// autre            => 0
function Profil($username) {
    global $administrateurs, $superviseurs;
    if (in_array($username, $administrateurs)) {
        return 2;
    } 
    elseif (in_array($username, $superviseurs)) {
        return 1;
    }
    else {
        return 0;
    }
}
?>