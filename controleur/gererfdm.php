<?php

require_once "../modele/Session.php";
require_once "../modele/JouerUnMatch.php";
checkSession();


$csrf_token = $_SESSION['csrf_token'];
$type = $_POST['type'] ?? $_GET['type'] ?? null;

if (!in_array($type, ['ajout', 'modification','validation', 'notes'])) {
    die("Type de requête non défini.");
}


$idMatch = $_POST['idMatch'] ?? $_GET['idMatch'] ?? null;
if (!is_numeric($idMatch)) {
    die("ID Match invalide.");
}

/**
 * @param int $idMatch
 * @return void
 */
function editerFDM(int $idMatch, bool $archive): void
{
    $fdmExistante = JouerUnMatch::getJouerByMatch($idMatch);
    for ($i = 1; $i <= 23; $i++) {
        $idJoueur = $_POST["position-" . $i];
        $poste = $i;
        if(array_key_exists($i,$fdmExistante)){
            if($idJoueur == ""){
                echo "Suppression de la position ".$i."<br>";
                $fdmExistante[$i]->delete();
            }else{
                $joueur = Joueur::getById($idJoueur);
                $jouerUnMatch = new JouerUnMatch($idMatch, $joueur, $i < 16, $poste, -1.0, $archive);
                $jouerUnMatch->update();
            }
        }
        elseif ($idJoueur != ""){
            $joueur = Joueur::getById($idJoueur);
            $jouerUnMatch = new JouerUnMatch($idMatch, $joueur, $i < 16, $poste, -1.0, $archive);
            $jouerUnMatch->insert();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(!isset($_POST['csrf_token']) || !password_verify($idMatch . $csrf_token . $type, $_POST['csrf_token'])){
        header("Location: /matchs.php");
        die("CSRF validation failed.");
    }

    for($i = 1; $i < 23; $i++){
        echo "position-".$i." : ".$_POST["position-".$i]."<br>";
    }
    foreach ($_POST as $key => $value) {
        if ($key == "csrf_token" || str_contains($key, "position-"))
            continue;
        echo $key . " : " . $value . "<br>";
    }


    if($_POST["fdm"] === "0"){
        $match = MatchDeRugby::getFromId($idMatch);
        $match->setResultat(Resultat::from($_POST["resultat"]));
        $match->validerMatch();
    }elseif($_POST["fdm"] === "1") {
        if ($_POST["submit"] === "ajouter") {
            editerFDM($idMatch, false);
        } elseif ($_POST["submit"] === "valider") {
            if (!regle()) {
                header("Location: /gerermatch.php?type=vue&idMatch=" . $_POST["idMatch"] . "&csrf_token=" . password_hash($_POST["idMatch"] . $csrf_token . "vue", PASSWORD_BCRYPT));
                die();
            }
            editerFDM($idMatch, true);
        }
    }elseif($_POST["fdm"] === 2){
        $idJoueur = $_POST['idJoueur'];
        $note = floatval($_POST['note']);
        $playerMatch = JouerUnMatch::getJouerByMatch($idMatch);
        foreach ($playerMatch as $player) {
            if ($player->getIdMatch() == $idMatch) {
                $player->setNote($note);
                $player->save();
                break;
            }
        }

        header('Location: /playerNotes.php?success=1');
        exit;
    }
    header("Location: /gerermatch.php?type=vue&idMatch=" . $idMatch . "&csrf_token=" . password_hash($_POST["idMatch"] . $csrf_token . "vue", PASSWORD_BCRYPT));
}
elseif($_SERVER['REQUEST_METHOD'] === 'GET'){
    if(!isset($_GET['csrf_token']) || !password_verify($idMatch . $csrf_token . $type, $_GET['csrf_token'])){
        header("Location: /matchs.php");
        die("CSRF validation failed.");
    }
    if($type == "notes"){
        $css = ["style.css","gerer.css"];
        $joueurs = JouerUnMatch::getJouerByMatch($idMatch);
        $title = "Ajouter des notes";
        $page = '../vue/saisirnotes.php';
        include_once '../components/page.php';
        die();
    }
    header("Location: /gerermatch.php?type=vue&idMatch=" . $idMatch . "&csrf_token=" . password_hash($_GET["idMatch"] . $csrf_token . "vue", PASSWORD_BCRYPT));
}

function regle():bool {
    $nbJoueurs = $_POST["nbJoueurs"];
    $nbPremieresLignes = $_POST["nbPremieresLignes"];
    if($nbJoueurs < 11)
        return false;
    if($nbPremieresLignes < 3)
        return false;
    switch ($nbPremieresLignes){
        case 4:
            if($nbJoueurs > 19)
                return false;
            break;
        case 5:
            if($nbJoueurs > 22)
                return false;
            break;
    }
    return true;
}