<?php

namespace FDM {

    use PDO;
    use PDOException;
    use PDOStatement;

    require_once "{$_SERVER["DOCUMENT_ROOT"]}/../libs/db/db.php";

    function fillFDM(array $fdm): array {
        //make update or insert given the PRIMARY KEY exists or not
        $match = readByMatch($fdm["idMatch"])["matchs"];
        if(array_key_exists($fdm["idMatch"], $match)){
            $existingFDM = $match[$fdm["idMatch"]]["feuilles"];
        } else {
            $existingFDM = array("feuilles" => []);
        }
        $createFeuilles = array();
        $updateFeuilles = array();
        $deleteFeuilles = array();

        for ($i = 1; $i <= 23; $i++) {
            if (array_key_exists($i, $fdm["feuilles"])) {
                if (array_key_exists($i, $existingFDM)) {
                    $updateFeuilles[$i] = $fdm["feuilles"][$i];
                } else {
                    $createFeuilles[$i] = $fdm["feuilles"][$i];
                }
            } else if (array_key_exists($i, $existingFDM)) {
                $deleteFeuilles[$i] = $existingFDM[$i];
            }
        }
        create($createFeuilles, $fdm["idMatch"]);
        update($updateFeuilles, $fdm["idMatch"]);
        delete($deleteFeuilles, $fdm["idMatch"]);

        return readByMatch($fdm["idMatch"]);
    }

    function create(array $feuilles, string $idMatch): void {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare(
                "INSERT INTO Participer (idMatch, idJoueur, estTitulaire, numero) 
             VALUES (:idMatch, :idJoueur, :estTitulaire, :numero)");

            foreach ($feuilles as $key => $fdm) {
                bindParams($statement, $fdm, $key, $idMatch);
                $statement->execute();
            }
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }

    function update(array $feuilles, string $idMatch): void {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare(
                "UPDATE Participer SET estTitulaire = :estTitulaire, idJoueur = :idJoueur
             WHERE idMatch = :idMatch AND numero = :numero");

            foreach ($feuilles as $key => $fdm) {
                bindParams($statement, $fdm, $key, $idMatch);
                $statement->execute();
            }
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }

    function setNotes(array $feuilles,string $idMatch): array {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare(
                "UPDATE Participer SET note = :note
             WHERE idMatch = :idMatch AND numero = :numero");

            foreach ($feuilles as $key => $fdm) {
                $statement->bindParam(":idMatch",$idMatch);
                $statement->bindParam(":numero",$key);
                $statement->bindParam(":note",$fdm["note"]);
                $statement->execute();
            }
            return readByMatch($idMatch);
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            return [];
        }
    }

    function delete(array $feuilles, string $idMatch): void
    {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare(
                "DELETE FROM Participer WHERE idMatch = :idMatch AND idJoueur = :idJoueur");

            foreach ($feuilles as $fdm) {
                $statement->bindParam(':idMatch', $idMatch);
                $statement->bindParam(':idJoueur', $fdm["idJoueur"]);
                $statement->execute();
            }

        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }

    function read(): array {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare("SELECT * FROM Participer ORDER BY idMatch");
            $statement->execute();
            return sortFDMs($statement->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
        return [];
    }

    function readByMatch(int $idMatch): array
    {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare(
                "SELECT * FROM Participer WHERE idMatch = :idMatch ORDER BY numero");

            $statement->bindParam(':idMatch', $idMatch);

            $statement->execute();
            return sortFDMs($statement->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
        return [];
    }

    function readByJoueur(string $idJoueur): array
    {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare(
                "SELECT P.*,M.lieu,M.dateHeure,M.adversaire,M.resultat FROM Participer as P JOIN MatchDeRugby as M ON P.idMatch = M.idMatch WHERE P.idJoueur = :idJoueur AND P.archive = 1 AND M.Resultat is not null AND M.Valider = 1");

            $statement->bindParam(':idJoueur', $idJoueur);

            $statement->execute();
            return sortFDMs($statement->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
        return [];
    }

    function readByNumeroAndMatch(array $ids): array
    {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare(
                "SELECT P.*,M.lieu,M.dateHeure,M.adversaire,M.resultat FROM Participer as P JOIN MatchDeRugby as M ON P.idMatch = M.idMatch WHERE P.numero = :numero AND P.idMatch = :idMatch AND P.archive = 1 AND M.Resultat is not null AND M.Valider = 1");

            $statement->bindParam(':numero', $ids["numero"]);
            $statement->bindParam(':idMatch', $ids["idMatch"]);

            $statement->execute();
            return sortFDMs($statement->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
        return [];
    }

    function isArchiveFDM(int $idMatch): bool
    {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare(
                "SELECT archive FROM Participer WHERE idMatch = :idMatch");

            $statement->bindParam(':idMatch', $idMatch);

            $statement->execute();
            $row = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $row[0] ? $row[0]['archive'] : false;
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
        return false;
    }

    function archiver(string $idMatch): bool
    {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare(
                "UPDATE Participer SET archive = 1 WHERE idMatch = :idMatch");

            $statement->bindParam(':idMatch', $idMatch);

            return $statement->execute();
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
        return false;
    }

    function sortFDMs(array $fdms): array {
        $sortedFDM = [];
        foreach ($fdms as $fdm) {
            $idMatch = $fdm["idMatch"];
            if (!isset($sortedFDM[$idMatch])) {
                $sortedFDM[$idMatch] = [];
            }
            $numero = $fdm["numero"];
            unset($fdm["idMatch"]);
            unset($fdm["numero"]);
            $sortedFDM[$idMatch]["feuilles"][$numero] = $fdm;

        }
        return array("matchs" => $sortedFDM);
    }

    /**
     * @param PDOStatement $statement
     * @param array $fdm
     * @return void
     */
    function bindParams(PDOStatement $statement, array $fdm, string $numero, string $idMatch): void
    {
        $statement->bindParam(':idMatch', $idMatch);
        $statement->bindParam(':estTitulaire', $fdm["estTitulaire"]);
        $statement->bindParam(':numero', $numero);
        $statement->bindParam(':idJoueur', $fdm["idJoueur"]);
    }

}