<?php

require_once $_SERVER["DOCUMENT_ROOT"]."../libs/modele/FDM.php";
require_once $_SERVER["DOCUMENT_ROOT"]."../libs/modele/Match.php";
require_once $_SERVER["DOCUMENT_ROOT"]."../libs/modele/Joueur.php";
require_once $_SERVER["DOCUMENT_ROOT"]."../libs/modele/Token.php";

use function FDM\existFDM;
use function MatchDeRugby\formatMatchs;
use function MatchDeRugby\isArchiveMatch, MatchDeRugby\archiver;
use function Joueur\formatJoueurs;
use function FDM\readByNumeroAndMatch, FDM\read, FDM\readByMatch, FDM\readByJoueur, FDM\fillFDM, FDM\deleteMatch, FDM\setNotes;
use function Token\apiVerifyToken;

header('Content-Type: application/json');
header('Cross-Origin-Resource-Policy: *');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Vérifie si la méthode de la requête est OPTIONS et renvoie une réponse appropriée
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    $message = array("status" => 200, "response" => "Options ok", "data" => []);
} else {
    // Vérifie le jeton avec une requête POST sur l'API d'authentification
    apiVerifyToken();

    $jsonBody = json_decode(file_get_contents('php://input'), true);

    /**
     * @param mixed $jsonBody
     * @return bool
     */
    function checkPOSTBody(mixed $jsonBody): bool {
        if (!isset($jsonBody["idMatch"]) || !isset($jsonBody["feuilles"])) {
            return false;
        }
        foreach ($jsonBody["feuilles"] as $key => $value) {
            if (!isset($key) || !is_numeric($key) || $key < 1 || $key > 23) {
                return false;
            }
            if (!isset($value)) {
                return false;
            }
        }
        return true;
    }

    function checkPATCHBody(mixed $jsonBody): bool {
        if (!isset($jsonBody["idMatch"]) || !isset($jsonBody["feuilles"])) {
            return false;
        }
        foreach ($jsonBody["feuilles"] as $key => $value) {
            if (!isset($key) || !is_numeric($key) || $key < 1 || $key > 23) {
                return false;
            }
            if (!isset($value) || !is_numeric($value) || $value < 0 || $value > 20) {
                return false;
            }
        }
        return true;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET["idMatch"])) {
            if (isset($_GET["numero"])) {
                $message = array("status" => 200, "response" => "Feuille de Match par numéro & match récupérée avec succès", "data" => (readByNumeroAndMatch(array("idMatch" => $_GET["idMatch"], "numero" => $_GET["numero"]))));
                if (!empty($message["data"]["matchs"])) {
                    foreach ($message["data"]["matchs"][$_GET["idMatch"]]["feuilles"] as $key => &$match) {
                        $match = formatMatchs($match);
                    }
                }
            } else {
                $message = array("status" => 200, "response" => "Feuilles de Match du match récupérées avec succès", "data" => (readByMatch($_GET["idMatch"])));
                if (!empty($message["data"]["matchs"])) {
                    foreach ($message["data"]["matchs"][$_GET["idMatch"]]["feuilles"] as $key => &$match) {
                        $match = formatJoueurs($match);
                    }
                }
            }
        } else if (isset($_GET["idJoueur"])) {
            $message = array("status" => 200, "response" => "Feuilles de Match du joueur récupérées avec succès", "data" => (readByJoueur($_GET["idJoueur"])));
            if (!empty($message["data"]["matchs"])) {
                foreach ($message["data"]["matchs"] as $idMatch => &$match) {
                    foreach ($match["feuilles"] as $key => &$fdm) {
                        $fdm = formatMatchs($fdm);
                    }
                }
            }
        } else {
            $message = array("status" => 200, "response" => "Liste des Feuilles de Matchs récupérées avec succès", "data" => (read()));
        }
    } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (checkPOSTBody($jsonBody)) {
            if (isArchiveMatch($jsonBody["idMatch"])) {
                $message = array("status" => 422, "response" => "Impossible de mettre à jour la feuille de match : archivée", "data" => []);
            } else {
                $message = array("status" => 201, "response" => "Feuille de Match créé avec succès", "data" => fillFDM($jsonBody));
            }
        } else {
            $message = array("status" => 400, "response" => "Les paramètres sont invalides", "data" => []);
        }
    } else if ($_SERVER["REQUEST_METHOD"] == 'PUT') {
        if (isset($jsonBody["idMatch"]) && archiver($jsonBody["idMatch"])) {
            $message = array("status" => 200, "response" => "Feuille de match validée avec succès", "data" => readByMatch($jsonBody["idMatch"]));
        } else {
            $message = array("status" => 200, "response" => "Erreur lors de la modification de la Feuille de Match", "data" => []);
        }
    } else if ($_SERVER["REQUEST_METHOD"] == 'PATCH') {
        if (checkPATCHBody($jsonBody)) {
            if (!isArchiveMatch($jsonBody["idMatch"])) {
                $message = array("status" => 422, "response" => "Impossible de mettre des notes car la feuille de match n'est pas validée", "data" => []);
            } else {
                $message = array("status" => 200, "response" => "Feuille de Match modifié avec succès", "data" => setNotes($jsonBody["feuilles"], $jsonBody["idMatch"]));
            }
        } else {
            $message = array("status" => 400, "response" => "Les paramètres sont invalides", "data" => []);
        }
    } else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        if (isset($jsonBody["idMatch"])) {
            if(existFDM($jsonBody["idMatch"])){
                $message = array("status" => 200, "response" => "Feuille de Match supprimé avec succès", "result" => deleteMatch($jsonBody["idMatch"]));
            } else {
                $message = array("status" => 404, "response" => "Feuille de Match non trouvée", "result" => []);
            }
        } else {
            $message = array("status" => 400, "response" => "Les paramètres sont invalides", "result" => []);
        }
    } else {
        $message = array("status" => 405, "response" => "Méthode non autorisée", "data" => []);
    }
}

// Définit le code de réponse HTTP et envoie la réponse en JSON
http_response_code($message["status"]);
die(json_encode($message));