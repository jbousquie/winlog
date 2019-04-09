<?php
include_once('../admin/winlog_admin_conf.php');
include_once('../admin/db_access.php');

// on n'accepte que les POST venant du portail wifi autorisé
$remote_addr = $_SERVER["REMOTE_ADDR"];
if ($_SERVER["REQUEST_METHOD"] == "POST" && ($remote_addr == $portal_message_ip || $remote_addr == $radius_server_ip)) {
    $db = db_connect();

    $wifi_username = db_escape_string($db, $_POST["username"]);
    $wifi_ip = db_escape_string($db, $_POST["ip"]);
    $wifi_browser = db_escape_string($db, $_POST["agent"]);

    $req = "INSERT IGNORE INTO wifi (wifi_username, wifi_ip, wifi_browser) VALUES (\"$wifi_username\", \"$wifi_ip\", \"$wifi_browser\")";
    db_query($db, $req);
}
?>