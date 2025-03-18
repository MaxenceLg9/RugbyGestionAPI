<?php
require_once "../libs/modele/Match.php";

use function MatchDeRugby\delete, MatchDeRugby\create, MatchDeRugby\read, MatchDeRugby\readById, MatchDeRugby\update;

header("Content-Type: application/json");

//check du jeton avec une requête POST sur l'api d'authentification
/*
if(!apiVerifyToken()){

}
*/

$jsonBody = json_decode(file_get_contents('php://input'), true);

function validateDate($date, $format = 'Y-m-d H:i:s') : bool {
    $d = DateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
    return $d && strtolower($d->format($format)) === strtolower($date);
}

function checkBody(mixed $jsonBody): bool {
    return isset($jsonBody["adversaire"]) && isset($jsonBody["dateHeure"]) && isset($jsonBody["lieu"]) && in_array($jsonBody["lieu"], ["DOMICILE", "EXTERIEUR"]) && validateDate($jsonBody["dateHeure"]);
}


if($_SERVER['REQUEST_METHOD'] == 'GET')
    if(isset($_GET["idMatch"]))
        $message = array("status" => 200, "response" => "Match récupéré avec succès", "data" => readById($_GET["idMatch"]));
     else
        $message = array("status" => 200, "response" => "Liste des Matchs récupérés avec succès", "data" => read());

else if($_SERVER['REQUEST_METHOD'] == 'POST')
    if(checkBody($jsonBody))
        $message = array("status" => 201, "response" => "Match créé avec succès", "data" => readById(create($jsonBody)));
    else
        $message = array("status" => 400, "response" => "Les paramètres sont invalides");

else if($_SERVER["REQUEST_METHOD"] == 'PUT')
    if (checkBody($jsonBody) && isset($jsonBody["idMatch"]))
        if (update($jsonBody))
            $message = array("status" => 200, "response" => "Match modifié avec succès", "data" => readById($jsonBody["idMatch"]));
        else
            $message = array("status" => 200, "response" => "Erreur lors de la modification du Match", "data" => readById($jsonBody["idMatch"]));
    else
        $message = array("status" => 400, "response" => "Les paramètres sont invalides");

else if($_SERVER['REQUEST_METHOD'] == 'DELETE')
    if(isset($jsonBody["idMatch"]))
        $message = array("status" => 200, "response" => "Match supprimé avec succès", "data" => delete($jsonBody["idMatch"]));
     else
        $message = array("status" => 400, "response" => "Les paramètres sont invalides");
else
    $message = array("status" => 405, "response" => "Méthode non autorisée");

http_response_code($message["status"]);
echo json_encode($message);