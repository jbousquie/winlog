<?php
// Client HTTP
require_once 'HTTP/Request2.php';

// fonction GetURL() : renvoie le contenu d'une réponse à un GET http
// renvoie une string contenant le corps de la réponse
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
?>