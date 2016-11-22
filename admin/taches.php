<?php
// Ce script affiche les processus en cours sur une machine windows
// Pour ceci il interroge (http) un serveur windows du domaine sur le lequel sera exécuté une commande tasklist /s $host
require_once 'HTTP/Request2.php';
include_once('winlog_admin_conf.php');
include_once('connexions.php');

Function Get_tasks($url) {
    $taches = array();
    $r = new HTTP_Request2($url, HTTP_Request2::METHOD_GET);
    try {
        $response = $r->send();
        if (200 == $response->getStatus()) {
            $body = $response->getBody();
            //$taches = json_decode(mb_convert_encoding($body, 'UTF-8'));
            $taches = json_decode($body);
        }
    } 
    catch (HTTP_Request2_Exception $ex) {
        echo $ex->getMessage();
    }
    return $taches;
}

// Variables
$host = $_GET['machine'];
$machines = Machines();
$machine = $machines[$host];
$os = $machine[1];
$os_version = $machine[3];
$adresse_ip = $machine[4];

$url = $url_taches . "?host=" . $host;           // Ghost + apache, port 81
$processus_utilisateur = 'IUT';                  // motif identifiant un processus utilisateur dans la task list
$msg = "";


// si le script est appelé sans paramètre, on revient à la page précédente
if ($host == "" ) { 
    $precedent = $_SERVER["HTTP_REFERER"];
    header("Location: $precedent"); 
    exit; 
}
$host_json = json_encode(array($host));

$proc = Get_tasks($url);
if (sizeof($proc) == 0) { 
    $msg = "La machine ".$host." n'a renvoyé aucune réponse."; 
}
else {
    $msg="<table><th>Processus</th><th>mémoire</th><th>Propriétaire</th>";
    $lig_proc = array();
    foreach($proc as $li) {
        $li = str_replace('ÿ','',$li);
        $li = str_replace('"','',$li);
        $lig_prog = explode(',', $li);
        $class_user = "";
        if (substr($lig_prog[5], 0, strlen($processus_utilisateur)) == $processus_utilisateur) { 
            $class_user = " p_user"; 
        }
        $msg = $msg."<tr class='proc".$class_user."'><td><a href=\"https://www.google.fr/search?q=".$lig_prog[0]."\" target=\"_blank\" >".$lig_prog[0]."</a></td><td>".$lig_prog[4]."</td><td>".$lig_prog[5]."</td></tr>\n";
    }
    $msg = $msg."</table>";
}
?>
<!DOCTYPE HTML>
<html lang="fr">
<head>   
    <title>Winlog : Connexions en cours dans les salles</title>
    <meta charset="utf-8">
    <meta http-equiv="refresh" content=20>
    <link rel="stylesheet" media="screen" type="text/css" title="default" href="default.css">
</head>
<body>
    <h2>Processus utilisateurs de la machine <?php echo $host; ?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="salles_live.php">[retour]</a></h2>
    <p> OS : <?php echo($os); ?> version <?php echo($os_version) ?><br/>adresse IP : <?php echo($adresse_ip) ?></p>
    <form action="stop.php" method="POST">
        <input type="hidden" name="host" value='<?php echo($host_json); ?>'>
        <!--<input type="submit" value="fermer la session" name="stop">-->
        <input type="submit" value="redémarrer cette machine" name="stop">
        <input type="submit" value="éteindre cette machine" name="stop">
    </form>
<?php
    echo $msg;
?>
</body>
</hmtl>
