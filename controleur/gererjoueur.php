<?php

require_once "../modele/Session.php";
checkSession();

require_once '../modele/Joueur.php';

$csrf_token = $_SESSION['csrf_token'];
$type = $_POST['type'] ?? $_GET['type'] ?? null;

if (!in_array($type, ['ajout', 'suppression', 'modification', 'vue'])) {
    die("Type de requête non défini.");
}


$idJoueur = $_POST['idJoueur'] ?? $_GET['idJoueur'] ?? null;
if (!is_numeric($idJoueur)) {
    die("ID joueur invalide.");
}


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['csrf_token']) || !password_verify($idJoueur . $csrf_token . $type, $_GET['csrf_token'])) {
        die("CSRF validation failed.");
    }
    $css = ["style.css","gerer.css"];
    if ($type === 'ajout') {
        $title = "Ajouter un joueur";
        $page = '../vue/nouveaujoueur.php';
        include_once '../components/page.php';
    } elseif ($type === 'modification') {
        $joueur = Joueur::getById($idJoueur);
        $title = "Modifier un joueur";
        $page = '../vue/modifierjoueur.php';
        include_once '../components/page.php';
    }elseif ($type == "vue"){
        require_once "../modele/JouerUnMatch.php";
        $joueur = Joueur::getById($idJoueur);
        $fdmJoueur = JouerUnMatch::getJouerByJoueur($joueur);
        $stats['totalMatches'] = count($fdmJoueur);
        $stats['matchesWon'] = 0;
        foreach($fdmJoueur as $match){
            if(MatchDeRugby::getFromId($match->getIdMatch())->getResultat() == Resultat::VICTOIRE)
                $stats['matchesWon']++;
        }
        $winPercentage = 0;
        $stats["avgNote"] = 0;
        if($stats['totalMatches'] > 0) {
            $winPercentage = number_format($stats['matchesWon'] / $stats['totalMatches'] * 100, 2);
            $stats["avgNote"] = array_sum(array_map(function ($match) {
                    return $match->getNote();
                }, $fdmJoueur)) / count($fdmJoueur);
        }
        $title = "Consulter un joueur";
        $page = '../vue/vuejoueur.php';
        include_once '../components/page.php';
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !password_verify($idJoueur . $csrf_token . $type,$_POST['csrf_token'])) {
        die("CSRF validation failed.");
    }

    if ($type === 'ajout') {

        $joueur = new Joueur(-1, $_POST['nom'], $_POST['prenom'], new DateTime($_POST['dateNaissance']), $_POST['numeroLicense'], $_POST['taille'], $_POST['poids'], Statut::from($_POST["statut"]), Poste::tryFromName($_POST['postePrefere']), $_POST['estPremiereLigne']);
        $joueur->setCommentaire($_POST["commentaire"]);
        $joueur->saveJoueur();
        header('Location: /equipe.php');
    } elseif ($type === 'modification') {
        $joueur = Joueur::getById($idJoueur);
        $joueur->setNom($_POST['nom']);
        $joueur->setPrenom($_POST['prenom']);
        $joueur->setDateNaissance(new DateTime($_POST['dateNaissance']));
        $joueur->setNumeroLicense($_POST['numeroLicense']);
        $joueur->setTaille($_POST['taille']);
        $joueur->setPoids($_POST['poids']);
        $joueur->setStatut(Statut::from($_POST['statut']));
        $joueur->setPostePrefere(Poste::tryFromName($_POST['postePrefere']));
        $joueur->setEstPremiereLigne($_POST['estPremiereLigne']);
        $joueur->setCommentaire($_POST["commentaire"]);
        $joueur->saveJoueur();
        // Update logic here
        header('Location: /equipe.php');
    }elseif($type === 'suppression'){
        // Delete logic here
        Joueur::getById($idJoueur)->deleteJoueur();
        header('Location: /equipe.php');
    }
}