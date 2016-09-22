<?php
include 'XMPPHP/XMPP.php';

$pc=$_GET["pc"];

#Use XMPPHP_Log::LEVEL_VERBOSE to get more logging for error reports
#If this doesn't work, are you running 64-bit PHP with < 5.2.6?
$conn = new XMPPHP_XMPP('jabber.iut.rdz', 5222, 'winlog', 'winlog', 'xmpphp', 'iut.rdz', $printlog=false, $loglevel=XMPPHP_Log::LEVEL_INFO);

try {
    $conn->connect();
    $conn->processUntil('session_start');
    $conn->presence();
    //$msg = "task ".$pc." | findstr DOMETUD | sort";
    //$msg = "reload-execute";
    $msg = "task ".$pc." | findstr IUT | sort";
    $conn->message('windows@iut.rdz', $msg);
    $conn->disconnect();
} catch(XMPPHP_Exception $e) {
    die($e->getMessage());
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Machine <? echo($pc); ?></title>
        <script type="text/javascript"  src="jquery/jquery-1.4.4.min.js"></script> 
        <script type="text/javascript"  src="strophejs/core.js"></script> 
        <script type="text/javascript"  src="strophejs/md5.js"></script> 
        <script type="text/javascript"  src="strophejs/base64.js"></script> 
        <script type="text/javascript" src="infopc.js"></script> 
    </head>
    <body>




        <h1>Machine <? echo($pc) ; ?></h1>


	<h2>Processus</h2>
	<div id="resultats"></div>
	<br>
	<h3>Notifications</h3>	
        <div id="notifications"></div>
    </body>
</html>

