<?php
// Fonction : db_connect
// Renvoie une connexion à la base de données
function db_connect() {
    global $db_server, $db_user, $db_passwd, $db_dbname;
    $db = new mysqli($db_server, $db_user, $db_passwd, $db_dbname);
    $db->set_charset('utf8');
    return $db;
}

// Fonction : db_query
// Renvoie le résultat d'une requête $query sur la base $db
function db_query($db, $query) { // Retrait du &
    $res = $db->query($query);
    return $res;
}

// Fonction : db_fetch_row
// Renvoie un tableau indexé à partir d'une ligne de résultat d'une requête
function db_fetch_row($res) { // Retrait du &
    // Sécurité au cas où $res serait faux/null suite à une requête échouée
    if (!$res) return false; 
    $row = $res->fetch_row();
    return $row;
}

// Fonction : db_fetch_column_names()
// Renvoie un tableau indexé contenant les noms des colonnes du résultat
function db_fetch_column_names($res) { // Retrait du &
    if (!$res) return array();
    $fields = $res->fetch_fields();
    $col_names = array();
    foreach($fields as $col) {
        $col_names[] = $col->name;
    }
    return $col_names;
}

// Fonction : db_num_rows()
// Renvoie le nombre de ligne d'un résultat 
function db_num_rows($res)  { // Retrait du &
    if (!$res) return 0;
    $num = $res->num_rows;
    return $num;
}

// Fonction db_escape_string
// Retourne une chaîne échappée
function db_escape_string($db, $string) { // Retrait du &
    $str = $db->real_escape_string($string);
    return $str;
}

// Fonction db_affected_rows
// Retourne le nombre de lignes affectées par la dernière opération
function db_affected_rows($db) { // Retrait du &
    $nb = $db->affected_rows;
    return $nb;
}

// Fonction : db_free
// Libère la mémoire
function db_free($res) { // Retrait du &
    if ($res) {
        $res->free();
    }
} // Pensez à fermer l'accolade ici si elle manquait
?>
