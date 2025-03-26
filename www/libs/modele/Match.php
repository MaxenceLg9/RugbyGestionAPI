<?php

namespace MatchDeRugby {

    require_once "{$_SERVER["DOCUMENT_ROOT"]}/../libs/db/db.php";

    use DateTime;
    use Exception;
    use PDO;
    use PDOException;

    function create(array $match): string {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare(
                "INSERT INTO MatchDeRugby (dateHeure, adversaire, lieu, valider) 
                   VALUES (:dateHeure, :adversaire, :lieu, 0)");


            $statement->bindParam(':dateHeure', $match["dateHeure"]);
            $statement->bindParam(':adversaire', $match["adversaire"]);
            $statement->bindParam(':lieu', $match["lieu"]);

            $statement->execute();

            return $connexion->lastInsertId();
        } catch (PDOException $e) {
            echo "Erreur lors de la création du match: " . $e->getMessage();
            return "";
        }
    }

    function read(): array {
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

    function readById(string $idMatch): array {
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

    function readByDateHeure(DateTime $dateHeure): array {
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

    function update(array $match): bool {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare(
                "UPDATE MatchDeRugby SET dateHeure = :dateHeure, adversaire = :adversaire, lieu = :lieu
                   WHERE idMatch = :idMatch");


            $statement->bindParam(':dateHeure', $match["dateHeure"]);
            $statement->bindParam(':adversaire', $match["adversaire"]);
            $statement->bindParam(':lieu', $match["lieu"]);
            $statement->bindParam(':idMatch',$match["idMatch"]);

            return $statement->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour du match: " . $e->getMessage();
            return false;
        }
    }

    function delete(int $idMatch): bool {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare("DELETE FROM MatchDeRugby WHERE idMatch = :idMatch");
            $statement->bindParam(':idMatch', $idMatch);

            return $statement->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression du match: " . $e->getMessage();
            return false;
        }
    }

    function readMatchWithResultat(): array
    {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare("SELECT * FROM MatchDeRugby WHERE resultat is not null ORDER BY dateHeure");
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur lors de la lecture des matches: " . $e->getMessage();
        }
        return [];
    }

    function validerMatch(array $match): bool
    {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare("UPDATE MatchDeRugby SET resultat = :resultat, valider := 1 WHERE idMatch = :idMatch");


            $statement->bindParam(':idMatch', $match["idMatch"]);
            $statement->bindParam(':resultat', $match["resultat"]);

            return $statement->execute();
            echo "Match mis à jour avec succès\n";
        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour du match: " . $e->getMessage();
            return false;
        }
    }
}