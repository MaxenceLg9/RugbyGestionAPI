<?php

require_once "../db/DAOJouerUnMatch.php";

header("Content-Type: application/json");

$jsonBody = json_decode(file_get_contents('php://input'), true);

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(isset($_GET['idMatch'])) {
        $message = array("status" => 200, "response" => "Feuille de match récupérée avec succès", "data" => DAOJouerUnMatch::readAllByMatch($_GET["idMatch"]));
    } else {
        $message = array("status" => 200, "response" => "Liste des feuilles de matchs récupérées avec succès", "data" => DAOJouerUnMatch::readAll());
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = array("status" => 405, "response" => "Méthode non implémentée");
} else {
    $message = array("status" => 405, "response" => "Méthode non autorisée");
}
echo json_encode($message);



