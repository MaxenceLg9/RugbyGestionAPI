<?php

require_once '../db/db.php';

class DAOMatchDeRugby {

    public function create(MatchDeRugby $match): void {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare(
                "INSERT INTO MatchDeRugby (dateHeure, adversaire, lieu, valider) 
                   VALUES (:dateHeure, :adversaire, :lieu, 0)");

            $dateHeure = $match->getDateHeure()->format('Y-m-d H:i:s');
            $adversaire = $match->getAdversaire();
            $lieu = $match->getLieu()->name;

            $statement->bindParam(':dateHeure', $dateHeure);
            $statement->bindParam(':adversaire', $adversaire);
            $statement->bindParam(':lieu', $lieu);

            $statement->execute();
            echo "Match créé avec succès\n";
        } catch (PDOException $e) {
            echo "Erreur lors de la création du match: " . $e->getMessage();
        }
    }

    public static function read(): array {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare("SELECT * FROM MatchDeRugby ORDER BY dateHeure");
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Erreur lors de la lecture des matches: " . $e->getMessage();
        }
        return [];
    }

    public static function readById(int $idMatch): array {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare("SELECT * FROM MatchDeRugby WHERE idMatch = :idMatch");
            $statement->bindParam(':idMatch', $idMatch);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Erreur lors de la lecture du match: " . $e->getMessage();
        }
        return [];
    }

    public function readByDateHeure(DateTime $dateHeure): array {
        $dateHeure = $dateHeure->format('Y-m-d H:i:s');
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare("SELECT * FROM MatchDeRugby WHERE dateHeure = :dateHeure");
            $statement->bindParam(':dateHeure', $dateHeure);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Erreur lors de la lecture du match: " . $e->getMessage();
        }
        return [];
    }

    public function update(MatchDeRugby $match): void {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare(
                "UPDATE MatchDeRugby SET dateHeure = :dateHeure, adversaire = :adversaire, lieu = :lieu
                   WHERE idMatch = :idMatch");

            $dateHeure = $match->getDateHeure()->format('Y-m-d H:i:s');
            $adversaire = $match->getAdversaire();
            $lieu = $match->getLieu()->name;
            $id = $match->getidMatch();

            $statement->bindParam(':dateHeure', $dateHeure);
            $statement->bindParam(':adversaire', $adversaire);
            $statement->bindParam(':lieu', $lieu);
            $statement->bindParam(':idMatch',$id);

            $statement->execute();
            echo "Match mis à jour avec succès\n";
        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour du match: " . $e->getMessage();
            die();
        }
    }

    public function delete(MatchDeRugby $matchDeRugby): void {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare("DELETE FROM MatchDeRugby WHERE idMatch = :idMatch");
            $id = $matchDeRugby->getIdMatch();
            $statement->bindParam(':idMatch', $id);
            $statement->execute();
            $statement = $connexion->prepare("DELETE FROM Participer WHERE idMatch = :idMatch");
            $statement->bindParam(':idMatch', $id);
            $statement->execute();
            echo "Match supprimé avec succès\n";
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression du match: " . $e->getMessage();
        }
    }

    public function readMatchWithResultat(): array
    {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare("SELECT * FROM MatchDeRugby WHERE resultat is not null ORDER BY dateHeure");
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Erreur lors de la lecture des matches: " . $e->getMessage();
        }
        return [];
    }

    public function validerMatch(MatchDeRugby $match): void
    {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare("UPDATE MatchDeRugby SET resultat = :resultat, valider := 1 WHERE idMatch = :idMatch");

            $idMatch = $match->getIdMatch();
            $resultat = $match->getResultat()->value;

            $statement->bindParam(':idMatch', $idMatch);
            $statement->bindParam(':resultat', $resultat);

            $statement->execute();
            echo "Match mis à jour avec succès\n";
        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour du match: " . $e->getMessage();
        }
    }
}