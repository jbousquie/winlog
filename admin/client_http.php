<?php
// Client HTTP
// Ce fichier contient les fonctions clientes http
require_once 'HTTP/Request2.php';

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

// Fonction PostURL() : émet un POST http sur l'url
// paramètre : string $url et $param, un array de paramètres du POST
// retourne une string contenant le corps de la réponse http
Function PostURL($url, $params) {
    $body = "";
    $http = new HTTP_Request2( $url, HTTP_Request2::METHOD_POST);
    $http->addPostParameter($params);
    try { 
        $response = $http->send(); 
        if (200 == $response->getStatus()) {
            $body = $response->getBody();
        }
    } 
    catch (HTTP_Request2_Exception $ex) { 
        echo $ex;
    }
    return $body;
};
?>