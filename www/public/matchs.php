<?php
require_once $_SERVER["DOCUMENT_ROOT"]."../libs/modele/Match.php";
require_once $_SERVER["DOCUMENT_ROOT"]."../libs/modele/Resultat.php";
require_once $_SERVER["DOCUMENT_ROOT"]."../libs/modele/Lieu.php";

use function MatchDeRugby\delete, MatchDeRugby\create, MatchDeRugby\read, MatchDeRugby\readById, MatchDeRugby\update,MatchDeRugby\validerMatch,MatchDeRugby\formatMatchs;

header("Content-Type: application/json");
header('Cross-Origin-Resource-Policy: *');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

//check du jeton avec une requête POST sur l'api d'authentification
/*
if(!apiVerifyToken()){

}
*/

$jsonBody = json_decode(file_get_contents('php://input'), true);

function validateDate($date, $format = 'Y-m-d H:i') : bool {
    $d = DateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
    return $d && strtolower($d->format($format)) === strtolower($date);
}

function checkBody(mixed $jsonBody): bool {
    return isset($jsonBody["adversaire"]) && isset($jsonBody["dateHeure"]) && isset($jsonBody["lieu"]) && Lieu::existFromName($jsonBody["lieu"]) && validateDate($jsonBody["dateHeure"]);
}


if($_SERVER['REQUEST_METHOD'] == 'GET')//récupérer des matchs
    if(isset($_GET["idMatch"]))
        $message = array("status" => 200, "response" => "Match récupéré avec succès", "data" => readById($_GET["idMatch"]));
     else
        $message = array("status" => 200, "response" => "Liste des Matchs récupérés avec succès", "data" => read());

else if($_SERVER['REQUEST_METHOD'] == 'POST')//créer un match
    if(checkBody($jsonBody))
        $message = array("status" => 201, "response" => "Match créé avec succès", "data" => readById(create($jsonBody)));
    else
        $message = array("status" => 400, "response" => "Les paramètres sont invalides","data" => []);

else if($_SERVER["REQUEST_METHOD"] == 'PUT')//modifier les infos du match
    if (checkBody($jsonBody) && isset($jsonBody["idMatch"]))
        if (update($jsonBody))
            $message = array("status" => 200, "response" => "Match modifié avec succès", "data" => readById($jsonBody["idMatch"]));
        else
            $message = array("status" => 200, "response" => "Erreur lors de la modification du Match", "data" => readById($jsonBody["idMatch"]));
    else
        $message = array("status" => 400, "response" => "Les paramètres sont invalides","data" => []);

else if($_SERVER["REQUEST_METHOD"] == 'PATCH')//validation du résultat
    if (isset($jsonBody["idMatch"]) && isset($jsonBody["resultat"]) && Resultat::existFromName($jsonBody["resultat"]))
        if (validerMatch($jsonBody))
            $message = array("status" => 200, "response" => "Match modifié avec succès", "data" => readById($jsonBody["idMatch"]));
        else
            $message = array("status" => 200, "response" => "Erreur lors de la modification du Match", "data" => readById($jsonBody["idMatch"]));
    else
        $message = array("status" => 400, "response" => "Les paramètres sont invalides","data" => []);

else if($_SERVER['REQUEST_METHOD'] == 'DELETE')//suppression du match
    if(isset($jsonBody["idMatch"]))
        $message = array("status" => 200, "response" => "Match supprimé avec succès", "data" => delete($jsonBody["idMatch"]));
     else
        $message = array("status" => 400, "response" => "Les paramètres sont invalides","data" => []);
else if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    $message = array("status" => 200, "response" => "Options ok","data" => []);
}
else
    $message = array("status" => 405, "response" => "Méthode non autorisée","data" => []);
foreach ($message["data"] as &$match){
    $match = formatMatchs($match);
}
http_response_code($message["status"]);
echo json_encode($message);