<?php

require_once "../libs/modele/FDM.php";

use function FDM\isArchiveFDM;
use function FDM\readByNumeroAndMatch,FDM\read,FDM\readByMatch,FDM\readByJoueur,FDM\fillFDM,FDM\archiver,FDM\deleteMatch,\FDM\setNotes;

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
function checkPOSTBody(mixed $jsonBody): bool
{
    if(!isset($jsonBody["idMatch"]) || !isset($jsonBody["feuilles"]))
        return false;
    foreach ($jsonBody["feuilles"] as $key => $value) {
        if (!isset($key) || !is_numeric($key) || $key < 1 || $key > 23)
            return false;
        if(!isset($value["idJoueur"]) || !isset($value["estTitulaire"]))
            return false;
    }
    return true;
}
function checkPATCHBody(mixed $jsonBody): bool {
    if(!isset($jsonBody["idMatch"]) || !isset($jsonBody["feuilles"]))
        return false;
    foreach ($jsonBody["feuilles"] as $key => $value) {
        if (!isset($key) || !is_numeric($key) || $key < 1 || $key > 23)
            return false;
        if(!isset($value["note"]))
            return false;
    }
    return true;
}




if($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(isset($_GET["idMatch"])){
        if(isset($_GET["numero"]))
            $message = array("status" => 200, "response" => "Feuille de Match par numéro & match récupérée avec succès", "data" => (readByNumeroAndMatch(array("idMatch" => $_GET["idMatch"],"numero" => $_GET["numero"]))));
        else
            $message = array("status" => 200, "response" => "Feuilles de Match du match récupérées avec succès", "data" => (readByMatch($_GET["idMatch"])));
    } else if(isset($_GET["idJoueur"])){
        $message = array("status" => 200, "response" => "Feuilles de Match du joueur récupérées avec succès", "data" => (readByJoueur($_GET["idJoueur"])));
    }
    else {
        $message = array("status" => 200, "response" => "Liste des Feuilles de Matchs récupérées avec succès", "data" => (read()));
    }
}
//creer, modifier, supprimer les liaisons entre un joueur et un match
else if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(checkPOSTBody($jsonBody)){
        if(isArchiveFDM($jsonBody["idMatch"]))
            $message = array("status" => 422, "response" => "Impossible de mettre à jour la feuille de match : validée", "data" => []);
        else
            $message = array("status" => 201, "response" => "Feuille de Match créé avec succès", "data" => fillFDM($jsonBody));
    } else {
        $message = array("status" => 400, "response" => "Les paramètres sont invalides");
    }
}
else if($_SERVER["REQUEST_METHOD"] == 'PUT') {
    if(isset($jsonBody["idMatch"]) && archiver($jsonBody["idMatch"]))
        $message = array("status" => 200, "response" => "Feuille de match validée avec succès", "data" => readByMatch($jsonBody["idMatch"]));
    else
        $message = array("status" => 200, "response" => "Erreur lors de la modification de la Feuille de Match", "data" => []);
}
else if($_SERVER["REQUEST_METHOD"] == 'PATCH') {
    if(checkPATCHBody($jsonBody)){
        if(!isArchiveFDM($jsonBody["idMatch"]))
            $message = array("status" => 422, "response" => "Impossible de mettre des notes car la feuille de match n'est pas validée", "data" => []);
        else
            $message = array("status" => 200, "response" => "Feuille de Match modifié avec succès", "data" => setNotes($jsonBody["feuilles"],$jsonBody["idMatch"]));
    } else {
        $message = array("status" => 400, "response" => "Les paramètres sont invalides");
    }
}
else if($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    if(isset($jsonBody["idMatch"])){
        if(deleteMatch($jsonBody["idMatch"]))
            $message = array("status" => 200, "response" => "Feuille de Match supprimé avec succès", "data" => readByMatch($jsonBody["idMatch"]));
        else
            $message = array("status" => 200, "response" => "Erreur lors de la suppression de la Feuille de Match", "data" => []);
    } else {
        $message = array("status" => 400, "response" => "Les paramètres sont invalides");
    }
}
else {
    $message = array("status" => 405, "response" => "Méthode non autorisée");
}

http_response_code($message["status"]);
echo json_encode($message);


