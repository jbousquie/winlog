<?php
require_once 'HTTP/Request2.php';


$ip = $_GET['ip'];
$src = $_GET['src'];
$target = $_GET['tgt'];
$url = $_GET['url'];

// SECTION À COPIER DANS VOTRE PROPRE PAGE interdit.php
// ====================================================

// mettre l'URL de votre propre serveur suivie de "/proxy/squid.php"
// $url_winlog = "http://monserveur.winlog/proxy/squid";
$url_winlog = "http://winlog.iut.rdz/proxy/squid.php"; // NE PAS METTRE https à cause de la SOP IE

// Fonction GetURL() : renvoie le contenu d'une réponse à un GET http
// renvoie une string contenant le corps de la réponse http
Function GetURL($url) {
    $body = "";
    $r = new HTTP_Request2($url, HTTP_Request2::METHOD_GET);
    try {
        $response = $r->send();
        if (200 == $response->getStatus()) {
            $body = $response->getBody();
        }
    } 
    catch (HTTP_Request2_Exception $ex) {
        echo $ex->getMessage();
    }
    return $body;
};

$url_param = "$url_winlog?ip=$ip&src=$src&tgt=$target";
GetURL($url_param);
// FIN DE SECTION À COPIER
// =======================

$loi_porn = "<h3><a href=\"http://www.legifrance.gouv.fr/affichCodeArticle.do?cidTexte=LEGITEXT000006070719&idArticle=LEGIARTI000006418098&dateTexte=20090929\">Loi article 227-24</a></h3>
<p>\"Le fait soit de fabriquer, de transporter, de diffuser par quelque moyen que ce soit et quel qu'en soit le support un message à caractère <b>violent</b> ou <b>pornographique</b> ou de <b>nature à porter gravement atteinte à la dignité humaine</b>, soit de faire commerce d'un tel message, est puni de trois ans d'emprisonnement et de 75000 euros d'amende lorsque ce message est susceptible d'être vu ou perçu par un mineur.</p><p>Lorsque les infractions prévues au présent article sont soumises par la voie de la presse écrite ou audiovisuelle ou de la communication au public en ligne, les dispositions particulières des lois qui régissent ces matières sont applicables en ce qui concerne la détermination des personnes responsables.\"</p><br/><p>Rappel : il suffit <b>d'un seul mineur</b> dans l'établissement.</p>";
$msg_proxy = "<p>Vous venez d'essayer de contourner les filtres de sécurité de l'IUT.<br/>Cette démarche constitue une tentative délibérée d'enfreindre les règles de la charte Renater et de la charte informatique de l'IUT.</p><p>Cette tentative est enregistrée :</p>";
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
<div><img src="http://cache.iut-rodez.fr/warnings/vr_tn_IUT_Rodez.jpg"></div>
<div><h1>INTERDIT !!!</h1></div>
<p>L'accès au site web <b><? echo $url; ?></b> n'est pas autorisé depuis l'IUT.</p>
<p>Ce type d'interdiction vise à limiter les abus des usages non conformes à la <a href="http://cache.iut-rodez.fr/warnings/CharteInformatique.pdf">charte informatique</a> de l'établissement et à la <a href="http://www.renater.fr/IMG/pdf/charte_fr.pdf">charte Renater</a> (Renater est l'organisme fournisseur d'accès à l'internet de l'IUT).</p>
<p>Il permet par ailleurs d'améliorer la sécurité générale du système informatique en empêchant la propagation de virus, chevaux de troie, et autres logiciels espions dans les salles informatiques qui sont l'outil de travail de tous.</p>
<p>Enfin, il limite très fortement les comportements illégaux : téléchargements illégaux, non respect de la propriété intellectuelle, diffamation en ligne, etc. Il est rappelé à ce titre que chacun est responsable <b>personnellement et pénalement</b> de ses agissements sur le réseau et que l'IUT est légalement tenu de donner à la Justice, sur simple demande, l'identité des internautes contrevenants à la Loi.</p>
<?
if ($target == "adult" or $target == "haine") { 
    echo $loi_porn 
;}

?>
</body>
</html>
