<?php
//
// phpCAS simple client
//

// Variables
$cas_server = 'cas.iut-rodez.fr';
$cas_path = '/cas';
$cas_port = 443;

include('ldap_connector.php');

// import phpCAS lib
include_once('CAS.php');

phpCAS::setDebug();

// initialize phpCAS
phpCAS::client(CAS_VERSION_2_0,$cas_server,$cas_port,$cas_path);

// no SSL validation for the CAS server
phpCAS::setNoCasServerValidation();

// force CAS authentication
phpCAS::forceAuthentication();

// at this step, the user has been authenticated by the CAS server
// and the user's login name can be read with phpCAS::getUser().

// logout if desired
if (isset($_REQUEST['logout'])) {
	phpCAS::logout();
}
?>
