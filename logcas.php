<?php
//
// phpCAS simple client
//


// import phpCAS lib
include_once('CAS/CAS.php');

//phpCAS::setDebug();

// initialize phpCAS
phpCAS::client(CAS_VERSION_2_0,$cas_server,$cas_port,$cas_path);

// no SSL validation for the CAS server
phpCAS::setNoCasServerValidation();

// check CAS authentication
phpCAS::checkAuthentication();

// at this step, the user has been authenticated by the CAS server
// and the user's login name can be read with phpCAS::getUser().
function Autorise($user, $liste_users_autorises) {
  return in_array($user, $liste_users_autorises);
}

// logout if desired
if (isset($_REQUEST['logout'])) {
	phpCAS::logout();
}
?>
