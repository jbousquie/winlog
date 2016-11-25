<?php
// Cette page est la fiche machine d'un poste
// Le délais de reload de la liste des tâches est le délais Winlog x 3 pour limiter les appels vers $url_taches
include_once('libhome.php');
include_once('winlog_admin_conf.php');
include_once('connexions.php');
$delayMs = $delay * 3000;
$username = phpCAS::getUser();

// test profil utilisateur
$admin = false;                     // booleen : utilisateur administrateur ?
$supervis = false;                  // booleen : utilisateur superviseur ?
if (in_array($username, $administrateurs)) {
    $admin = true;
}
if (in_array($username, $superviseurs)) {
    $supervis = true;
}

// on quitte immédiatement si non autorisé
if (!$supervis and !$admin) {
    header("Location: $winlog_url");
    exit();
}

// si le script est appelé sans paramètre, on quitte aussi
$host = $_GET['id'];
if ($host == "" ) { 
    header("Location: $winlog_url"); 
    exit(); 
}

$host_json = json_encode(array($host));

$machines = Machines();
$machine = $machines[$host];
$salle = $machine[0];
$os = $machine[1];
$os_sp = $machine[2];
$os_version = $machine[3];
$adresse_ip = $machine[4];
$marque = $machine[5];
$modele = $machine[6];
$arch = $machine[7];
$mac_addr = $machine[8];
$mac_descr = $machine[9];
?>
<!DOCTYPE HTML>
<html lang="fr">
<head>   
    <title>Winlog</title>
    <meta charset="utf-8">
    <link rel="stylesheet" media="screen" type="text/css" title="default" href="default.css">
</head>
<body>
    <h2>Machine <?php echo $host; ?> (<?php echo($salle); ?>)&nbsp;&nbsp;&nbsp;&nbsp;<a href="salles_live.php">[retour]</a></h2>
    <p> Marque : <?php echo($marque); ?><br/>
        Modèle : <?php echo($modele); ?><br/>
        Architecture : <?php echo($arch); ?></p>

    <p> OS : <?php echo($os); ?><br/>
        sp : <?php echo($os_sp); ?><br/> 
        version <?php echo($os_version) ?></p>

    <p> Interface réseau : <?php echo($mac_descr) ?><br/>
        adresse MAC : <?php echo($mac_addr) ?><br/>
        adresse IP : <b><?php echo($adresse_ip) ?></b></p>

    <?php
        if ($admin) { 
    ?>
    <form action="stop.php" method="POST">
        <input type="hidden" name="host" value='<?php echo($host_json); ?>'>
        <!--<input type="submit" value="fermer la session" name="stop">-->
        <input type="submit" value="redémarrer cette machine" name="stop">
        <input type="submit" value="éteindre cette machine" name="stop">
    </form>
    <br/>
    <?php
        }
    ?>
    <p><u>Liste des processus en cours sur la machine <?php echo($host); ?></u></p>
    <div id="processus"><i>Veuillez patienter ...</i></div>
    <script>
    
    // fonction d'affichage d'erreur dans la console
    var erreurXHR = function(url, xhr) {
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
                    erreurXHR(url, xhr);
                }
            }
        };
        xhr.onerror = function(e) {
            erreurXHR(url, xhr);
        };

    xhr.send(null);  // initie la requête xhr
    };

    // init
    var url = 'taches.php?host=' + '<?php echo($host); ?>';
    var init = function() {
        var div = document.getElementById('processus');
        if (div) {
            window.setInterval(function() {
                reload(url, div);
                }, <?php echo($delayMs); ?>);
            reload(url, div);
        }
    };
    window.onload = init;

    </script>
</body>
</hmtl>