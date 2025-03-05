<?php
require_once "../modele/Session.php";
checkSession();

require '../modele/MatchDeRugby.php';

$csrf_token = $_SESSION['csrf_token'];
$type = $_POST['type'] ?? $_GET['type'] ?? null;

if (!in_array($type, ['ajout', 'suppression', 'modification','vue'])) {
    die("Type de requête non défini.");
}


$idMatch = $_POST['idMatch'] ?? $_GET['idMatch'] ?? null;
if (!is_numeric($idMatch)) {
    die("ID joueur invalide.");
}

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    if (!isset($_GET['csrf_token']) || !password_verify($idMatch . $csrf_token . $type,$_GET['csrf_token'])) {
        header('Location: /matchs.php');
        die('CSRF validation failed.');
    }
    $css = ["style.css","gerer.css"];
    if($type == "ajout") {
        $title = "Ajouter un match";
        $page = '../vue/nouveaumatch.php';
        include_once '../components/page.php';
    }
    elseif ($type == "modification") {
        $title = "Modifier un match";
        $page = '../vue/modifiermatch.php';
        $match = MatchDeRugby::getFromId($_GET['idMatch']);
        if($match == null){
            header('Location: /matchs.php');
            die();
        }
        include_once '../components/page.php';
    }
    elseif($type == "vue"){
        require_once "../modele/Joueur.php";
        require_once "../modele/JouerUnMatch.php";
        $css = ["style.css", "feuille.css"];
        $title = "Consulter un match";
        $page = '../vue/vuematch.php';
        $match = MatchDeRugby::getFromId($_GET['idMatch']);
        if($match == null){
            header('Location: /matchs.php');
            die();
        }
        $archive = $match->isValider();
        $valider = JouerUnMatch::isArchiveFDM($match->getIdMatch());
        if(!$archive && !$valider){
            $joueursNP = Joueur::findAllNotOnMatch($match->getIdMatch());
        }
        $jouerLeMatch = JouerUnMatch::getJouerByMatch($match->getIdMatch());
        include_once '../components/page.php';
    }
}
elseif($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (!isset($_POST['csrf_token']) || !password_verify($idMatch . $csrf_token . $type,$_POST['csrf_token'])) {
        header('Location: /matchs.php');
        die('CSRF validation failed.');
    }

    if($type == "ajout") {
        $dateHeure = new DateTime($_POST['datetime']);
        $match = new MatchDeRugby(-1, $dateHeure, $_POST['adversaire'],  Lieu::from($_POST['lieu']),false);
        $match->saveMatchDeRugby();
        header('Location: /matchs');
        die();
    }
    if ($type == "modification"){
        // Sanitize and validate the match ID
        $match = MatchDeRugby::getFromId($_POST['idMatch']);
        if($match == null){
            header('Location: /matchs.php');
            die();
        }
        $match->setDateHeure(new DateTime($_POST['datetime']));
        $match->setAdversaire($_POST['adversaire']);
        $match->setLieu(Lieu::from($_POST['lieu']));
        $match->saveMatchDeRugby();
        header('Location: /matchs');
        die();
    }
    elseif ($type == "suppression"){
        // Sanitize and validate the match ID
        $match = MatchDeRugby::getFromId($_POST['idMatch']);
        if($match == null){
            header('Location: /matchs.php');
            die();
        }
        $match->deleteMatchDeRugby();
        header('Location: /matchs');
        die();
    }
}