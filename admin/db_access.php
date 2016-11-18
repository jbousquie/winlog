<?php
// Fonction : db_connect
// Renvoie une connexion à la base de données
function db_connect() {
    global $db_server, $db_user, $db_passwd, $db_dbname;
    $db = new mysqli($db_server, $db_user, $db_passwd, $db_dbname);
    return $db;
}

// Fonction : db_query
// Renvoie le résultat d'une requête $query sur la base $db
function db_query($db, $query) {
    $res = $db->query($query);
    return $res;
}

// Fonction : db_fetch_row
// Renvoie un tableau indexé à partir d'une ligne de résultat d'une requête
function db_fetch_row($res) {
    $row = $res->fetch_row();
    return $row;
}

// Fonction db_escape_string
// Retourne ûne chaîne échappée
function db_escape_string($db, $string) {
    $str = $db->real_escape_string($string);
    return $str;
}

// Fonction : db_free
// Libère la mémoire
function db_free($db, $res) {
    $db->free($res);
}
?>