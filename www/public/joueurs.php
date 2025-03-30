<?php
require_once "../libs/modele/Joueur.php";
require_once "../libs/modele/Poste.php";
require_once "../libs/modele/Resultat.php";
require_once "../libs/modele/Statut.php";


use function Joueur\update,Joueur\delete,Joueur\create,Joueur\readById,Joueur\read;

header('Content-Type: application/json; charset=utf-8');
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

/**
 * @param mixed $jsonBody
 * @return bool
 */

function checkFields(mixed $jsonBody) : bool{
    return isset($jsonBody["numeroLicence"]) && isset($jsonBody["nom"]) && isset($jsonBody["prenom"]) && isset($jsonBody["dateNaissance"]) &&
        isset($jsonBody["taille"]) && isset($jsonBody["poids"]) && isset($jsonBody["statut"]) && isset($jsonBody["postePrefere"]) &&
        isset($jsonBody["estPremiereLigne"]) && isset($jsonBody["commentaire"]);
}

function checkValues(mixed $jsonBody) : bool {
    return Poste::existFromName($jsonBody["postePrefere"]) &&
        Statut::existFromName($jsonBody["statut"]) &&
        is_numeric($jsonBody["taille"]) &&
        is_numeric($jsonBody["poids"]) && ($jsonBody["estPremiereLigne"] === 0 || $jsonBody["estPremiereLigne"] === 1) &&
        checkDateNaissance($jsonBody["dateNaissance"]);
}

function checkDateNaissance(string $date): bool {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && strtolower($d->format('Y-m-d')) === strtolower($date);
}

function checkBody(mixed $jsonBody): bool
{
    return checkFields($jsonBody) && checkValues($jsonBody);
}



if($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(isset($_GET["idJoueur"])){
        $message = array("status" => 200, "response" => "Joueur récupéré avec succès", "data" => readById($_GET["idJoueur"]));
    } else {
        $message = array("status" => 200, "response" => "Liste des joueurs récupérés avec succès", "data" => read());
    }
}
else if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(checkBody($jsonBody)){
        $message = array("status" => 201, "response" => "Joueur créé avec succès", "data" => readById(create($jsonBody)));
    } else {
        $message = array("status" => 400, "response" => "Les paramètres sont invalides", "data" => []);
    }
}
else if($_SERVER["REQUEST_METHOD"] == 'PUT') {
    if(checkBody($jsonBody) && isset($jsonBody["idJoueur"])){
        if(update($jsonBody))
            $message = array("status" => 200, "response" => "Joueur modifié avec succès", "data" => readById($jsonBody["idJoueur"]));
        else
            $message = array("status" => 200, "response" => "Erreur lors de la modification du joueur", "data" => readById($jsonBody["idJoueur"]));
    } else {
        $message = array("status" => 400, "response" => "Les paramètres sont invalides","data" => []);
    }
}
else if($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    if(isset($jsonBody["idJoueur"])){
        if(delete($jsonBody["idJoueur"]))
            $message = array("status" => 200, "response" => "Joueur supprimé avec succès", "data" => []);
        else
            $message = array("status" => 200, "response" => "Erreur lors de la suppression du joueur", "data" => []);
    } else {
        $message = array("status" => 400, "response" => "Les paramètres sont invalides", "data" => []);
    }
}
else if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    $message = array("status" => 200, "response" => "Options ok","data" => []);
}
else {
    $message = array("status" => 405, "response" => "Méthode non autorisée","data" => []);
}
//var_dump($message["data"]);
http_response_code($message["status"]);

foreach ($message["data"] as &$joueurs){
    $url = $_SERVER["DOCUMENT_ROOT"]."/img/joueurs/".$joueurs["url"];
    if(!file_exists($url))
        $joueurs["url"] = "http://rugbygestion.api/img/data/default.png";
    else
        $joueurs["url"] = "http://rugbygestion.api/img/joueurs/".$joueurs["url"];
    $joueurs["postePrefere"] = Poste::fromName($joueurs["postePrefere"])->value;
    $joueurs["statut"] = Statut::fromName($joueurs["statut"])->value;
    $date = DateTime::createFromFormat('Y-m-d', $joueurs["dateNaissance"]);
    $joueurs["dateNaissance"] = $date->format('d-m-Y');
    $joueurs["estPremiereLigne"] = ($joueurs["estPremiereLigne"] == 0) ? "Non" : "Oui";
}
echo json_encode($message);