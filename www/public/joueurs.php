<?php
require_once "../libs/modele/Joueur.php";
require_once "../libs/modele/Poste.php";
require_once "../libs/modele/Resultat.php";
require_once "../libs/modele/Statut.php";

use libs\modele\Poste;
use libs\modele\Resultat;
use libs\modele\Statut;

use function Joueur\update,Joueur\delete,Joueur\create,Joueur\readById,Joueur\read;

header("Content-Type: application/json");

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
    return isset($jsonBody["numeroLicense"]) && isset($jsonBody["nom"]) && isset($jsonBody["prenom"]) && isset($jsonBody["dateNaissance"]) &&
        isset($jsonBody["taille"]) && isset($jsonBody["poids"]) && isset($jsonBody["statut"]) && isset($jsonBody["postePrefere"]) &&
        isset($jsonBody["estPremiereLigne"]) && isset($jsonBody["commentaire"]) && isset($jsonBody["url"]);
}

function checkValues(mixed $jsonBody) : bool {
    return Poste::existFromName($jsonBody["postePrefere"]) &&
        Statut::existFrom($jsonBody["statut"]) &&
        is_numeric($jsonBody["taille"]) &&
        is_numeric($jsonBody["poids"]) &&
        is_bool($jsonBody["estPremiereLigne"]);
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
        $message = array("status" => 400, "response" => "Les paramètres sont invalides");
    }
}
else if($_SERVER["REQUEST_METHOD"] == 'PUT') {
    if(checkBody($jsonBody) && isset($jsonBody["idJoueur"])){
        if(update($jsonBody))
            $message = array("status" => 200, "response" => "Joueur modifié avec succès", "data" => readById($jsonBody["idJoueur"]));
        else
            $message = array("status" => 200, "response" => "Erreur lors de la modification du joueur", "data" => readById($jsonBody["idJoueur"]));
    } else {
        $message = array("status" => 400, "response" => "Les paramètres sont invalides");
    }
}
else if($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    if(isset($jsonBody["idJoueur"])){
        $message = array("status" => 200, "response" => "Joueur supprimé avec succès", "data" => delete($jsonBody["idJoueur"]));
    } else {
        $message = array("status" => 400, "response" => "Les paramètres sont invalides");
    }
}
else {
    $message = array("status" => 405, "response" => "Méthode non autorisée");
}

http_response_code($message["status"]);
echo json_encode($message);