<?php
// Formulaire de résultat de recherches
include_once('winlog_admin_conf.php');
include_once('db_access.php');
include_once('session.php');

$username = Username();
$profil = Profil($username);
FiltreProfil($profil);

$res_bool = false;

// fonction RechercheConnexions() : renvoie un tableau de résultats contenant les connexions demandées
function RechercheConnexions(&$db) {
    global $_POST;
    global $liste_const;
    global $res_bool;
    $res_bool = true;

    global $trombino;
    global $trombino_url;
    global $trombino_defaut_url;
    global $trombino_extension_fichier;

    $machine = db_escape_string($db, $_POST["machine"]);
    $compte = db_escape_string($db, $_POST["compte"]);
    $salle = db_escape_string($db, $_POST["salle"]);
    $ip = db_escape_string($db, $_POST["ip"]);
    $date_debut = db_escape_string($db, $_POST["date_debut"]);
    $date_fin = db_escape_string($db, $_POST["date_fin"]);

    $req_connexions = "SELECT username AS 'Compte', hote AS 'Machine', debut_con AS 'Début connexion', fin_con AS 'Fin connexion', close AS 'fermée ?', ip AS 'Adresse IP', con_id FROM connexions";
    $req_total_connexions = "SELECT username AS 'Compte', hote AS 'Machine', debut_con AS 'Début connexion', fin_con AS 'Fin connexion', 1 AS 'fermée ?', ip AS 'Adresse IP', con_id FROM total_connexions";
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
    $req = "($req_connexions $where) UNION ($req_total_connexions $where) ORDER BY con_id DESC";
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
        $where = $where . "username LIKE \"$compte\" ";
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
    $req = "$req_utilisateurs $where ORDER BY username DESC";
    $res = db_query($db, $req);

    return $res;

}

// fonction RechercheMachines : renvoie un tableau de résultats contenant les machines demandées
function RechercheMachines(&$db) {
    global $_POST;
    global $liste_const;
    global $mode_ping;

    $machine = db_escape_string($db, $_POST["machine"]);
    $salle = db_escape_string($db, $_POST["salle"]);
    $os = db_escape_string($db, $_POST["os"]);
    $sp = db_escape_string($db, $_POST["sp"]);
    $os_version = db_escape_string($db, $_POST["os_version"]);
    $ip = db_escape_string($db, $_POST["ip"]);
    $marque = db_escape_string($db, $_POST["marque"]);
    $modele = db_escape_string($db, $_POST["modele"]);
    $arch = db_escape_string($db, $_POST["arch"]);
    $mac = db_escape_string($db, $_POST["mac"]);
    $iface = db_escape_string($db, $_POST["iface"]);

    $ping_join = "";
    $ping_col = "";
    if ($mode_ping) {
        $ping_join = " LEFT OUTER JOIN ping ON machines.machine_id = ping.machine_id ";
        $ping_col = ",  ping_timestamp AS 'Dernier ping' ";
    }
    
    $req_machines = "SELECT machines.machine_id AS 'Machine', salle AS 'Salle', adresse_ip AS 'Adresse IP', os AS 'Système', os_sp AS 'Service Pack', os_version AS 'Version'";
    $req_machines = $req_machines.", type_systeme AS 'archi OS', marque AS 'Marque', modele AS 'Modèle', mac_description AS 'Carte réseau', mac AS 'Adresse MAC', ROUND(ram/1000000000, 1) AS 'RAM (Go)', ROUND(procSpeed/1000, 1) AS 'Proc (GHz)', ROUND(diskSize/1000000000, 1) AS 'Disque C: (Go)', ROUND(freeSpace/1000000000, 1) AS 'Libre C: (Go)' $ping_col FROM machines".$ping_join;
    $where = " WHERE ";
    $contrainte = false;
    if ($machine != "") {
        $where = $where . "machines.machine_id LIKE \"$machine\" ";
        $contrainte = true;
        $liste_const = $liste_const. "machine = <i>$machine</i><br/>";
    }
    if ($salle != "") {
        $and = ($contrainte) ? " AND " : "";
        $where = $where . $and . "salle LIKE \"{$salle}\"";
        $contrainte = true;
        $liste_const = $liste_const. "salle = <i>$salle</i><br/>";
    }
    if ($os != "") {
        $and = ($contrainte) ? " AND " : "";
        $where = $where . $and . "os LIKE \"{$os}\"";
        $contrainte = true;
        $liste_const = $liste_const. "OS = <i>$os</i><br/>";
    }
    if ($sp != "") {
        $and = ($contrainte) ? " AND " : "";
        $where = $where . $and . "sp LIKE \"{$sp}\"";
        $contrainte = true;
        $liste_const = $liste_const. "Service Pack = <i>$sp</i><br/>";
    }
    if ($os_version != "") {
        $and = ($contrainte) ? " AND " : "";
        $where = $where . $and . "os_version LIKE \"{$os_version}\"";
        $contrainte = true;
        $liste_const = $liste_const. "version OS = <i>$os_version</i><br/>";
    }
    if ($ip != "") {
        $and = ($contrainte) ? " AND " : "";
        $where = $where . $and . "adresse_ip LIKE \"{$ip}\"";
        $contrainte = true;
        $liste_const = $liste_const. "adresse IP = <i>$ip</i><br/>";
    }
    if ($marque != "") {
        $and = ($contrainte) ? " AND " : "";
        $where = $where . $and . "marque LIKE \"{$marque}\"";
        $contrainte = true;
        $liste_const = $liste_const. "marque = <i>$marque</i><br/>";
    }
    if ($modele != "") {
        $and = ($contrainte) ? " AND " : "";
        $where = $where . $and . "modele LIKE \"{$modele}\"";
        $contrainte = true;
        $liste_const = $liste_const. "modèle = <i>$modele</i><br/>";
    }
    if ($arch != "") {
        $and = ($contrainte) ? " AND " : "";
        $where = $where . $and . "type_systeme LIKE \"{$arch}\"";
        $contrainte = true;
        $liste_const = $liste_const. "architecture système = <i>$arch</i><br/>";
    }
    if ($mac != "") {
        $and = ($contrainte) ? " AND " : "";
        $where = $where . $and . "mac LIKE \"{$mac}\"";
        $contrainte = true;
        $liste_const = $liste_const. "adresse MAC = <i>$mac</i><br/>";
    }
    if ($iface != "") {
        $and = ($contrainte) ? " AND " : "";
        $where = $where . $and . "mac_description LIKE \"{$iface}\"";
        $contrainte = true;
        $liste_const = $liste_const. "carte réseau = <i>$iface</i><br/>";
    }
    if (!$contrainte) {
        return false;
    }
    $req = "$req_machines $where ORDER BY machines.machine_id, salle";
    $res = db_query($db, $req);

    return $res;
}


// fonction RechercheWifi : renvoie un tableau de résultats contenant les connexions Wifi demandées
function RechercheWifi(&$db) {
    global $_POST;
    global $liste_const;
    global $res_bool;
    $res_bool = true;

    $compte = db_escape_string($db, $_POST["compte"]);
    $nom = db_escape_string($db, $_POST["nom"]);
    $prenom = db_escape_string($db, $_POST["prenom"]);
    $groupe = db_escape_string($db, $_POST["groupe"]);
    $ip = db_escape_string($db, $_POST["ip"]);
    $browser = db_escape_string($db, $_POST["browser"]);
    $date_debut = db_escape_string($db, $_POST["date_debut"]);
    $date_fin = db_escape_string($db, $_POST["date_fin"]);
    $contrainte = false;

    $req_wifi = "SELECT wifi_username AS 'Compte', nom AS 'Nom', prenom AS 'Prénom', groupe AS 'Groupe', wifi_ip AS 'Adresse IP', wifi_browser AS 'Browser/Device', wifi_deb_conn AS 'Heure connexion', close AS 'Fermée ?'";
    $req_wifi = $req_wifi." FROM wifi, comptes WHERE wifi_username = username ";
    $where = "";
    if ($compte != "") {
        $where = $where . " AND wifi_username LIKE \"$compte\" ";
        $liste_const = $liste_const. "compte = <i>$compte</i><br/>";
        $contrainte = true;
    }
    if ($nom != "") {
        $where = $where ." AND nom LIKE \"{$nom}\"";
        $liste_const = $liste_const. "nom = <i>$nom</i><br/>";
        $contrainte = true;
    }
    if ($prenom != "") {
        $where = $where . " AND prenom LIKE \"{$prenom}\"";
        $liste_const = $liste_const. "prénom = <i>$prenom</i><br/>";
        $contrainte = true;
    }
    if ($groupe != "") {
        $where = $where . " AND groupe LIKE \"{$groupe}\"";
        $liste_const = $liste_const. "groupe = <i>$groupe</i><br/>";
        $contrainte = true;
    }
    if ($ip != "") {
        $where = $where ." AND wifi_ip LIKE \"{$ip}\"";
        $liste_const = $liste_const. "adresse IP = <i>$ip</i><br/>";
        $contrainte = true;
    }
    if ($browser != "") {
        $where = $where . "AND wifi_browser LIKE \"{$browser}\"";
        $liste_const = $liste_const. "browser/device = <i>$browser</i><br/>";
        $contrainte = true;
    }
    if ($date_debut != "" && $date_fin != "") {
        // transformation de la date JJ/MM/AAAA en date iso AAAA-MM-JJ
        $tab_deb = explode("/", $date_debut);
        $tab_fin = explode("/", $date_fin);
        if (isset($tab_deb[2]) && isset($tab_fin[2])) {
            $isodate_d = sprintf( "%04d-%02d-%02d", (int)trim($tab_deb[2]), (int)trim($tab_deb[1]), (int)trim($tab_deb[0]) );
            $isodate_f = sprintf( "%04d-%02d-%02d", (int)trim($tab_fin[2]), (int)trim($tab_fin[1]), (int)trim($tab_fin[0]) );
            $date_debut_00 = "$isodate_d 00:00:00";
            $date_fin_24 = "$isodate_f 23:59:59";
            $where = $where . " AND wifi_deb_conn >= \"{$date_debut_00}\" AND wifi_deb_conn <= \"{$date_fin_24}\"";
            $liste_const = $liste_const. "du <i>$date_debut</i> au <i>$date_fin</i><br/>";
            $contrainte = true;
        }
    }
    elseif ($date_debut != "") {
        // transformation de la date JJ/MM/AAAA en date iso AAAA-MM-JJ
        $tab_deb = explode("/", $date_debut);
        if (isset($tab_deb[2])) {
            $isodate_d = sprintf( "%04d-%02d-%02d", (int)trim($tab_deb[2]), (int)trim($tab_deb[1]), (int)trim($tab_deb[0]) );
            $date_debut_00 = "$isodate_d 00:00:00";
            $date_debut_24 = "$isodate_d 23:59:59";
            $where = $where . " AND wifi_deb_conn >= \"{$date_debut_00}\" AND wifi_deb_conn <= \"{$date_debut_24}\"";
            $liste_const = $liste_const. "date : <i>$date_debut</i><br/>";
            $contrainte = true;
        }
    }
    if (!$contrainte) {
        return false;
    }
    $req = "$req_wifi $where ORDER BY wifi_deb_conn DESC";
    $res = db_query($db, $req);

    return $res;

}

// fonction AfficheResultats($tab) : formatte l'affichage d'un jeu de résultats
function FormatteResultats(&$db, &$res) {
    $r = "<th>n°</th>";
    $resultats = "La recherche n'a abouti à aucun résultat.";
    $nb = db_num_rows($res);
    $a_trombiner = array();                                 // tableau des index des champs trombinables
    $cols_username = array();                               // tableau des index des champs désignant un username
    $noms_trombinables = ["Compte", "Prénom", "Nom"];       // quelles colonnes peuvent afficher la photo ?
    $noms_username = ["Compte"];                            // quelles colonnes désignent le username ?
    if ($nb != 0) {
        $cols = db_fetch_column_names($res);
        foreach($cols as $name) {
            $r = $r . "<th>$name</th>";
            $trombo = false;
            if (in_array($name, $noms_trombinables)) {
                $trombo = true;
            }
            $a_trombiner[] = $trombo;
            $usn = false;
            if (in_array($name, $noms_username)) {
                $usn = true;
            }
            $cols_username[] = $usn;
        }

        global $trombino_url;
        global $trombino_defaut_url;
        global $trombino_extension_fichier; 
        $trombino = false;
        if ($trombino_url != "") {
            $trombino = true;
        }

        $cpt = 1;
        while ($li = db_fetch_row($res)) {
            $li_coul = ($cpt % 2 == 0) ? "odd" : "even";
            $r = $r . "<tr class=\"$li_coul\"><td>$cpt</td>";
            $username = "";
            foreach($li as $id => $col) {
                $div_trombi = "<div>";
                $fin_div = "</div>";
                if ($trombino && $a_trombiner[$id]) {
                    if ($cols_username[$id]) {
                        $username = $col;                   // récupère le username, cette colonne doit précéder les autres colonnes trombinables
                    }
                    $url_photo = $trombino_url."/".$username.$trombino_extension_fichier;
                    $div_trombi = "<div class='trombi'><img src='".$url_photo."' onerror=\"this.error=null;this.src='".$trombino_defaut_url."';\">";
                }

                $r = $r . "<td>" . $div_trombi. $col . $fin_div. "</td>";
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

    case "machines":
    $donnees = RechercheMachines($db);
    break;

    case "wifi":
    $donnees = RechercheWifi($db);
    break;

    default:
    $donnees = false;
    break;
}


if (!$donnees) {
    $resultats = "Vous devez saisir au moins un critère.";
}
else {
    $resultats = FormatteResultats($db, $donnees);
}
$note_booleens = "";
if ($res_bool) {
    $note_booleens = "<p><i>Les résultats booléens sont exprimés en chiffre : 0 = faux, 1 = vrai.</i></p>";
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
    <p class="header">WINLOG-R</p>
    <p><a href="<?php echo($_SERVER['HTTP_REFERER']); ?>">Retour au menu de recherche</a></p>
    <p><b><u>Rappel critères</u> :</b><br/><br/>
    <?php echo($liste_const); ?>
    </p>
    <p><b><u>Résultats</u> :</b></p>
    <?php
        echo($note_booleens);
        echo($resultats);
    ?>
    <p><a href="<?php echo($_SERVER['HTTP_REFERER']); ?>">Retour au menu de recherche</a></p>
    <p class="footer">version <?php echo($winlog_version); ?></p>
</body>
</html>
