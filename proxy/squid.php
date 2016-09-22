<?php
//header('Content-type: application/json; charset=utf-8');
include('../admin/winlog_admin_conf.php');
include('../admin/connexions.php');
$ip = $_GET["ip"];
$src = $_GET["src"];
$target = $_GET["tgt"];
$username = "";

$prefixe_reseau_captif = "10.3.";             // prefixe du réseau captif

if ($src!="etudiants" && $src!="personnels") {
  // si src <> étudiants ou personnels :
  // la requête vient directement de cache.iut-rodez.fr : $ip reçu est fiable
  // il s'agit d'un accès interdit depuis un poste fixe de l'IUT
  // sinon :
  // la requête vient d'un iframe inclus dans la page interdit.php
  // on ne peut se baser que sur X_FORWARDED_FOR

  $adresses = explode(',', $_SERVER["HTTP_X_FORWARDED_FOR"]); 
        // le header peut contenir plusieurs ip (ex: routeur Kanet) => on récupère un tableau
  $ip = "";
  $lg_prefixe = strlen($prefixe_reseau_captif);
  foreach($adresses as $http_ip) {
    if (substr($http_ip, 0, $lg_prefixe) == $prefixe_reseau_captif) { $ip = $http_ip; }
  }
  
  $connexions_wifi = Connexions_wifi();
  $i = 0;
  while ($connexions_wifi[$i]) {
  if ($connexions_wifi[$i]["ip"] == $ip) { $username = $connexions_wifi[$i]["username"];}
  $i++;
  }

}
else { // src= etudiants ou personnels
  $con_ip = Con_ip($ip);
  if (!empty($con_ip)) { $username = $con_ip[1]; }
}

$db = db_connect();
$req_log = 'INSERT INTO proxy (ip, username, target, logts) VALUES ( "'.$ip.'", "'.$username.'", "'.$target.'", CURRENT_TIMESTAMP() )';
$req_purge = 'DELETE from proxy WHERE timestampdiff(SECOND, timestamp(logts), timestamp(now())) > 60'; // on purge les logs de plus de 1mn
$res = db_query($db, $req_purge);
$res = db_query($db, $req_log);
?>
