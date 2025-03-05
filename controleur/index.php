<?php
require_once "../modele/Session.php";
checkSession();


$post['nom'] = $_SESSION['nom'];

require_once "../modele/MatchDeRugby.php";
$matches = MatchDeRugby::getMatchDeRugbyWithResultat();

$title = "Accueil";
$css = ["style.css"];
$page = '../vue/index.php';
include_once '../components/page.php';