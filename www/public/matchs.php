<?php
require_once $_SERVER["DOCUMENT_ROOT"]."../libs/modele/Match.php";
require_once $_SERVER["DOCUMENT_ROOT"]."../libs/modele/Resultat.php";
require_once $_SERVER["DOCUMENT_ROOT"]."../libs/modele/Lieu.php";
require_once $_SERVER["DOCUMENT_ROOT"]."../libs/modele/Token.php";

use function MatchDeRugby\delete, MatchDeRugby\create, MatchDeRugby\read, MatchDeRugby\readById, MatchDeRugby\update,MatchDeRugby\validerMatch,MatchDeRugby\formatMatchs;
use function MatchDeRugby\existMatch;
use function MatchDeRugby\readMatchAVenir;
use function MatchDeRugby\readMatchWithResultat;
use function Token\apiVerifyToken;

header('Content-Type: application/json');
header('Cross-Origin-Resource-Policy: *');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');


// Vérifie si la méthode de la requête est OPTIONS et renvoie une réponse appropriée
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    $message = array("status" => 200, "response" => "Options ok","data" => []);
} else {
    // Vérifie le jeton avec une requête POST sur l'API d'authentification
    apiVerifyToken();

    // Récupère le corps de la requête en JSON
    $jsonBody = json_decode(file_get_contents('php://input'), true);

    // Fonction pour valider la date
    function validateDate($date, $format = 'Y-m-d H:i') : bool {
        $d = DateTime::createFromFormat($format, $date);
        // Le Y (année à 4 chiffres) retourne TRUE pour tout entier avec n'importe quel nombre de chiffres, donc changer la comparaison de == à === corrige le problème.
        return $d && strtolower($d->format($format)) === strtolower($date);
    }

    // Fonction pour vérifier le corps de la requête
    function checkBody(mixed $jsonBody): bool {
        return isset($jsonBody["adversaire"]) && isset($jsonBody["dateHeure"]) && isset($jsonBody["lieu"]) && Lieu::existFromName($jsonBody["lieu"]) && validateDate($jsonBody["dateHeure"]);
    }

    // Si la méthode de la requête est GET, récupère les matchs
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET["idMatch"])) {
            if(existMatch($_GET["idMatch"])){
                $message = array("status" => 200, "response" => "Match récupéré avec succès", "data" => readById($_GET["idMatch"]));
            } else {
                $message = array("status" => 404, "response" => "Match non trouvé", "data" => []);
            }
        } else if (isset($_GET["limit"]) && is_numeric($_GET["limit"])) {
            $message = array("status" => 200, "response" => "Match récupéré avec succès", "data" => array("resultats" => readMatchWithResultat($_GET["limit"]), "avenir" => readMatchAVenir($_GET["limit"])));
        } else {
            $message = array("status" => 200, "response" => "Liste des Matchs récupérés avec succès", "data" => read());
        }
        // Si la méthode de la requête est POST, crée un match
    } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (checkBody($jsonBody)) {
            $message = array("status" => 201, "response" => "Match créé avec succès", "data" => readById(create($jsonBody)));
        } else {
            $message = array("status" => 400, "response" => "Les paramètres sont invalides", "data" => []);
        }
        // Si la méthode de la requête est PUT, modifie les infos du match
    } else if ($_SERVER["REQUEST_METHOD"] == 'PUT') {
        if (checkBody($jsonBody) && isset($jsonBody["idMatch"])) {
            if (update($jsonBody)) {
                $message = array("status" => 200, "response" => "Match modifié avec succès", "data" => readById($jsonBody["idMatch"]));
            } else {
                $message = array("status" => 200, "response" => "Erreur lors de la modification du Match", "data" => readById($jsonBody["idMatch"]));
            }
        } else {
            $message = array("status" => 400, "response" => "Les paramètres sont invalides", "data" => []);
        }
        // Si la méthode de la requête est PATCH, valide le résultat
    } else if ($_SERVER["REQUEST_METHOD"] == 'PATCH') {
        if (isset($jsonBody["idMatch"]) && isset($jsonBody["resultat"]) && Resultat::existFromName($jsonBody["resultat"])) {
            if (validerMatch($jsonBody)) {
                $message = array("status" => 200, "response" => "Match modifié avec succès", "data" => readById($jsonBody["idMatch"]));
            } else {
                $message = array("status" => 200, "response" => "Erreur lors de la modification du Match", "data" => readById($jsonBody["idMatch"]));
            }
        } else {
            $message = array("status" => 400, "response" => "Les paramètres sont invalides", "data" => []);
        }
        // Si la méthode de la requête est DELETE, supprime le match
    } else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        if (isset($jsonBody["idMatch"])) {
            if(existMatch($jsonBody["idMatch"])){
                $message = array("status" => 200, "response" => "Match supprimé avec succès", "result" => delete($jsonBody["idMatch"]));
            } else {
                $message = array("status" => 404, "response" => "Joueur non trouvé", "result" => []);
            }
        } else {
            $message = array("status" => 400, "response" => "Les paramètres sont invalides", "result" => []);
        }
    } else {
        $message = array("status" => 405, "response" => "Méthode non autorisée", "data" => []);
    }
}
if(isset($message["data"]["resultats"])){
    foreach ($message["data"]["resultats"] as &$match) {
        $match = formatMatchs($match);
    }
    foreach ($message["data"]["avenir"] as &$match) {
        $match = formatMatchs($match);
    }
}else {
// Formate les matchs avant de les envoyer en réponse
    foreach ($message["data"] as &$match) {
        $match = formatMatchs($match);
    }
}

// Définit le code de réponse HTTP et envoie la réponse en JSON
http_response_code($message["status"]);
die(json_encode($message));