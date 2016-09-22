<?php
  include_once('winlog_admin_conf.php');
  $delay = $delay * 1000;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>   
	<title>Winlog : Connexions en cours dans les salles</title>
       <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
       <meta http-equiv="Content-Style-Type" content="text/css">
       <meta http-equiv="Content-Language" content="fr">
       <link rel="stylesheet" media="screen" type="text/css" title="default" href="default.css">
       <script src="jquery/jquery-1.4.4.min.js"></script> 
       <script> 
          var auto_refresh = setInterval(
          function()
            {
              $('#loaddiv').load('reload_ma_salle.php');
            }, <?php echo($delay); ?>);
      </script>
  </head>
<body>
<form>
 <!-- faire un bouton bloquer/débloquer l'accès Web de cette salle -->
 <!-- faire un bouton (sur un autre formulaire) bloquer/débloquer l'accès Windows de cette salle -->
</form>
<div class="salle"><? echo $salle ?></div>
<div class="connexions">
 <div id="loaddiv">
  <?php
  include('reload_ma_salle.php'); 
  ?>
  </div>
</body>
</html>
