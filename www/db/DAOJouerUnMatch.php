<?php

require_once '../db/db.php';

class DAOJouerUnMatch {

    public static function create(JouerUnMatch $jouer): void {
        try {
            $connexion = getPDO();
            $statement = $connexion -> prepare(
                "INSERT INTO Participer (idMatch, idJoueur, estTitulaire, numero, note, archive) 
             VALUES (:idMatch, :idJoueur, :estTitulaire, :numero, :note, :archive)");


            $estTitulaire = $jouer -> isTitulaire();
            $numero = $jouer -> getNumero();
            $note = $jouer -> getNote();
            $idMatch = $jouer -> getIdMatch();
            $archive = $jouer->isArchive();
            $idJoueur = $jouer->getJoueur()->getIdJoueur();

            $statement->bindParam(':estTitulaire', $estTitulaire);
            $statement->bindParam(':numero', $numero);
            $statement->bindParam(':note', $note);
            $statement->bindParam(':idJoueur', $idJoueur);
            $statement->bindParam(':idMatch', $idMatch);
            $statement->bindParam(':archive', $archive);

            $statement -> execute();
            echo "Feuille de match créée avec succès !";
        } catch (PDOException $e) {
            echo "Erreur : " . $e -> getMessage();
        }
    }

    public static function existJoueur(JouerUnMatch $jouer): bool {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare(
                "SELECT * FROM Participer WHERE idMatch = :idMatch AND numero = :numero"
            );

            // Get the necessary values from the $jouer object
            $idMatch = $jouer->getIdMatch();
            $numero = $jouer->getNumero();

            // Bind parameters
            $statement->bindParam(':idMatch', $idMatch, PDO::PARAM_INT);
            $statement->bindParam(':numero', $numero, PDO::PARAM_INT);

            // Execute query
            $statement->execute();

            // Fetch the result
            $row = $statement->fetch(PDO::FETCH_ASSOC);

            // Return true if a row exists, false otherwise
            return $row !== false;

        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            return false; // Default to false in case of an exception
        }
    }

    public static function readAll(): array {
        try {
            $connexion = getPDO();
            $statement = $connexion -> prepare("SELECT * FROM Participer");
            $statement -> execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur : " . $e -> getMessage();
        }
        return [];
    }

    public static function update(JouerUnMatch $jouer): void {
        try {
            $connexion = getPDO();
            $statement = $connexion -> prepare(
                "UPDATE Participer SET estTitulaire = :estTitulaire, idJoueur = :idJoueur, note = :note, archive = :archive
             WHERE idMatch = :idMatch AND numero = :numero");

            $estTitulaire = $jouer -> isTitulaire();
            $numero = $jouer -> getNumero();
            $note = $jouer -> getNote();
            $idJoueur = $jouer -> getJoueur() -> getIdJoueur();
            $idMatch = $jouer -> getIdMatch();
            $archive = $jouer->isArchive();

            $statement->bindParam(':estTitulaire', $estTitulaire);
            $statement->bindParam(':numero', $numero);
            $statement->bindParam(':note', $note);
            $statement->bindParam(':idMatch', $idMatch);
            $statement->bindParam(':idJoueur', $idJoueur);
            $statement->bindParam(':archive', $archive);

            $statement -> execute();
            echo "Feuille de match mise à jour avec succès !";
        } catch (PDOException $e) {
            echo "Erreur : " . $e -> getMessage();
        }
    }

    public static function delete(JouerUnMatch $jouer): void {
        try {
            $connexion = getPDO();
            $statement = $connexion -> prepare(
                "DELETE FROM Participer WHERE idMatch = :idMatch AND idJoueur = :idJoueur");

            $idJoueur = $jouer -> getJoueur()->getIdJoueur();
            $idMatch = $jouer -> getIdMatch();
            $statement -> bindParam(':idMatch', $idMatch);
            $statement -> bindParam(':idJoueur', $idJoueur);

            $statement -> execute();
            echo "Feuille de match supprimée avec succès !";
        } catch (PDOException $e) {
            echo "Erreur : " . $e -> getMessage();
        }
    }

    public static function readAllByMatch(int  $idMatch): array {
        try {
            $connexion = getPDO();
            $statement = $connexion -> prepare(
                "SELECT * FROM Participer WHERE idMatch = :idMatch ORDER BY numero");

            $statement -> bindParam(':idMatch', $idMatch);

            $statement -> execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur : " . $e -> getMessage();
        }
        return [];
    }

    public static function readAllByJoueur(Joueur $joueur): array {
        try {
            $connexion = getPDO();
            $statement = $connexion -> prepare(
                "SELECT * FROM Participer JOIN MatchDeRugby ON Participer.idMatch = MatchDeRugby.idMatch WHERE idJoueur = :idJoueur AND archive = 1 AND Resultat is not null");

            $idJoueur = $joueur -> getIdJoueur();
            $statement -> bindParam(':idJoueur', $idJoueur);

            $statement -> execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur : " . $e -> getMessage();
        }
        return [];
    }

    public static function isArchiveFDM(int $idMatch): bool
    {
        try {
            $connexion = getPDO();
            $statement = $connexion -> prepare(
                "SELECT archive FROM Participer WHERE idMatch = :idMatch");

            $statement -> bindParam(':idMatch', $idMatch);

            $statement -> execute();
            $row = $statement -> fetch(PDO::FETCH_ASSOC);
            return $row ? $row['archive'] : false;
        } catch (PDOException $e) {
            echo "Erreur : " . $e -> getMessage();
        }
        return false;
    }

}