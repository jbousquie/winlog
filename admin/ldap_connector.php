<?php
# Fonctions de connexion, de lecture et d'écriture dans l'AD.
# AD requiert pour modifier le password d'accéder en ldaps (port 636).
# La vérification du certificat n'est pas faite : "REQ_CERT  never" dans /etc/ldap/ldap.conf

# Définition des constantes
define("LDAP_HOST", "ldaps://10.5.0.106/");
define("LDAP_PORT", 636);
define("LDAP_RDN", "CN=Administrateur,CN=Users,DC=iut,DC=local"); // compte Administrateur nécessaire pour écrire dans AD
define("LDAP_PASSWD", "jeer=bepa11");
define("BASE_DN", "DC=iut,DC=local"); // on se place à la racine pour accéder indifféremment aux personnels ou aux étudiants

# Fonction de connexion à l'AD.
# Cette fonction renvoie une connexion à l'AD.
function connect_AD() {
  $ldap_con = ldap_connect(LDAP_HOST, LDAP_PORT);
  ldap_set_option($ldap_con, LDAP_OPT_REFERRALS, 0); // option nécessaire pour une recherche depuis la racine du domaine
  ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3); // option nécessaire pour une recherche sur AD
  ldap_bind($ldap_con, LDAP_RDN, LDAP_PASSWD); // bind nécessaire pour lire/écrire sur AD. Les options doivent être placées AVANT le bind.
  return $ldap_con;
}

# Fonction de lecture d'une personne dans AD à partir de son username.
# Cette fonction renvoie un tableau associatif ou false si la recherche échoue.
function get_pers($username) {
  $email = $username."@iut-rodez.fr";
  $filtre = "(&(objectClass=user)(mail=".$email."))"; // on limite la recherche aux objets de type user
  $attr = array("sAMAccountname", "givenName", "sn", "mail", "distinguishedName"); // on ne récupère que quelques attributs
  $ldap_con = connect_AD(); //connexion à l'AD
  $result = ldap_search($ldap_con, BASE_DN, $filtre, $attr);
   if ($result)
	{
	$entry = ldap_first_entry($ldap_con, $result);
	if ($entry)
	  {
	  $login = ldap_get_values($ldap_con, $entry, "samaccountname");
	  $nom = ldap_get_values($ldap_con, $entry, "sn");
	  $prenom = ldap_get_values($ldap_con, $entry, "givenname");
	  $mail = ldap_get_values($ldap_con, $entry, "mail");
	  $dn = ldap_get_values($ldap_con, $entry, "distinguishedName");
          ldap_close($ldap_con);
	  return array("login"=>$login, "nom"=>$nom[0], "mail"=>strtolower($mail[0]), "prenom"=>$prenom[0], "dn"=>$dn[0]);
	  }
	}
        ldap_close($ldap_con);
	return false;
}


# Fonction de mise à jour du mot de passe d'un username.
# réf : http://msdn.microsoft.com/en-us/library/cc223248%28PROT.13%29.aspx
# Cette fonction renvoie TRUE si le mot de passe est modifié dans l'AD, FALSE sinon.
function set_password($username,$password) {
  $pers = get_pers($username);		// recherche de l'entrée correspondant au username
  if ($pers) {
    $dn_pers = $pers["dn"];		// DN de l'entrée trouvée
    $password = "\"".$password."\"";		// AD impose que le password soit encadré de deux double-quotes
    $entry['unicodePwd'] = mb_convert_encoding($password, "UTF-16LE");  // AD impose que le tout soit encodé en UTF-16
    $ldap_con = connect_AD(); //connexion à l'AD
    $change = ldap_mod_replace($ldap_con, $dn_pers, $entry);
    ldap_close($ldap_con);
    return $change;
    }
  return false;
}
	
?>
