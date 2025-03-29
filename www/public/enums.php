<?php

require_once $_SERVER["DOCUMENT_ROOT"]."../libs/modele/Poste.php";
require_once $_SERVER["DOCUMENT_ROOT"]."../libs/modele/Resultat.php";
require_once $_SERVER["DOCUMENT_ROOT"]."../libs/modele/Statut.php";

header("Content-Type: application/json");
header('Cross-Origin-Resource-Policy: *');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(isset($_GET["value"])){
        $message = match ($_GET["value"]) {
            "postes" => array("status" => 200, "response" => "Liste des postes récupérée avec succès", "data" => Poste::staticCases()),
            "statuts" => array("status" => 200, "response" => "Liste des statuts récupérée avec succès", "data" => Statut::staticCases()),
            "resultats" => array("status" => 200, "response" => "Liste des résultats récupérée avec succès", "data" => Resultat::staticCases()),
            "lieux" => array("status" => 200, "response" => "Liste des lieux récupérée avec succès", "data" => Lieu::staticCases()),
            default => array("status" => 400, "response" => "Les paramètres sont invalides"),
        };
    }
    else
        $message = array("status" => 400, "response" => "Les paramètres sont invalides");

}
else {
    $message = array("status" => 405, "response" => "Méthode non autorisée");
}
http_response_code($message["status"]);
echo json_encode($message);