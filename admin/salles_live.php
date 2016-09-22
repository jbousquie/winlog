<?php
  header ('Content-Type: text/html; charset=utf-8');
  include_once('libhome.php');
  include_once('winlog_admin_conf.php');
  $delay = $delay * 1000;
  $username = phpCAS::getUser();
  $autorises = array("jerome.bousquie", "rosalie.viala", "monique.malric", "dominique.seryies", "nicolas.gaven", "systeme.ut1", "thierry.deltort");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>   
	<title>Winlog : Connexions en cours dans les salles</title>
       <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
       <meta http-equiv="Content-Style-Type" content="text/css">
       <meta http-equiv="Content-Language" content="fr">
       <link rel="stylesheet" media="screen" type="text/css" title="default" href="default.css">
        <link rel="stylesheet" href="jquery/jquery-ui-1.10.0.custom.css">
       <script src="jquery/jquery-1.9.js"></script> 
       <script src="jquery/jquery-ui-1.10.0.custom.min.js"></script> 
       <script> 
          var auto_refresh = setInterval(
          function()
            {
              $('#loaddiv').load('reload_salles.php');
            }, <?php echo($delay); ?>);
      </script>
  </head>
  <body>
  <?php
    // Si le compte est autorisé à voir les salles, on affiche le div
    if (in_array($username, $autorises)) {
      echo('<div id="loaddiv">');
      include('reload_salles.php');
      echo('</div>');
   }
  else
  {
  // sinon on affiche un message
  echo("Vous n'avez pas l'autorisation d'afficher cette page");
  }
?>
<br/>
<br/>
<a href="recup_salles.php"><i>rechargement des comptes, machines et salles</i></a>
</body>
</html>
