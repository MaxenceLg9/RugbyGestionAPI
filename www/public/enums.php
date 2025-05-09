<?php

use function Token\apiVerifyToken;

require_once $_SERVER["DOCUMENT_ROOT"]."../libs/modele/Poste.php";
require_once $_SERVER["DOCUMENT_ROOT"]."../libs/modele/Resultat.php";
require_once $_SERVER["DOCUMENT_ROOT"]."../libs/modele/Statut.php";
require_once $_SERVER["DOCUMENT_ROOT"]."../libs/modele/Lieu.php";
require_once $_SERVER["DOCUMENT_ROOT"]."../libs/modele/Token.php";

header("Content-Type: application/json");
header('Cross-Origin-Resource-Policy: *');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');



// Vérifie si la méthode de la requête est OPTIONS et renvoie une réponse appropriée
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    $message = array("status" => 200, "response" => "Options ok", "data" => []);
} else {
    apiVerifyToken();
    if($_SERVER['REQUEST_METHOD'] == 'GET') {
        if(isset($_GET["value"])){
            $message = match ($_GET["value"]) {
                "postes" => array("status" => 200, "response" => "Liste des postes récupérée avec succès", "data" => Poste::staticCases()),
                "statuts" => array("status" => 200, "response" => "Liste des statuts récupérée avec succès", "data" => Statut::staticCases()),
                "resultats" => array("status" => 200, "response" => "Liste des résultats récupérée avec succès", "data" => Resultat::staticCases()),
                "lieux" => array("status" => 200, "response" => "Liste des lieux récupérée avec succès", "data" => Lieu::staticCases()),
                default => array("status" => 400, "response" => "Les paramètres sont invalides","data" => []),
            };
        }
        else
            $message = array("status" => 400, "response" => "Les paramètres sont invalides","data" => []);
    }
    else {
        $message = array("status" => 405, "response" => "Méthode non autorisée","data" => []);
    }
}
http_response_code($message["status"]);
echo json_encode($message);