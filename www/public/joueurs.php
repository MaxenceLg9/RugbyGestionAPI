<?php
require_once "../libs/modele/Joueur.php";
require_once "../libs/modele/Poste.php";
require_once "../libs/modele/Statut.php";
require_once "../libs/modele/Token.php";

use function Joueur\existJoueur;
use function Joueur\readByStatut;
use function Joueur\update, Joueur\delete, Joueur\create, Joueur\readById, Joueur\read, Joueur\readBynumeroLicence, Joueur\readNonParticiperMatch, Joueur\readOnMatch, Joueur\formatJoueurs;
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
    function checkFields(mixed $jsonBody): bool {
        return isset($jsonBody["numeroLicence"]) && isset($jsonBody["nom"]) && isset($jsonBody["prenom"]) && isset($jsonBody["dateNaissance"]) &&
            isset($jsonBody["taille"]) && isset($jsonBody["poids"]) && isset($jsonBody["statut"]) && isset($jsonBody["postePrefere"]) &&
            isset($jsonBody["estPremiereLigne"]) && isset($jsonBody["commentaire"]);
    }

    function checkValues(mixed $jsonBody): bool {
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

    function checkBody(mixed $jsonBody): bool {
        return checkFields($jsonBody) && checkValues($jsonBody);
    }

    // Si la méthode de la requête est GET, récupère les joueurs
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET["idJoueur"])) {
            $message = array("status" => 200, "response" => "Joueur récupéré avec succès", "data" => readById($_GET["idJoueur"]));
        } else if (isset($_GET["numeroLicence"])) {
            $message = array("status" => 200, "response" => "Joueur récupéré avec succès", "data" => readBynumeroLicence($_GET["numeroLicence"]));
        } else if (isset($_GET["idMatch"])) {
            $message = array("status" => 200, "response" => "Liste des joueurs récupérés avec succès", "data" => array("disponibles" => readNonParticiperMatch($_GET["idMatch"]), "feuille" => readOnMatch($_GET["idMatch"])));
        } else if (isset($_GET["statut"])) {
            $message = array("status" => 200, "response" => "Liste des joueurs récupérés avec succès", "data" => readByStatut($_GET["statut"]));
        } else {
            $message = array("status" => 200, "response" => "Liste des joueurs récupérés avec succès", "data" => read());
        }
        // Si la méthode de la requête est POST, crée un joueur
    } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (checkBody($jsonBody)) {
            $message = array("status" => 201, "response" => "Joueur créé avec succès", "data" => readById(create($jsonBody)));
        } else {
            $message = array("status" => 400, "response" => "Les paramètres sont invalides", "data" => []);
        }
        // Si la méthode de la requête est PUT, modifie les infos du joueur
    } else if ($_SERVER["REQUEST_METHOD"] == 'PUT') {
        if (checkBody($jsonBody) && isset($jsonBody["idJoueur"])) {
            if (update($jsonBody)) {
                $message = array("status" => 200, "response" => "Joueur modifié avec succès", "data" => readById($jsonBody["idJoueur"]));
            } else {
                $message = array("status" => 200, "response" => "Erreur lors de la modification du joueur", "data" => readById($jsonBody["idJoueur"]));
            }
        } else {
            $message = array("status" => 400, "response" => "Les paramètres sont invalides", "data" => []);
        }
        // Si la méthode de la requête est DELETE, supprime le joueur
    } else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        if (isset($jsonBody["idJoueur"])) {
            if (existJoueur($jsonBody["idJoueur"])) {
                $message = array("status" => 200, "response" => "Joueur supprimé avec succès", "result" => delete($jsonBody["idJoueur"]));
            } else {
                $message = array("status" => 404, "response" => "Joueur non trouvé", "result" => []);
            }
        } else {
            $message = array("status" => 400, "response" => "Les paramètres sont invalides", "result" => []);
        }
    } else {
        $message = array("status" => 405, "response" => "Méthode non autorisée", "data" => []);
    }



    if (isset($message["data"]["disponibles"])) {
        foreach ($message["data"]["disponibles"] as &$joueur) {
            $joueur = formatJoueurs($joueur);
        }
        foreach ($message["data"]["feuille"] as &$joueur) {
            $joueur = formatJoueurs($joueur);
        }
    } else {
        foreach ($message["data"] as &$joueurs) {
            $joueurs = formatJoueurs($joueurs);
        }
    }
}
// Définit le code de réponse HTTP et envoie la réponse en JSON
http_response_code($message["status"]);
die(json_encode($message));