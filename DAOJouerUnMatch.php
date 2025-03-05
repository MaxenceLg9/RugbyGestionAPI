<?php

require_once '../db/Connexion.php';
require_once '../modele/JouerUnMatch.php';
require_once '../modele/MatchDeRugby.php';
require_once '../modele/Joueur.php';

class DAOJouerUnMatch {

    public static function create(JouerUnMatch $jouer): void {
        try {
            $connexion = Connexion::getInstance()->getConnection();
            $requete = $connexion -> prepare(
                "INSERT INTO Participer (idMatch, idJoueur, estTitulaire, numero, note, archive) 
             VALUES (:idMatch, :idJoueur, :estTitulaire, :numero, :note, :archive)");


            $estTitulaire = $jouer -> isTitulaire();
            $numero = $jouer -> getNumero();
            $note = $jouer -> getNote();
            $idMatch = $jouer -> getIdMatch();
            $archive = $jouer->isArchive();
            $idJoueur = $jouer->getJoueur()->getIdJoueur();

            $requete->bindParam(':estTitulaire', $estTitulaire);
            $requete->bindParam(':numero', $numero);
            $requete->bindParam(':note', $note);
            $requete->bindParam(':idJoueur', $idJoueur);
            $requete->bindParam(':idMatch', $idMatch);
            $requete->bindParam(':archive', $archive);

            $requete -> execute();
            echo "Feuille de match créée avec succès !";
        } catch (PDOException $e) {
            echo "Erreur : " . $e -> getMessage();
        }
    }

    public static function existJoueur(JouerUnMatch $jouer): bool {
        try {
            $connexion = Connexion::getInstance()->getConnection();
            $requete = $connexion->prepare(
                "SELECT * FROM Participer WHERE idMatch = :idMatch AND numero = :numero"
            );

            // Get the necessary values from the $jouer object
            $idMatch = $jouer->getIdMatch();
            $numero = $jouer->getNumero();

            // Bind parameters
            $requete->bindParam(':idMatch', $idMatch, PDO::PARAM_INT);
            $requete->bindParam(':numero', $numero, PDO::PARAM_INT);

            // Execute query
            $requete->execute();

            // Fetch the result
            $row = $requete->fetch(PDO::FETCH_ASSOC);

            // Return true if a row exists, false otherwise
            return $row !== false;

        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            return false; // Default to false in case of an exception
        }
    }

    public static function readAll(): array {
        $listJouer = array();
        try {
            $connexion = Connexion::getInstance()->getConnection();
            $requete = $connexion -> prepare("SELECT * FROM Participer");
            $requete -> execute();
            $rows = $requete -> fetchAll();

            foreach ($rows as $row) {
                $listJouer[] = new JouerUnMatch($row["idMatch"],Joueur::getById($row["idJoueur"]), $row["estTitulaire"], $row["numero"], $row["note"], $row["archive"]);
            }
        } catch (PDOException $e) {
            echo "Erreur : " . $e -> getMessage();
        }
        return $listJouer;
    }

    public static function update(JouerUnMatch $jouer): void {
        try {
            $connexion = Connexion::getInstance()->getConnection();
            $requete = $connexion -> prepare(
                "UPDATE Participer SET estTitulaire = :estTitulaire, idJoueur = :idJoueur, note = :note, archive = :archive
             WHERE idMatch = :idMatch AND numero = :numero");

            $estTitulaire = $jouer -> isTitulaire();
            $numero = $jouer -> getNumero();
            $note = $jouer -> getNote();
            $idJoueur = $jouer -> getJoueur() -> getIdJoueur();
            $idMatch = $jouer -> getIdMatch();
            $archive = $jouer->isArchive();

            $requete->bindParam(':estTitulaire', $estTitulaire);
            $requete->bindParam(':numero', $numero);
            $requete->bindParam(':note', $note);
            $requete->bindParam(':idMatch', $idMatch);
            $requete->bindParam(':idJoueur', $idJoueur);
            $requete->bindParam(':archive', $archive);

            $requete -> execute();
            echo "Feuille de match mise à jour avec succès !";
        } catch (PDOException $e) {
            echo "Erreur : " . $e -> getMessage();
        }
    }

    public static function delete(JouerUnMatch $jouer): void {
        try {
            $connexion = Connexion::getInstance()->getConnection();
            $requete = $connexion -> prepare(
                "DELETE FROM Participer WHERE idMatch = :idMatch AND idJoueur = :idJoueur");

            $idJoueur = $jouer -> getJoueur()->getIdJoueur();
            $idMatch = $jouer -> getIdMatch();
            $requete -> bindParam(':idMatch', $idMatch);
            $requete -> bindParam(':idJoueur', $idJoueur);

            $requete -> execute();
            echo "Feuille de match supprimée avec succès !";
        } catch (PDOException $e) {
            echo "Erreur : " . $e -> getMessage();
        }
    }

    public static function readAllByMatch(int  $idMatch): array {
        $listJouer = array();
        try {
            $connexion = Connexion::getInstance()->getConnection();
            $requete = $connexion -> prepare(
                "SELECT * FROM Participer WHERE idMatch = :idMatch ORDER BY numero");

            $requete -> bindParam(':idMatch', $idMatch);

            $requete -> execute();
            $rows = $requete -> fetchAll();
            foreach ($rows as $row) {
                $listJouer[$row["numero"]] = new JouerUnMatch($idMatch,Joueur::getById($row["idJoueur"]), $row["estTitulaire"], $row["numero"], $row["note"], $row["archive"]);
            }
        } catch (PDOException $e) {
            echo "Erreur : " . $e -> getMessage();
        }
        return $listJouer;
    }

    public static function readAllByJoueur(Joueur $joueur): array {
        $listJouers = array();
        try {
            $connexion = Connexion::getInstance()->getConnection();
            $requete = $connexion -> prepare(
                "SELECT * FROM Participer JOIN MatchDeRugby ON Participer.idMatch = MatchDeRugby.idMatch WHERE idJoueur = :idJoueur AND archive = 1 AND Resultat is not null");

            $idJoueur = $joueur -> getIdJoueur();
            $requete -> bindParam(':idJoueur', $idJoueur);

            $requete -> execute();
            $rows = $requete -> fetchAll();

            foreach ($rows as $row) {
                $listJouers[] = new JouerUnMatch($row['idMatch'], $joueur,$row['estTitulaire'], $row['numero'], $row['note'], $row["archive"]);
            }
        } catch (PDOException $e) {
            echo "Erreur : " . $e -> getMessage();
        }
        return $listJouers;
    }

    public static function isArchiveFDM(int $idMatch): bool
    {
        try {
            $connexion = Connexion::getInstance()->getConnection();
            $requete = $connexion -> prepare(
                "SELECT archive FROM Participer WHERE idMatch = :idMatch");

            $requete -> bindParam(':idMatch', $idMatch);

            $requete -> execute();
            $row = $requete -> fetch(PDO::FETCH_ASSOC);
            return $row ? $row['archive'] : false;
        } catch (PDOException $e) {
            echo "Erreur : " . $e -> getMessage();
        }
        return false;
    }

}