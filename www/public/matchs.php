<?php
require_once "../db/DAOMatchDeRugby.php";

header("Content-Type: application/json");

$jsonBody = json_decode(file_get_contents('php://input'), true);

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(isset($_GET["idMatch"])){
        $message = array("status" => 200, "response" => "Match récupéré avec succès", "data" => DAOMatchDeRugby::readById($_GET["idMatch"]));
    } else {
        $message = array("status" => 200, "response" => "Liste des matchs récupérés avec succès", "data" => DAOMatchDeRugby::read());
    }
}
else if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = array("status" => 405, "response" => "Méthode non implémentée");
    if(isset($jsonBody[])){

    }
}
else if($_SERVER['REQUEST_METHOD'] == 'PATCH') {

}
else {
    $message = array("status" => 405, "response" => "Méthode non autorisée");
}
echo json_encode($message);