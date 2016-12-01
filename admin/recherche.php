<?php
// Formulaire de résultat de recherches
include_once('winlog_admin_conf.php');
include_once('db_access.php');
include_once('session.php');

$username = Username();
$profil = Profil($username);
FiltreProfil($profil);


// fonction RechercheConnexions() : renvoie un tableau de résultats contenant les connexions demandées
function RechercheConnexions(&$db) {
    global $_POST;
    global $liste_const;
    $machine = db_escape_string($db, $_POST["machine"]);
    $compte = db_escape_string($db, $_POST["compte"]);
    $salle = db_escape_string($db, $_POST["salle"]);
    $ip = db_escape_string($db, $_POST["ip"]);
    $date_debut = db_escape_string($db, $_POST["date_debut"]);
    $date_fin = db_escape_string($db, $_POST["date_fin"]);

    $req_connexions = "SELECT username AS 'Compte', hote AS 'Machine', debut_con AS 'Début connexion', fin_con AS 'Fin connexion', close AS 'fermée ?', ip AS 'Adresse IP', con_id AS 'Id connexion' FROM connexions";
    $req_total_connexions = "SELECT username AS 'Compte', hote AS 'Machine', debut_con AS 'Début connexion', fin_con AS 'Fin connexion', 1 AS 'fermée ?', ip AS 'Adresse IP', con_id AS 'Id connexion' FROM total_connexions";
    $where = " WHERE ";
    $contrainte = false;
    if ($salle != "") {
        $req_connexions = $req_connexions.", machines";
        $req_total_connexions = $req_total_connexions.", machines";
        $where = $where . "hote = machine_id AND salle LIKE \"$salle\" ";
        $contrainte = true;
        $liste_const = $liste_const. "salle = <i>$salle</i><br/>";
    }
    if ($machine != "") {
        $and = ($contrainte) ? " AND " : "";
        $where = $where . $and . "hote LIKE \"{$machine}\"";
        $contrainte = true;
        $liste_const = $liste_const. "machine = <i>$machine</i><br/>";
    }
    if ($compte != "") {
        $and = ($contrainte) ? " AND " : "";
        $where = $where . $and . "username LIKE \"{$compte}\"";
        $contrainte = true;
        $liste_const = $liste_const. "compte = <i>$compte</i><br/>";
    }
    if ($ip != "") {
        $and = ($contrainte) ? " AND " : "";
        $where = $where . $and . " ip LIKE \"{$ip}\"";
        $contrainte = true;
        $liste_const = $liste_const. "ip = <i>$ip</i><br/>";
    }
    if ($date_debut != "" && $date_fin != "") {
        // transformation de la date JJ/MM/AAAA en date iso AAAA-MM-JJ
        $tab_deb = explode("/", $date_debut);
        $tab_fin = explode("/", $date_fin);
        if (isset($tab_deb[2]) && isset($tab_fin[2])) {
            $isodate_d = sprintf( "%04d-%02d-%02d", (int)trim($tab_deb[2]), (int)trim($tab_deb[1]), (int)trim($tab_deb[0]) );
            $isodate_f = sprintf( "%04d-%02d-%02d", (int)trim($tab_fin[2]), (int)trim($tab_fin[1]), (int)trim($tab_fin[0]) );
            $and = ($contrainte) ? " AND " : "";
            $date_debut_00 = "$isodate_d 00:00:00";
            $date_fin_24 = "$isodate_f 23:59:59";
            $where = $where . $and . " debut_con >= \"{$date_debut_00}\" AND fin_con <= \"{$date_fin_24}\"";
            $contrainte = true;
            $liste_const = $liste_const. "du <i>$date_debut</i> au <i>$date_fin</i><br/>";    
        }     
    } 
    elseif ($date_debut != "") {
        // transformation de la date JJ/MM/AAAA en date iso AAAA-MM-JJ
        $tab_deb = explode("/", $date_debut);
        if (isset($tab_deb[2])) {
            $isodate_d = sprintf( "%04d-%02d-%02d", (int)trim($tab_deb[2]), (int)trim($tab_deb[1]), (int)trim($tab_deb[0]) );
            $and = ($contrainte) ? " AND " : "";
            $date_debut_00 = "$isodate_d 00:00:00";
            $date_debut_24 = "$isodate_d 23:59:59";
            $where = $where . $and . " debut_con >= \"{$date_debut_00}\" AND fin_con <= \"{$date_debut_24}\"";
            $contrainte = true;
            $liste_const = $liste_const. "date : <i>$date_debut</i><br/>";    
        }    
    }

    if (!$contrainte) {
        return false;
    }
    $req = "($req_connexions $where) UNION ($req_total_connexions $where) ORDER BY 'Id connexion' DESC";
    $res = db_query($db, $req);

    return $res;
}

// fonction RechercheUtilisateurs : renvoie un tableau de résultats contenant les utilisateurs demandés
function RechercheUtilisateurs(&$db) {
    global $_POST;
    global $liste_const;   

    $compte = db_escape_string($db, $_POST["compte"]);
    $nom = db_escape_string($db, $_POST["nom"]);
    $prenom = db_escape_string($db, $_POST["prenom"]);
    $groupe = db_escape_string($db, $_POST["groupe"]);

    $req_utilisateurs = "SELECT username AS 'Compte', prenom AS 'Prénom', nom AS 'Nom', groupe AS 'Groupe', compte_id AS 'Id' FROM comptes";
    $where = " WHERE ";
    $contrainte = false;
    if ($compte != "") {
        $where = $where . "compte LIKE \"$compte\" ";
        $contrainte = true;
        $liste_const = $liste_const. "compte = <i>$compte</i><br/>";
    }
    if ($nom != "") {
        $and = ($contrainte) ? " AND " : "";
        $where = $where . $and . "nom LIKE \"{$nom}\"";
        $contrainte = true;
        $liste_const = $liste_const. "machine = <i>$nom</i><br/>";
    }
    if ($prenom != "") {
        $and = ($contrainte) ? " AND " : "";
        $where = $where . $and . "prenom LIKE \"{$prenom}\"";
        $contrainte = true;
        $liste_const = $liste_const. "prenom = <i>$prenom</i><br/>";
    }
    if ($groupe != "") {
        $and = ($contrainte) ? " AND " : "";
        $where = $where . $and . "groupe LIKE \"{$groupe}\"";
        $contrainte = true;
        $liste_const = $liste_const. "groupe = <i>$groupe</i><br/>";
    }    

    if (!$contrainte) {
        return false;
    }
    $req = "($req_utilisateurs $where) ORDER BY username DESC";
    $res = db_query($db, $req);

    return $res;

}


// fonction AfficheResultats($tab) : formatte l'affichage d'un jeu de résultats
function FormatteResultats(&$db, &$res) {
    $r = "<th>n°</th>";
    $resultats = "La recherche n'a abouti à aucun résultat.";
    $nb = db_num_rows($res);
    if ($nb != 0) {
        $cols = db_fetch_column_names($res);
        foreach($cols as $name) {
            $r = $r . "<th>$name</th>";
        }
        $cpt = 1;
        while ($li = db_fetch_row($res)) {
            $li_coul = ($cpt % 2 == 0) ? "odd" : "even";
            $r = $r . "<tr class=\"$li_coul\"><td>$cpt</td>";
            foreach($li as $col) {
                $r = $r . "<td>$col</td>";
            }   
            $r = $r . "</tr>\n";
            $cpt = $cpt + 1;
        }
        db_free($res);
        $resultats = "$nb résultats trouvés<br/>\n<table>\n$r</table>";
    }
    return $resultats;
}

// =====================
// Programme principal

$db = db_connect();

$objet = $_POST["objet"];
$liste_const = "";          // variable globale
switch ($objet) {
    case "connexions":
        $donnees = RechercheConnexions($db);
        break;

    case "utilisateurs":
        $donnees = RechercheUtilisateurs($db);
        break;
}


if (!$donnees) {
    $resultats = "Vous devez saisir au moins un critère.";
}
else {
    $resultats = FormatteResultats($db, $donnees);
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
    <p><a href="<?php echo($_SERVER['HTTP_REFERER']); ?>">Retour au menu de recherche</a></p>
    <p><b><u>Rappel critères</u> :</b><br/><br/>
    <?php echo($liste_const); ?>
    </p>
    <p><b><u>Résultats</u> :</b></p>
    <?php
        echo($resultats);
    ?>
    <p><a href="<?php echo($_SERVER['HTTP_REFERER']); ?>">Retour au menu de recherche</a></p>
    <p class="footer">version <?php echo($winlog_version); ?></p>
</body>
</html>

