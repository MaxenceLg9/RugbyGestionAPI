<?php

header('Content-Type: application/json');
header('Cross-Origin-Resource-Policy: *');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

die(json_encode(array("status" => 200, "response" => "Bienvenue sur la page d'accueil", "data" => [])));