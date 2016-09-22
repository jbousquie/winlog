<?php 

// conf serveur CAS
$cas_server = 'cas.iut-rodez.fr';
$cas_path = '/cas';
$cas_port = 443;
include_once('../logcas.php');
include_once('../winlogconf.php');               // importation de la conf mysql


$prefixe_reseau_captif = "10.3.";             // prefixe du réseau captif
$delai = "15";                                // délai en secondes de renvoi de la requête depuis le browser
$delai_vie = "40";                           // délai en secondes avant de fermer une connexion en base
                                              // le délai de vie doit être supérieur au délai de reload


$cas_user = phpCAS::getUser();
$action = $_GET['action'];
// $action : 
// si "C" =>  création d'un enregistrement de connexion
// si "U" =>  mise à jour d'un enregistrement existant (déconnexion)

$browser = $_SERVER["HTTP_USER_AGENT"];
$adresses = explode(',', $_SERVER["HTTP_X_FORWARDED_FOR"]); 
        // le header peut contenir plusieurs ip (ex: routeur Kanet) => on récupère un tableau
$wifi_ip = "";
$lg_prefixe = strlen($prefixe_reseau_captif);
foreach($adresses as $http_ip) {
  if (substr($http_ip, 0, $lg_prefixe) == $prefixe_reseau_captif) { $wifi_ip = $http_ip; }
}

$req_check = 'SELECT * FROM wifi WHERE wifi_username="'.$cas_user.'" AND wifi_ip="'.$wifi_ip.'" AND close=0'; // recherche connexion déjà ouverte
$req_con = 'INSERT INTO wifi (wifi_username, wifi_ip, wifi_browser, wifi_deb_conn, wifi_fin_conn, close) VALUES ( "'.$cas_user.'", "'.$wifi_ip.'", "'.$browser.'", CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), 0 )';

$req_clos = 'UPDATE wifi SET close = 1 WHERE close = 0 AND timestampdiff(SECOND, timestamp(wifi_fin_conn), timestamp(now())) > '.$delai_vie; // ferme TOUTES les connexions trop anciennes

$req_maj = 'UPDATE wifi SET wifi_fin_conn = CURRENT_TIMESTAMP() WHERE close = 0 AND wifi_username = "'.$cas_user.'" AND wifi_ip = "'.$wifi_ip.'"';

$db = mysql_pconnect($db_server, $db_user, $db_passwd);
mysql_select_db($db_dbname, $db);

$clos = mysql_query($req_clos, $db); // dans tous les cas, on ferme toutes les connexions trop anciennes


if ($action == "C") { 
    $deja_con = mysql_query($req_check, $db);
    $req = $req_maj;
    if (mysql_num_rows($deja_con) == 0) { $req = $req_con; } 
        // si non déjà connecté, alors connecte, sinon update
    }
if ($action=="U") { $req = $req_maj; }
$res = mysql_query($req, $db);



// on sert une page web vide qui se reloade toutes les $delai secondes dans une iframe invisible
// (seulement sur action == U, sinon une iframe charge une fois unique la page => action == C)
// mieux qu'un appel ajax : kanet.php est un client CAS, xhr ne suit pas la redirection CAS
?>
<html>
<head>
<?php 
if ($action == "U") { echo('<meta http-equiv="refresh" content="'.$delai.'">'); }
?>
</head>
<body>
still alive...
</body>
</html>
