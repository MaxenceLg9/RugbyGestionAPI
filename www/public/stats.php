<?php

use function Joueur\statsJoueurs,Joueur\existJoueur,Joueur\readStatsIndiv;
use function MatchDeRugby\readStats;
use function Token\apiVerifyToken;

require_once "../libs/modele/Joueur.php";
require_once "../libs/modele/Poste.php";
require_once "../libs/modele/Statut.php";
require_once "../libs/modele/Match.php";
require_once "../libs/modele/Token.php";


header('Content-Type: application/json');
header('Cross-Origin-Resource-Policy: *');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

//check du jeton avec une requête POST sur l'api d'authentification
apiVerifyToken();


//récupération des statistiques
if($_SERVER['REQUEST_METHOD'] == 'GET') {
    //si joueur défini
    if(isset($_GET["idJoueur"])) {
        //on regarde si le joueur existe
        if(existJoueur($_GET["idJoueur"]))
            $message = array("status" => 200, "response" => "Statistiques pour le Joueur", "data" => readStatsIndiv($_GET["idJoueur"]));
        else // sinon erreur not found
            $message = array("status" => 404, "response" => "Joueur non trouvé", "data" => []);
    }
    else//route par défaut sans paramètre : renvoi les stats de l'équipe
        $message = array("status" => 200, "response" => "Statistiques pour l'équipe", "data" => array("joueurs" => readStatsIndiv(),"matchs" => readStats(),"stats" => statsJoueurs()));
}
//entête cors
else if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    $message = array("status" => 200, "response" => "Options ok","data" => []);
}
else {
    //autre : requête non autorisée
    $message = array("status" => 405, "response" => "Méthode non autorisée","data" => []);
}
http_response_code($message["status"]);
echo json_encode($message);