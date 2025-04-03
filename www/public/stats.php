<?php

use function Joueur\readStats;

require_once "../libs/modele/Joueur.php";
require_once "../libs/modele/Poste.php";
require_once "../libs/modele/Statut.php";
require_once "../libs/modele/Match.php";


header('Content-Type: application/json');
header('Cross-Origin-Resource-Policy: *');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

//check du jeton avec une requête POST sur l'api d'authentification
/*
if(!apiVerifyToken()){

}
*/



if($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(isset($_GET["idJoueur"])) {
        $message = array("status" => 200, "response" => "Statistiques pour le Joueur", "data" => readStats($_GET["idJoueur"]));
    }
    else
        $message = array("status" => 200, "response" => "Statistiques pour l'équipe", "data" => array("joueurs" => readStats(),"matchs" => \MatchDeRugby\readStats()));
}
else if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    $message = array("status" => 200, "response" => "Options ok","data" => []);
}
else {
    $message = array("status" => 405, "response" => "Méthode non autorisée","data" => []);
}
http_response_code($message["status"]);
echo json_encode($message);