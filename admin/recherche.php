<?php
// Formulaire de résultat de recherches
include_once('winlog_admin_conf.php');
include_once('db_access.php');
include_once('session.php');

$username = Username();
$profil = Profil($username);
FiltreProfil($profil);

$db = db_connect();

$objet = $_POST["objet"];
$liste_const = "";          // variable globale

switch ($objet) {
    case "connexions":
        $donnees = RechercheConnexions($db);
        break;

    case "utilisateurs":
        # code...
        break;
}

$resultats = FormatteResultats($db, $donnees);


// fonction RechercheConnexions() : renvoie un tableau de résultats 
function RechercheConnexions(&$db) {
    global $_POST;
    global $liste_const;
    $machine = db_escape_string($db, $_POST["machine"]);
    $compte = db_escape_string($db, $_POST["compte"]);
    $salle = db_escape_string($db, $_POST["salle"]);
    $ip = db_escape_string($db, $_POST["ip"]);
    $date_debut = db_escape_string($db, $_POST["date_debut"]);
    $date_fin = db_escape_string($db, $_POST["date_fin"]);

    $req_connexions = "SELECT con_id, username, hote, ip, fin_con, debut_con, close FROM connexions";
    $req_total_connexions = "SELECT con_id, username, hote, ip, fin_con, debut_con, 1 FROM total_connexions";
    $where = " WHERE ";
    $contrainte = false;
    if ($salle != "") {
        $req_connexions = $req_connexions.", machines";
        $req_total_connexions = $req_total_connexions.", machines";
        $where = $where . "hote = machine_id AND salle = \"$salle\" ";
        $contrainte = true;
        $liste_const = $liste_const. "salle = $salle<br/>";
    }
    if ($machine != "") {
        $and = ($contrainte) ? " AND " : "";
        $where = $where . $and . "hote = \"{$machine}\"";
        $contrainte = true;
        $liste_const = $liste_const. "machine = $machine<br/>";
    }
    if ($compte != "") {
        $and = ($contrainte) ? " AND " : "";
        $where = $where . $and . "username = \"{$compte}\"";
        $contrainte = true;
        $liste_const = $liste_const. "compte = $compte<br/>";
    }
    if ($ip != "") {
        $and = ($contrainte) ? " AND " : "";
        $where = $where . $and . " ip = \"{$ip}\"";
        $contrainte = true;
        $liste_const = $liste_const. "ip = $ip<br/>";
    }

    if (!$contrainte) {
        echo("vous devez saisir au moins un critère<br/>");
    }
    $req = "($req_connexions $where) UNION ($req_total_connexions $where) ORDER BY con_id DESC";
    //echo $req;
    $res = db_query($db, $req);

    return $res;
}

// fonction AfficheResultats($tab) : formatte l'affichage d'un jeu de résultats
function FormatteResultats(&$db, &$res) {
    $r = "";
    $cols = db_fetch_column_names($res);
    foreach($cols as $name) {
        $r = $r . "<th>$name</th>";
    }
    while ($li = db_fetch_row($res)) {
        $r = $r . "<tr>";
        foreach($li as $col) {
            $r = $r . "<td>$col</td>";
        }   
        $r = $r . "</tr>\n";
    }
    db_free($res);
    $resultats = "<table>\n$r</table>";
    return $resultats;
}
?>
<!DOCTYPE HTML>
<html lang="fr">
<head>   
    <title>Winlog</title>
    <meta charset="utf-8">
    <link rel="stylesheet" media="screen" type="text/css" title="default" href="default.css">
</head>
<body>
    <p class="header">WINLOG</p>
    <p>Rappel critères :<br/>
    <?php echo($liste_const); ?>
    </p>
    <?php
        echo($resultats);
    ?>
    <p class="footer">version <?php echo($winlog_version); ?></p>
</body>
</html>

