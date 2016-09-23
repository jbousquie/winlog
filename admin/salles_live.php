<?php
  header ('Content-Type: text/html; charset=utf-8');
  include_once('libhome.php');
  include_once('winlog_admin_conf.php');
  $delay = $delay * 1000;
  $username = phpCAS::getUser();
  $autorises = array("jerome.bousquie", "rosalie.viala", "monique.malric", "dominique.seryies", "nicolas.gaven", "systeme.ut1", "thierry.deltort");
?>
<!DOCTYPE HTML>
<html lang="fr">
  <head>   
	<title>Winlog : Connexions en cours dans les salles</title>
       <meta charset="utf-8">
       <link rel="stylesheet" media="screen" type="text/css" title="default" href="default.css">
       <link rel="stylesheet" href="jquery/jquery-ui-1.10.0.custom.css">
       <script src="jquery/jquery-ui-1.10.0.custom.min.js"></script> 

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
<script> 
  // affichage erreur dans la console
  var erreurXHR = function(url) {
    console.log("erreur chargement" + url + " : " + xhr.statusText);
  };

  // emission requête XHR et récupération du résultat dans div
  var reload = function(url, div) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", url);
    xhr.onload = function(e) {
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          div.innerHTML = xhr.responseText;
        } else {
          erreurXHR(url);
        }
      }
    };
    xhr.onerror = function(e) {
      erreurXHR(url);
    };

    xhr.send(null);  // initie la requête xhr
  };

  var url = 'reload_salles.php';
  var div = document.getElementById('loaddiv');
  if (div) {
    window.setInterval(function() {
      reload(url, div);
    }, <?php echo($delay); ?>);
  }

</script>
</body>
</html>
