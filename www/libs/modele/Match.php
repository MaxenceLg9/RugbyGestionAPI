<?php

namespace MatchDeRugby {

    require_once "{$_SERVER["DOCUMENT_ROOT"]}/../libs/db/db.php";
    require_once "{$_SERVER["DOCUMENT_ROOT"]}/../libs/modele/Resultat.php";
    require_once "{$_SERVER["DOCUMENT_ROOT"]}/../libs/modele/Lieu.php";

    use DateTime;
    use Exception;
    use PDO;
    use PDOException;

    function formatMatchs(array $match): array
    {
        if($match["resultat"] !== null)
            $match["resultat"] = \Resultat::fromName($match["resultat"])->value;
        $match["lieu"] = \Lieu::fromName($match["lieu"])->value;
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $match["dateHeure"]);
        $match["dateHeure"] = $date->format('d/m/Y à H:i');
        return $match;
    }

    function existMatch(string $idMatch) : bool {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare("SELECT COUNT(*) FROM MatchDeRugby WHERE idMatch = :idMatch");
            $statement->bindParam(':idMatch', $idMatch);
            $statement->execute();
            return $statement->fetchColumn() > 0;
        } catch (PDOException $e) {
            echo "Erreur lors de la vérification de l'existence du match: " . $e->getMessage();
            return false;
        }
    }

    function create(array $match): string {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare(
                "INSERT INTO MatchDeRugby (dateHeure, adversaire, lieu, valider, archive) 
                   VALUES (:dateHeure, :adversaire, :lieu, 0, 0)");


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

    function readStats() : array{
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare("SELECT 
    CAST(COUNT(*) AS UNSIGNED) AS totalMatches, 
    CAST(COALESCE(SUM(resultat = 'VICTOIRE'), 0) AS UNSIGNED) AS matchesWon, 
    CAST(COALESCE(SUM(resultat = 'DEFAITE'), 0) AS UNSIGNED) AS matchesLoss, 
    CAST(COALESCE(SUM(resultat = 'NUL'), 0) AS UNSIGNED) AS matchesDrawed, 
    CONCAT(CAST(IFNULL(SUM(resultat = 'VICTOIRE') / NULLIF(SUM(resultat = 'DEFAITE'), 0), 0) AS DECIMAL(10, 3)), '%') AS winLossRatio 

FROM MatchDeRugby;");
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Erreur lors de la lecture des stats: " . $e->getMessage();
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

    function isArchiveMatch(int $idMatch): bool
    {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare(
                "SELECT archive FROM MatchDeRugby WHERE idMatch = :idMatch");

            $statement->bindParam(':idMatch', $idMatch);

            $statement->execute();
            $row = $statement->fetchAll(PDO::FETCH_ASSOC);
            return isset($row[0]) && $row[0]['archive'] == 1;
        } catch (PDOException $e) {
            echo "Erreur isARCHIVE: " . $e->getMessage();
        }
        return false;
    }

    function archiver(string $idMatch): bool
    {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare(
                "UPDATE MatchDeRugby SET archive = 1 WHERE idMatch = :idMatch");

            $statement->bindParam(':idMatch', $idMatch);

            return $statement->execute();
        } catch (PDOException $e) {
            echo "Erreur ARCHIVER: " . $e->getMessage();
        }
        return false;
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

    function readMatchWithResultat(int $limit): array
    {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare("SELECT * FROM MatchDeRugby WHERE valider = 1 ORDER BY dateHeure DESC LIMIT ?");
            $statement->bindValue(1, $limit, PDO::PARAM_INT);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur lors de la lecture des matches: " . $e->getMessage();
        }
        return [];
    }

    function readMatchAVenir(int $limit): array
    {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare("SELECT * FROM MatchDeRugby WHERE valider != 1 ORDER BY dateHeure ASC LIMIT ?");
            $statement->bindValue(1, $limit, PDO::PARAM_INT);
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
        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour du match: " . $e->getMessage();
            return false;
        }
    }
}