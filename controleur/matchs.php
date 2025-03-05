<?php
require_once "../modele/Session.php";
checkSession();

require_once "../modele/MatchDeRugby.php";
require_once "../modele/JouerUnMatch.php";

$matchs = MatchDeRugby::findAll();
$title = "Tous les matchs";
$css = ["style.css","view.css"];
$page = "../vue/matchs.php";
include_once "../components/page.php";
