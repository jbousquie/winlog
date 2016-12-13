<?php
require_once 'HTTP/Request2.php';

// domaine établissement
$domain = "iut-rodez.fr";
// url winlog
$url_winlog = "http://winlog.iut.rdz/wifi/";

// récupération du cookie et destruction s'il existe après récupération de sa valeur
$username = "";
$msg_compte = "";
$log_wifi = false;
if (isset($_COOKIE["winlog"])) {
    $username = $_COOKIE["winlog"];
    $msg_compte = " avec le compte <b>$username</b>";
    setcookie("winlog", "", time() - 3600, "/", $domain);
    $log_wifi = true;
}

// Fonction PostURL() : émet un POST http sur l'url
// paramètre : string $url et $param, un array de paramètres du POST
// retourne une string contenant le corps de la réponse http
Function PostURL($url, $params) {
    $body = "";
    $http = new HTTP_Request2( $url, HTTP_Request2::METHOD_POST);
    $http->addPostParameter($params);
    try { 
        $response = $http->send(); 
        if (200 == $response->getStatus()) {
            $body = $response->getBody();
        }
    } 
    catch (HTTP_Request2_Exception $ex) { 
        echo $ex;
    }
    return $body;
};


// fonction de récupération de l'IP si proxy
function get_ip() {
    //Just get the headers if we can or else use the SERVER global
    if ( function_exists( 'apache_request_headers' ) ) {
      $headers = apache_request_headers();
    } else {
      $headers = $_SERVER;
    }
    //Get the forwarded IP if it exists
    if ( array_key_exists( 'X-Forwarded-For', $headers ) && filter_var( $headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
      $the_ip = $headers['X-Forwarded-For'];
    } elseif ( array_key_exists( 'HTTP_X_FORWARDED_FOR', $headers ) && filter_var( $headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 )
    ) {
      $the_ip = $headers['HTTP_X_FORWARDED_FOR'];
    } else {
      $the_ip = filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 );
    }
    return $the_ip;
  }

if ($log_wifi) {
    $ip = get_ip();
    $agent = $_SERVER['HTTP_USER_AGENT'];
    $params = array("username" => $username, "ip" => $ip, "agent" => $agent);
    $logged = PostURL($url_winlog, $params);
}
?>
<!DOCTYPE html>
<hmtl>
<head>
<meta charset="UTF-8">
<title>WIFI - IUT de RODEZ</title>
    <style type="text/css">
      html,body{
    font-family: "Verdana",Verdana,sans-serif;
        height:100%;
        padding:0;
        margin:0;
    color: rgb(80, 80, 80);
      }
      #header{
        position: relative;
        padding-top: 10px;
        padding-left: 190px;
        background: url("https://cas.iut-rodez.fr/cas/themes/esup/images/vr_tn_IUT_Rodez.jpg") no-repeat scroll 10px center transparent;
      }
      #app-name{
        background: none repeat scroll 0% 0% rgb(167, 168, 170);
        font-family: "Verdana",Verdana,sans-serif;
        font-size: 2.2em;
        padding-top: 55px;
        padding-bottom: 45px;
        padding-left: 20px;
        color: rgb(255,255,255);
      }
      #corps{
    padding-top: 20px;
    padding-left: 2%;
      }
    </style>
</head>
<body>
<div id="header">
  <div id="app-name">RESEAU WIFI DE L'IUT DE RODEZ</div>
</div>
<div id="corps">
<h2>Avertissement</h2>
Vous êtes maintenant connecté au réseau Wifi de l'IUT de Rodez<?php echo($msg_compte); ?>.<br/>
Ce réseau Wifi d'établissement est relié à l'Internet dans le cadre du réseau national de la recherche et l'enseignement supérieur RENATER.<br/><br/>
À ce titre, les usages sont limités à ceux inhérents aux activités pédagogiques ou de recherche.<br/>
Aussi, les mêmes règles de filtrages que celles déployées sur les ordinateurs pédagogiques de l'IUT ou de l'Université de Toulouse 1 Capitole s'appliquent au trafic relevant de ce réseau Wifi.<br/>
Il est rappelé par ailleurs que toutes les activités sur ce réseau sont <b>légalement enregistrées</b> et que <b>chaque utilisateur est tenu personnellement responsable</b> de ses usages devant la Loi.<br/>
<br/>
<br/>
Vous pouvez dès à présent continuer votre navigation sur le Web en saisissant comme d'habitude l'URL de votre choix dans la barre d'adresse de votre navigateur.
</div>
</body>
</html>
