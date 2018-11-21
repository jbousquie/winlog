<?php
// configuration de l'authentification LDAP
// Attention : si connexion en ldaps, vérifier la présence du certificat du serveur LDAP
// ou forcer la non vérification du certificat : TLS_REQCERT never dans /etc/ldap/ldap.conf
$auth_ldap_server = "ldaps://ip_serveur_ldap";                              // IP ou nom d'hôte du serveur LDAP
$auth_ldap_user   = "CN=reader,CN=Users,DC=iut,DC=local";                   // DN d'un compte autorisé en lecture sur l'annuaire
$auth_ldap_pass   = "Maude Passe";                                          // mot de passe de ce compte
$auth_ldap_basedn = "DC=iut,DC=local";                                      // DN : base de recherche des comptes
$auth_ldap_port = "636";                                                    // port du serveur : 636 (ldaps) recommandé
$auth_ldap_attribut_identifier = "sAMAccountName";                          // attribut identifiant d'un compte dans l'annuaire
$auth_ldap_AD = true;                                                       // le serveur LDAP est-il un serveur AD ?
 = "Veuillez vous authentifier avec votre compte et votre mode passe Windows"; // message sur le formulaire d'authentification LDAP
?>
