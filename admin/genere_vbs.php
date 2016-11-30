<?php
include_once('winlog_admin_conf.php');
include_once('session.php');

$username = Username();

$profil = Profil($username);
FiltreProfil($profil);

// retour à l'envoyeur
function Quitte() {
    Quitte(); 
}

if ($profil < $niveaux[$roles[3]] OR !isset($_GET["f"])) {

}

$param = $_GET["f"];
switch ($param) {
    case "logon":
        $file = "logon.vbs";
        break;
    case "logout":
        $file = "logout.vbs";
        break;
    case "matos":
        $file = "matos.vbs";
        break;
    default:
        Quitte();
}
$filename = $repertoire_scripts.$file;
$script_template = file_get_contents($filename);
$template_code = "code=#####&";
$string_code = "code=".$server_code."&";
$template_server = "##serverwinlog##";
$script_code = str_replace($template_code, $string_code, $script_template);
$script_code = str_replace($template_server, $server_url, $script_code);

// Envoi du fichier
// désactive la mise en cache
header("Cache-Control: no-cache, must-revalidate");
header("Cache-Control: post-check=0,pre-check=0");
header("Cache-Control: max-age=0");
header("Pragma: no-cache");
header("Expires: 0");
// force le téléchargement du fichier
header("Content-Type: text/plain");
header('Content-Disposition: attachment; filename="'.$file.'"');
echo($script_code);
?>