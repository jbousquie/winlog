<?php
//header('Content-type: application/json; charset=utf-8');
include('../admin/winlog_admin_conf.php');
include('../admin/connexions.php');
$ip = $_GET["ip"];
$src = $_GET["src"];
$target = $_GET["tgt"];
$username = "";

$con_ip = Con_ip($ip);
if (!empty($con_ip)) { 
    $username = $con_ip[1]; 
}

$db = db_connect();
$req_log = 'INSERT INTO proxy (ip, username, target, logts) VALUES ( "'.$ip.'", "'.$username.'", "'.$target.'", CURRENT_TIMESTAMP() )';
$req_purge = 'DELETE from proxy WHERE timestampdiff(SECOND, timestamp(logts), timestamp(now())) > 60'; // on purge les logs de plus de 1mn
$res = db_query($db, $req_purge);
$res = db_query($db, $req_log);
?>
