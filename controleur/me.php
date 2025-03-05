<?php
require_once "../modele/Session.php";
checkSession();

$title = "Mon compte";
$css = ["style.css"];
$page = "../vue/me.php";
include_once "../components/page.php";