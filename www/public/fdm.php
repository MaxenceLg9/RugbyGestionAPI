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


    // Récupère le corps de la requête en JSON
    $jsonBody = json_decode(file_get_contents('php://input'), true);

    /**
     * @param mixed $jsonBody
     * @return bool
     */
    // Vérifie si les champs requis sont présents dans le corps de la requête
    function checkPOSTBody(mixed $jsonBody): bool {
        // Vérifie que les champs idMatch et feuilles sont présents
        if (!isset($jsonBody["idMatch"]) || !isset($jsonBody["feuilles"])) {
            return false;
        }
        foreach ($jsonBody["feuilles"] as $numero => $idJoueur) {
            // Vérifie que les numeros sont des nombres entre 1 et 23
            if (!isset($numero) || !is_numeric($numero) || $numero < 1 || $numero > 23) {
                return false;
            }
            // Vérifie que les idJoueur associés sont définies
            if (!isset($idJoueur)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param mixed $jsonBody
     * @return bool
     */
    // Vérifie si les champs requis sont présents dans le corps de la requête pour la méthode PATCH
    function checkPATCHBody(mixed $jsonBody): bool {
        // Vérifie que les champs idMatch et feuilles sont présents
        if (!isset($jsonBody["idMatch"]) || !isset($jsonBody["feuilles"])) {
            return false;
        }
        // Vérifie que les numeros sont des nombres entre 1 et 23
        foreach ($jsonBody["feuilles"] as $numero => $note) {
            if (!isset($numero) || !is_numeric($numero) || $numero < 1 || $numero > 23) {
                return false;
            }
            // Vérifie que les notes associées sont définies et sont des nombres entre 0 et 20
            if (!isset($note) || !is_numeric($note) || $note < 0 || $note > 20) {
                return false;
            }
        }
        return true;
    }

    // Si la méthode de la requête est GET, récupère les fdm sous plusieurs formes avec plusieurs paramètres
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        // Si l'ID du match est présent dans la requête, récupère pour un match
        if (isset($_GET["idMatch"])) {
            // Si le numéro est présent dans la requête, récupère pour un match et un numéro
            if (isset($_GET["numero"])) {
                // Récupère la fdm pour un match et un numéro
                $message = array("status" => 200, "response" => "Feuille de Match par numéro & match récupérée avec succès", "data" => (readByNumeroAndMatch(array("idMatch" => $_GET["idMatch"], "numero" => $_GET["numero"]))));
                // Si la fdm existe, formate les champs du Match
                if (!empty($message["data"]["matchs"])) {
                    //pour toutes les fdm du match $idMatch dans "feuilles"
                    foreach ($message["data"]["matchs"][$_GET["idMatch"]]["feuilles"] as $idMatch => &$match) {
                        $match = formatMatchs($match);
                    }
                }
            } else {
                // Si le numero n'est pas présent, récupère les fdm pour un match
                $message = array("status" => 200, "response" => "Feuilles de Match du match récupérées avec succès", "data" => (readByMatch($_GET["idMatch"])));
                // Si la fdm existe, formate les champs du Match
                if (!empty($message["data"]["matchs"])) {
                    //pour toutes les fdm du match $idMatch dans "feuilles"
                    foreach ($message["data"]["matchs"][$_GET["idMatch"]]["feuilles"] as $idMatch => &$match) {
                        //formattage des données du match
                        $match = formatJoueurs($match);
                    }
                }
            }
        } else if (isset($_GET["idJoueur"])) {
            // Si l'ID du joueur est présent dans la requête, récupère les fdm de match pour un joueur
            $message = array("status" => 200, "response" => "Feuilles de Match du joueur récupérées avec succès", "data" => (readByJoueur($_GET["idJoueur"])));
            // Si la fdm existe, formate les champs du Match
            if (!empty($message["data"]["matchs"])) {
                //pour tous les matchs du joueur $idJoueur dans "matchs"
                foreach ($message["data"]["matchs"] as $idMatch => &$match) {
                    //pour toutes les feuilles dans le match $idMatch dans "feuilles"
                    foreach ($match["feuilles"] as $numero => &$fdm) {
                        //formattage des données du match
                        $fdm = formatMatchs($fdm);
                    }
                }
            }
        } else {
            // Si aucun paramètre n'est présent, récupère toutes les fdm
            $message = array("status" => 200, "response" => "Liste des Feuilles de Matchs récupérées avec succès", "data" => (read()));
        }
        // Si la méthode de la requête est POST, saisie la fdm d'un match
    } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Vérifie si le corps de la requête contient les champs requis
        if (checkPOSTBody($jsonBody)) {
            // vérification que le match n'est pas archivé & donc modifiable
            if (isArchiveMatch($jsonBody["idMatch"])) {
                // Si le match est archivé, renvoie une réponse d'erreur
                $message = array("status" => 422, "response" => "Impossible de mettre à jour la feuille de match : archivée", "data" => []);
            } else {
                // Crée la fdm et renvoie la fdm créée
                $message = array("status" => 201, "response" => "Feuille de Match créée avec succès", "data" => fillFDM($jsonBody));
            }
        } else {
            // Si le corps de la requête ne contient pas les champs requis, renvoie une réponse d'erreur
            $message = array("status" => 400, "response" => "Les paramètres sont invalides", "data" => []);
        }
        // Si la méthode de la requête est PUT, permet d'archiver la fdm
    } else if ($_SERVER["REQUEST_METHOD"] == 'PUT') {
        // Vérifie si le corps de la requête contient les champs requis & que la méthode archiver a fonctionné
        if (isset($jsonBody["idMatch"]) && archiver($jsonBody["idMatch"])) {
            // Si la fdm a été archivée avec succès, renvoie la fdm archivée
            $message = array("status" => 200, "response" => "Feuille de match validée avec succès", "data" => readByMatch($jsonBody["idMatch"]));
        } else {
            // Si la fdm n'a pas pu être archivée, renvoie une réponse d'erreur
            $message = array("status" => 200, "response" => "Erreur lors de la modification de la Feuille de Match", "data" => []);
        }
        // Si la méthode de la requête est PATCH, modifie les notes de la fdm
    } else if ($_SERVER["REQUEST_METHOD"] == 'PATCH') {
        // Vérifie si le corps de la requête contient les champs requis
        if (checkPATCHBody($jsonBody)) {
            // Vérifie si le match est archivé & donc que les notes peuvent être saisies
            if (!isArchiveMatch($jsonBody["idMatch"])) {
                // Si le match n'est pas archivé, renvoie une réponse d'erreur
                $message = array("status" => 422, "response" => "Impossible de mettre des notes car la feuille de match n'est pas validée", "data" => []);
            } else {
                // Modifie les notes de la fdm et renvoie la fdm modifiée
                $message = array("status" => 200, "response" => "Feuille de Match modifiée avec succès", "data" => setNotes($jsonBody["feuilles"], $jsonBody["idMatch"]));
            }
        } else {
            // Si le corps de la requête ne contient pas les champs requis, renvoie une réponse d'erreur
            $message = array("status" => 400, "response" => "Les paramètres sont invalides", "data" => []);
        }
        // Si la méthode de la requête est DELETE, supprime la fdm d'un match seulement
    } else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        // Vérifie si l'ID du match est présent dans le corps de la requête
        if (isset($jsonBody["idMatch"])) {
            // Vérifie qu'une feuille de match existe pour le match
            if(existFDM($jsonBody["idMatch"])){
                // Renvoie un message avec un boolean indiquant si la fdm a été supprimée avec succès
                $message = array("status" => 200, "response" => "Feuille de Match supprimée avec succès", "result" => deleteMatch($jsonBody["idMatch"]));
            } else {
                // Si la fdm n'existe pas, renvoie une réponse d'erreur
                $message = array("status" => 404, "response" => "Feuille de Match non trouvée", "result" => []);
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

// Définit le code de réponse HTTP et envoie la réponse en JSON
http_response_code($message["status"]);
die(json_encode($message));