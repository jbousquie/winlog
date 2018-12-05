<?php
// Mise à jour de la table ping depuis le fichier produit par le démon ping
// Ce script est appelé depuis ping/winlog_ping.sh
include_once('winlog_admin_conf.php');
include_once('db_access.php');

$fichier = fopen($fichier_liste_ping, "r");
if ($fichier) {
    $db = db_connect();
    while (!feof($fichier)) {
        $ip = rtrim(fgets($fichier));
        if ($ip != '') {
            $req_ping_update = "INSERT INTO ping (machine_id) SELECT machine_id FROM machines WHERE machines.adresse_ip = '{$ip}' ON DUPLICATE KEY UPDATE ping_timestamp = CURRENT_TIMESTAMP;";
            db_query($db, $req_ping_update);
        }
    }
    fclose($fichier);
}
?>