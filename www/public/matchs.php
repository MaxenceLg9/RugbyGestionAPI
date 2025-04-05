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

    // Fonction pour vérifier le format de la date
    function validateDate($date, $format = 'Y-m-d H:i') : bool {
        $d = DateTime::createFromFormat($format, $date);
        // Le Y (année à 4 chiffres) retourne TRUE pour tout entier avec n'importe quel nombre de chiffres, donc changer la comparaison de == à === corrige le problème.
        return $d && strtolower($d->format($format)) === strtolower($date);
    }

    // Fonction pour vérifier le corps de la requête
    function checkBody(mixed $jsonBody): bool {
        // Vérifie si le corps de la requête contient l'adversaire, la date et le lieu
        return isset($jsonBody["adversaire"]) && isset($jsonBody["dateHeure"]) && isset($jsonBody["lieu"]) && Lieu::existFromName($jsonBody["lieu"]) && validateDate($jsonBody["dateHeure"]);
    }

    // Si la méthode de la requête est GET, récupère les matchs
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        // Si l'ID du match est présent dans la requête, récupère le match correspondant
        if (isset($_GET["idMatch"])) {
            // Vérifie si le match existe dans la BD
            if(existMatch($_GET["idMatch"])){
                // Récupère le match et renvoie une réponse appropriée
                $message = array("status" => 200, "response" => "Match récupéré avec succès", "data" => readById($_GET["idMatch"]));
            } else {
                // Si le match n'existe pas, renvoie une réponse d'erreur
                $message = array("status" => 404, "response" => "Match non trouvé", "data" => []);
            }
        // Sinon si le paramètre limit est présent et est un nombre alors renvoie les matchs ayant un résultant et ceux à venir
        } else if (isset($_GET["limit"]) && is_numeric($_GET["limit"])) {
            $message = array("status" => 200, "response" => "Match récupéré avec succès", "data" => array("resultats" => readMatchWithResultat($_GET["limit"]), "avenir" => readMatchAVenir($_GET["limit"])));
        } else {
            // Sinon renvoie tous les matchs : default
            $message = array("status" => 200, "response" => "Liste des Matchs récupérés avec succès", "data" => read());
        }
        // Si la méthode de la requête est POST, crée un match
    } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Vérifie si le corps de la requête contient les informations nécessaires
        if (checkBody($jsonBody)) {
            // Crée le match et renvoie le match créé
            $message = array("status" => 201, "response" => "Match créé avec succès", "data" => readById(create($jsonBody)));
        } else {
            // Si le corps de la requête ne contient pas les informations nécessaires, renvoie une réponse d'erreur
            $message = array("status" => 400, "response" => "Les paramètres sont invalides", "data" => []);
        }
        // Si la méthode de la requête est PUT, modifie les infos du match
    } else if ($_SERVER["REQUEST_METHOD"] == 'PUT') {
        // Vérifie si le corps de la requête contient les informations nécessaires
        if (checkBody($jsonBody) && isset($jsonBody["idMatch"])) {
            // Vérifie si le match existe dans la BD
            if (update($jsonBody)) {
                // Si le match a été modifié avec succès, renvoie le match modifié
                $message = array("status" => 200, "response" => "Match modifié avec succès", "data" => readById($jsonBody["idMatch"]));
            } else {
                // Si le match n'a pas pu être modifié, renvoie une réponse d'erreur
                $message = array("status" => 200, "response" => "Erreur lors de la modification du Match", "data" => readById($jsonBody["idMatch"]));
            }
        } else {
            // Si le corps de la requête ne contient pas les informations nécessaires, renvoie une réponse d'erreur
            $message = array("status" => 400, "response" => "Les paramètres sont invalides", "data" => []);
        }
        // Si la méthode de la requête est PATCH, valide le résultat
    } else if ($_SERVER["REQUEST_METHOD"] == 'PATCH') {
        // Vérifie si le corps de la requête contient les informations nécessaires : idMatch et resultat et que le resultat est valide
        if (isset($jsonBody["idMatch"]) && isset($jsonBody["resultat"]) && Resultat::existFromName($jsonBody["resultat"])) {
            // Valide le match et renvoie le match modifié
            if (validerMatch($jsonBody)) {
                // Si le match a été validé avec succès, renvoie le match modifié
                $message = array("status" => 200, "response" => "Match modifié avec succès", "data" => readById($jsonBody["idMatch"]));
            } else {
                // Si le match n'a pas pu être validé, renvoie le match quand même avec une erreur
                $message = array("status" => 400, "response" => "Erreur lors de la modification du Match", "data" => readById($jsonBody["idMatch"]));
            }
        } else {
            // Si le corps de la requête ne contient pas les informations nécessaires, renvoie une réponse d'erreur
            $message = array("status" => 400, "response" => "Les paramètres sont invalides", "data" => []);
        }
        // Si la méthode de la requête est DELETE, supprime le match
    } else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        // Vérifie si l'ID du match est présent dans le corps de la requête
        if (isset($jsonBody["idMatch"])) {
            // Vérifie si le match existe dans la BD
            if(existMatch($jsonBody["idMatch"])){
                // Renvoie un message avec un boolean indiquant si le match a été supprimé avec succès
                $message = array("status" => 200, "response" => "Match supprimé avec succès", "result" => delete($jsonBody["idMatch"]));
            } else {
                // Si le match n'existe pas, renvoie une réponse d'erreur
                $message = array("status" => 404, "response" => "Joueur non trouvé", "result" => []);
            }
        } else {
            // Si l'ID du match n'est pas présent, renvoie une réponse d'erreur
            $message = array("status" => 400, "response" => "Les paramètres sont invalides", "result" => []);
        }
    } else {
        // Si la méthode de la requête n'est pas autorisée, renvoie une réponse d'erreur
        $message = array("status" => 405, "response" => "Méthode non autorisée", "data" => []);
    }
}
// Si le message contient des matchs, formate les matchs avant de les envoyer en réponse

// Si les matchs sont dans la clé resultats / avenir
if(isset($message["data"]["resultats"])){
    foreach ($message["data"]["resultats"] as &$match) {
        $match = formatMatchs($match);
    }
    foreach ($message["data"]["avenir"] as &$match) {
        $match = formatMatchs($match);
    }
}else {
    // Si les matchs sont dans la clé data
    foreach ($message["data"] as &$match) {
        $match = formatMatchs($match);
    }
}

// Définit le code de réponse HTTP et envoie la réponse en JSON
http_response_code($message["status"]);
die(json_encode($message));