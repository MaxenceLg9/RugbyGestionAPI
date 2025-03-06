<?php
require_once "../db/DAOJoueur.php";

//check du jeton avec une requête POST sur l'api d'authentification

header("Content-Type: application/json");

$jsonBody = json_decode(file_get_contents('php://input'), true);

/**
 * @param mixed $jsonBody
 * @return bool
 */
function checkBody(mixed $jsonBody): bool
{
    return isset($jsonBody["numeroLicense"]) && isset($jsonBody["nom"]) && isset($jsonBody["prenom"]) && isset($jsonBody["dateNaissance"]) &&
        isset($jsonBody["taille"]) && isset($jsonBody["poids"]) && isset($jsonBody["statut"]) && isset($jsonBody["postePrefere"]) &&
        isset($jsonBody["estPremiereLigne"]) && isset($jsonBody["commentaire"]) && isset($jsonBody["url"]);
}

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(isset($_GET["idJoueur"])){
        $message = array("status" => 200, "response" => "Joueur récupéré avec succès", "data" => DAOJoueur::readById($_GET["idJoueur"]));
    } else {
        $message = array("status" => 200, "response" => "Liste des joueurs récupérés avec succès", "data" => DAOJoueur::read());
    }
}
else if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(checkBody($jsonBody)){
        $message = array("status" => 201, "response" => "Joueur créé avec succès", "data" => DAOJoueur::readById(DAOJoueur::create($jsonBody)));
    } else {
        $message = array("status" => 400, "response" => "Les paramètres sont invalides");
    }
}
else if($_SERVER["REQUEST_METHOD"] == 'PUT') {
    if(checkBody($jsonBody) && isset($jsonBody["idJoueur"])){
        if(DAOJoueur::update($jsonBody))
            $message = array("status" => 200, "response" => "Joueur modifié avec succès", "data" => DAOJoueur::readById($jsonBody["idJoueur"]));
        else
            $message = array("status" => 200, "response" => "Erreur lors de la modification du joueur", "data" => DAOJoueur::readById($jsonBody["idJoueur"]));
    } else {
        $message = array("status" => 400, "response" => "Les paramètres sont invalides");
    }
}
else if($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    if(isset($jsonBody["idJoueur"])){
        $message = array("status" => 200, "response" => "Joueur supprimé avec succès", "data" => DAOJoueur::delete($jsonBody["idJoueur"]));
    } else {
        $message = array("status" => 400, "response" => "Les paramètres sont invalides");
    }
}
else {
    $message = array("status" => 405, "response" => "Méthode non autorisée");
}
echo json_encode($message);