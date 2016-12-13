<?php
include_once('winlog_admin_conf.php');
include_once('db_access.php');

// Fonction : Connexions_par_salle
// Renvoie les connexions par salle sous la forme d'un Array 
function Connexions_par_salle($salle) {
    $machines_de_salles = Machines_de_salle(Machines());
    $machines_de_ma_salle = $machines_de_salles[$salle];
    $machines_connectees = Connexion_machine();
    $connexions_par_salle = array();
    $i = 0;
    foreach($machines_de_ma_salle as $machine) {
        if (array_key_exists($machine, $machines_connectees)) {
            $connexions_par_salle[$i] = array();
            $connexions_par_salle[$i]["machine"] = $machine;
            $connexions_par_salle[$i]["username"] = $machines_connectees[$machine]["username"];
            $connexions_par_salle[$i]["stamp"] = $machines_connectees[$machine]["stamp"];
            $connexions_par_salle[$i]["ip"] = $machines_connectees[$machine]["ip"];
            $i++;
        }
    }

  return $connexions_par_salle; 
};

// Fonction : Machines()
// Renvoie toutes les machines existantes
// Retourne un tableau associatif : machines[$machine_id] = ($salle, $os, $os_sp, $os_version, $ip_fixe)
function Machines() {
    //$machines = array() ;
    $db = db_connect();

    $req = 'SELECT machine_id, salle, os, os_sp, os_version, adresse_ip, marque, modele, type_systeme, mac, mac_description, ram, procSpeed, diskSize, freeSpace FROM machines ORDER BY salle, machine_id';
    $res = db_query($db, $req);
    while ($mac = db_fetch_row($res)) {
        $machines[$mac[0]] = array($mac[1], $mac[2], $mac[3], $mac[4], $mac[5], $mac[6], $mac[7], $mac[8], $mac[9], $mac[10], $mac[11], $mac[12], $mac[13], $mac[14]);
        }
    db_free($res);
    return $machines;
}
// Fonction : Machines_de_salle($machines)
// Renvoie les machines existantes des salles du tableau $machine (si le tableau est vide, alors appel à Machines()
// Retourne un tableau associatif machines_de_salle[$salle_id]=($machine_id1, $machine_id2, ...)
// En fait, cette fonction inverse le tableau associatif de la fonction machine sur la clé $salle
function Machines_de_salle(&$machines) {
    $machines_de_salle = array() ;
    if (!$machines ) { $machines = Machines(); }
    while ($mac = current($machines)) {
        $machines_de_salle[$mac[0]][] = key($machines);
        next($machines);
        }
    return $machines_de_salle;
}

// Fonction Connexion_machine()
// Renvoie un tableau associatif des machines connectées: 
//           connexion_machines[$machine_id]["username"] = $username
//                 connexion_machines[$machine_id]["ip"] = $ip
//                 connexion_machines[$machine_id]["stamp"] = $stamp
function Connexion_machine() {
    $connexion_machine = array();
    $connexions = Connexions();
    if (!empty($connexions)) {
        foreach($connexions as $con) {
            $connexion_machine[$con["hote"]] = array();
            $connexion_machine[$con["hote"]]["username"] = $con["username"];
            $connexion_machine[$con["hote"]]["ip"] = $con["ip"];
            $connexion_machine[$con["hote"]]["stamp"] = $con["stamp"];
            }
    }
    return $connexion_machine;
}

// Fonction Compte($username)
// Renvoie les informations sur l'utilisateur du compte
// Retourne un array (nom, prenom, groupe)
function Compte($username) {
    $db = db_connect();
    $req = "SELECT nom, prenom, groupe FROM comptes WHERE username=\"{$username}\"";
    $res = db_query($db, $req);
    $compte = db_fetch_row($res);
    db_free($res);
    return $compte;
}

// Fonction IP_machine($machine_id)
// Renvoie la chaîne adresse_ip de la machine dans la table machines
function IP_machine($machine_id) {
    $db = db_connect();
    $req = "SELECT adresse_ip FROM machines WHERE machine_id = \"{$machine_id}\"";
    $res = db_query($db, $req);
    $ip = db_fetch_row($res)[0];
    db_free($res);
    return $ip;
}


// Fonction : Con_ip($ip)
// Renvoie les informations de la connexion en cours relative à l'IP donnée
// Retourne un array (con_id, username, hote, stamp, nom, prenom, groupe)
function Con_ip($ip) {
    $con = Connexions();
    $con_ip = array();
    $i = 0;
    $count = count($con);
    while($i < $count) {
        if ($con[$i]["ip"] == $ip ) {
            $cpt = Compte($con[$i]["username"]);
            $con_ip = array($con[$i]["con_id"], $con[$i]["username"], $con[$i]["hote"], $con[$i]["stamp"], $cpt[0], $cpt[1], $cpt[2]); 
            break;          
            }
        $i++;
        }
    return $con_ip;
}


// Fonction : Connexions()
// Renvoie les connexions windows en cours du jour
// Retourne un array indexé de connexions :     
//            con[i]["con_id"] : id de connexion dans la table connexions
//                      con[i]["username"] : login windows
//                      con[i]["hote"] : nom de la machine
//                      con[i]["stamp"] : timestamp unix de la connexion
//                      con[i]["ip"] : adresse IP de la machine
function Connexions() {
    $connexions = array();
    $db = db_connect();

    $req = 'select con_id, username, hote, unix_timestamp(debut_con), ip from connexions where close=0 and (to_days(now())-to_days(debut_con)<1) order by username';
    $res = db_query($db, $req);
    $i = 0;
    while ($con = db_fetch_row($res)) {
        $connexions[$i] = array();
        $connexions[$i]["con_id"] = $con[0];
        $connexions[$i]["username"] = $con[1];
        $connexions[$i]["hote"] = $con[2];
        $connexions[$i]["stamp"] = $con[3];
        $connexions[$i]["ip"] = $con[4];
        $i++;
        }
    db_free($res);
    return $connexions;
}


// Fonction Derniere_connexion_machine($hote)
// Renvoie les informations sur la dernière connexion sur la machine $hote
// Retourne un array :
//          last_conn["username"] : login windows
//          last_conn["debut"] : timestamp du début de la dernière connexion
//          last_conn["fin"] : timestamp de la fin de la dernière connexion ( = debut, si connexion non encore terminée)
//          last_conn["close"] : 0 | 1 booléen indiquant si la connexion est terminée (1=close)
// NOTE : la recherche est faite sur les tables connexions ET total_connexions au cas la dernière connexion soit très ancienne
function Derniere_connexion_machine($hote) {  
    $last_conn = array();
    $db = db_connect();

    $req = '(SELECT username, debut_con, fin_con, close, con_id FROM `connexions` WHERE hote="'.$hote.'")';
    $req = $req . ' UNION (SELECT username, debut_con, fin_con, 1, con_id FROM `total_connexions` WHERE hote="'.$hote.'")';
    $req = $req . ' ORDER BY con_id DESC LIMIT 1';
    $res = db_query($db, $req); 
    while ($con = db_fetch_row($res)) {
        $last_conn["username"] = $con[0];
        $last_conn["debut"] = $con[1];
        $last_conn["fin"] = $con[2];
        $last_conn["close"] = $con[3];
    }
    db_free($res);
  return $last_conn; 
}


// Fonction Connexion_doyenne_salle 
// Renvoie la plus plus ancienne connexion d'une salle :
// renvoie un entier correspondant au nombre de jours depuis la dernière connexion la plus ancienne dans la salle
function Connexion_doyenne_salle(&$machines_de_la_salle) {
  $date_now = time();
  $date_last = $date_now;
  foreach($machines_de_la_salle as $machine) {
      $last_conn = Derniere_connexion_machine($machine);
      if (!empty($last_conn)) {
          $debut_last_machine = $last_conn["debut"];
          $date_last_machine = strtotime($debut_last_machine);
          if ($date_last_machine < $date_last) { $date_last = $date_last_machine; }
      }
  }
  $nb_jours = floor(($date_now-$date_last) / 86400);
  return $nb_jours;
}

// Fonction Salles()
// Renvoie un tableau de la liste des salles
function Salles() {
    $salles = array();
    $db = db_connect();
    $req = 'select salle_id from salles';
    $res = db_query($db, $req);
    $i = 0;
    while ($sal = db_fetch_row($res)) {
        $salles[$i] = $sal[0];
        $i++;
    }
    return $salles;
}

// Fonction NbConnexions()
// Renvoie le total des connexions dans la table
function NbConnexions() {
    $db = db_connect();
    $req = 'SELECT COUNT(*) FROM total_connexions';
    $res = db_query($db, $req);
    $count = db_fetch_row($res);
    return $count[0];
}

// Fonction PremiereConnexion()
// renvoie la date de la toute première connexion
function PremiereConnexion() {
    $db = db_connect();
    $req = 'SELECT debut_con FROM total_connexions ORDER BY con_id LIMIT 1';
    $res = db_query($db, $req);
    $prem = db_fetch_row($res);
    return $prem[0];
}


// Fonction Connexions_wifi()
// Ferme les connexions antérieures au jour courant
// Renvoie les connexions wifi non marquées "close" en base
// Retourne un array indexé de connexions :     
//            con_wifi[i]["id"] : id de connexion wifi
//            con_wifi[i]["username"] : login CAS
//            con_wifi[i]["ip"] : ip allouée
//            con_wifi[i]["browser"] : user agent
//            con_wifi[i]["debut"] : timestamp du début de la connexion
//            con_wifi[i]["prenom"] : prénom de l'utilisateur
//            con_wifi[i]["nom"] : nom de l'utilisateur
function Connexions_wifi() {
    $connexions_wifi = array();
    $db = db_connect();

    $req_close = "UPDATE wifi SET close = 1 WHERE DATE(wifi_deb_conn) < CURDATE()";
    $req = "SELECT wifi_id, wifi_username, wifi_ip, wifi_browser, wifi_deb_conn, prenom, nom FROM wifi, comptes WHERE close = 0 AND username = wifi_username ORDER BY wifi_deb_conn DESC";

    db_query($db, $req_close);
    $res = db_query($db, $req);
    $i = 0;
    while ($con = db_fetch_row($res)) {
        $connexions_wifi[$i]["id"] = $con[0];
        $connexions_wifi[$i]["username"] = $con[1];
        $connexions_wifi[$i]["ip"] = $con[2];
        $connexions_wifi[$i]["browser"] = $con[3];
        $connexions_wifi[$i]["debut"] = $con[4];
        $connexions_wifi[$i]["prenom"] = $con[5];
        $connexions_wifi[$i]["nom"] = $con[6];
        $i++;
    }

    db_free($res);
    return $connexions_wifi;   
}

// function Connexions_blacklist_live($delay, $machines)
// Renvoie les enregistrements de la table proxy de moins de $delay secondes : dernières touches sur la blacklist
// Retourne un array indexés : (ip, username, thème_blacklist) :
//            $connexion_bl_live[$i]["ip"] : ip de la machine à l'origine de la requête sur une URL blacklistée
//            $connexion_bl_live[$i]["username"] : username CAS à l'origine de la requête sur une URL blacklistée
//            $connexion_bl_live[$i]["target"] : thème de la blacklist concerné - adult, warez, games, etc
//            $connexion_bl_live[$i]["hote"] : le nom de la machine s'il existe dans la table connexions

function Connexions_blacklist_live($delay, &$machines) {
    $connexions_bl_live = array();
    $db = db_connect();

    $req = 'select proxy.ip, proxy.username, target, hote from proxy left join connexions ON proxy.ip = connexions.ip where timestampdiff(SECOND, timestamp(logts), timestamp(now())) < '.($delay); // on récupère les logs non checkés datant moins de $delay
    $res = db_query($db, $req);
    $i = 0;
    while ($log = db_fetch_row($res)) {
        $connexions_bl_live[$i]["ip"] = $log[0];
        $connexions_bl_live[$i]["username"] = $log[1];
        $connexions_bl_live[$i]["target"] = $log[2];
        $connexions_bl_live[$i]["hote"] = $log[3];
        $connexions_bl_live[$i]["salle"] = null;
        // si la connexion vient d'une machine connue d'une salle
        // on récupère le nom de la machine et de la salle
        if ($log[3]) {                                  
            $connexions_bl_live[$i]["salle"] = $machines[$log[3]][0];
        }
        $i++;
        }

    db_free($res);
    return $connexions_bl_live;   
}

// Fonction ArchiveConnexions() : 
// Ferme les connexions encore ouvertes des jours antérieurs au jour courant dans la table connexions
// Copie toutes les connexions fermées des jours antérieurs dans la table total_connexions
// Purge les connexions copiées de la table connexions
// Retourne le nombre de connexions archivées
function ArchiveConnexions() {
    $db = db_connect();

    $req_marque_archivables = 'UPDATE connexions SET close = 1, archivable = 1 where DATE(fin_con) < CURDATE()';
    $res = db_query($db, $req_marque_archivables);
    $nb_archivables = db_affected_rows($db);

    if ($nb_archivables != 0) {
        $req_archive = 'INSERT INTO total_connexions SELECT con_id, username, hote, ip, fin_con, debut_con FROM connexions WHERE archivable = 1';
        $req_purge_archivees = 'DELETE FROM connexions WHERE archivable = 1';
        db_query($db, $req_marque_archivables);
        db_query($db, $req_archive);
        db_query($db, $req_purge_archivees);
    }
    return $nb_archivables;
}


?>
