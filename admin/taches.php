<?php
// Ce script affiche les processus en cours sur une machine windows
// Pour ceci il interroge (http) un serveur windows du domaine sur le lequel sera exécuté une commande tasklist /s $host

Function get_tasks($url) {
	$salles_bloquees = array();
	$r = new HttpRequest($url, HttpRequest::METH_GET);
	try {
		$r->send();
		if ($r->getResponseCode() == 200) {
	        	$r->getResponseBody();
			$reponse = json_decode($r->getResponseBody());
	    		}
		} 
	catch (HttpException $ex) {
		echo $ex;
		}
	return $reponse;
}

// Variables

$precedent = $_SERVER["HTTP_REFERER"];
$host = $_GET['machine'];
//$url = "http://10.2.0.13/task.php?host=".$host;  // ancien seretud06
$url = "http://10.5.0.15:81/task.php?host=".$host; // Ghost + apache, port 81
$processus_utilisateur = 'IUT';                  // motif identifiant un processus utilisateur dans la task list
$msg = "";


// si le script est appelé sans paramètre, on revient à la page précédente
if ($host == "" ) { header("Location: $precedent"); exit; }
$host_json = json_encode(array($host));

$proc = get_tasks($url);
if (sizeof($proc) == 0) { $msg = "La machine ".$host." n'a renvoyé aucune réponse."; }
else {
  $msg="<table><th>Processus</th><th>mémoire</th><th>Propriétaire</th>";
  $lig_proc =array();
  foreach($proc as $li) {
    $li = str_replace('ÿ','',$li);
    $li = str_replace('"','',$li);
    $lig_prog = explode(',', $li);
    $class_user = "";
    if (substr($lig_prog[5],0,strlen($processus_utilisateur))==$processus_utilisateur) { $class_user = " p_user"; };
    $msg = $msg."<tr class='proc".$class_user."'><td><a href=\"https://www.google.fr/search?q=".$lig_prog[0]."\" target=\"_blank\" >".$lig_prog[0]."</a></td><td>".$lig_prog[4]."</td><td>".$lig_prog[5]."</td></tr>\n";
  }
  $msg = $msg."</table>";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>   
	     <title>Winlog : Connexions en cours dans les salles</title>
       <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
       <meta http-equiv="refresh" content=20>
       <meta http-equiv="Content-Style-Type" content="text/css">
       <meta http-equiv="Content-Language" content="fr">
       <link rel="stylesheet" media="screen" type="text/css" title="default" href="default.css">
  </head>
  <body>
  <h2>Processus utilisateurs de la machine <?php echo $host; ?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="salles_live.php">[retour]</a></h2>
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
