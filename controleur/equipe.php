<?php

require_once "../modele/Session.php";
checkSession();

require "../modele/Joueur.php";

$joueurs = Joueur::findAll();

$title = "Mon équipe";
$css = ["style.css","view.css"];
$page = "../vue/joueurs.php";
include_once "../components/page.php";