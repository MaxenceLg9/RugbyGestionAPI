<?php

namespace Joueur {


    use DateTime;
    use PDO;
    use PDOException;
    use PDOStatement;

    require_once "{$_SERVER["DOCUMENT_ROOT"]}/../libs/db/db.php";
    require_once $_SERVER["DOCUMENT_ROOT"]."../libs/modele/Poste.php";
    require_once $_SERVER["DOCUMENT_ROOT"]."../libs/modele/Statut.php";

    function formatJoueurs(mixed $joueurs): mixed
    {
        if(!isset($joueurs["url"])) {
            var_dump($joueurs);
            die();
        }
        $url = $_SERVER["DOCUMENT_ROOT"] . "/img/joueurs/" . $joueurs["url"];
        if (!file_exists($url))
            $joueurs["url"] = "https://rugbygestionapi.alwaysdata.net/img/data/default.png";
        else
            $joueurs["url"] = "https://rugbygestionapi.alwaysdata.net/img/joueurs/" . $joueurs["url"];
        $joueurs["postePrefere"] = \Poste::fromName($joueurs["postePrefere"])->value;
        $joueurs["statut"] = \Statut::fromName($joueurs["statut"])->value;
        $date = DateTime::createFromFormat('Y-m-d', $joueurs["dateNaissance"]);
        $joueurs["dateNaissance"] = $date->format('d/m/Y');
        $joueurs["estPremiereLigne"] = ($joueurs["estPremiereLigne"] == 0) ? "Non" : "Oui";
        return $joueurs;
    }

    /**
     * @throws DateMalformedStringException
     */
    function readByStatut(string $statut):array
    {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare("SELECT * FROM Joueur WHERE statut = :statut ORDER BY postePrefere, nom");
            $statement->bindParam(':statut',$statut);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur lors de la lecture des joueurs: " . $e->getMessage();
        }
        return [];
    }

    function statsJoueurs(): array
    {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare(
                "SELECT COALESCE(COUNT(DISTINCT idJoueur),0) as differents_joueurs FROM Participer");
            $statement->execute();

            $rs = $statement->fetchAll(PDO::FETCH_ASSOC);

            $statement = $connexion->prepare(
                "SELECT COALESCE(COUNT(DISTINCT idJoueur),0) as actifs_joueurs FROM Joueur WHERE statut = 'ACTIF'");
            $statement->execute();

            return array_merge(array_merge($statement->fetchAll(PDO::FETCH_ASSOC)[0],$rs[0]),array("joueurs" => readJoueursPlusUtilises()));
        } catch (PDOException $e) {
            echo "Erreur DISTINCT: " . $e->getMessage();
        }
        return [];
    }

    function readJoueursPlusUtilises() : array {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare(
                "SELECT P.idJoueur, COUNT(*) AS matchs_joues
FROM Participer AS P
JOIN Joueur AS J ON J.idJoueur = P.idJoueur
GROUP BY P.idJoueur
ORDER BY COUNT(*) DESC
LIMIT 5;");
            $statement->execute();

            return empty($statement->fetchAll(PDO::FETCH_ASSOC)) ? [] : $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur DISTINCT: " . $e->getMessage();
        }
        return [];
    }

    function readStatsIndiv(string $idJoueur = null): array {
        try {
            $connexion = getPDO();
            $query = "SELECT 
    J.idJoueur,
    J.url,
    J.nom,
    J.prenom,
    J.postePrefere,
    J.dateNaissance,
    J.estPremiereLigne,
    J.statut,
    COALESCE(AVG(P.note), 0) AS avg_note, 
    COALESCE(SUM(M.resultat = 'VICTOIRE'), 0) AS victories, 
    COALESCE(SUM(P.numero < 16),0) AS titulaires, 
    COALESCE(SUM(P.numero > 15),0) AS remplaçants, 
    CONCAT(COALESCE(SUM(M.resultat = 'VICTOIRE') / NULLIF(COUNT(DISTINCT M.idMatch), 0), 0),'%') AS victory_ratio 
FROM Joueur AS J  -- Include all players
LEFT JOIN Participer AS P ON J.idJoueur = P.idJoueur
LEFT JOIN MatchDeRugby AS M ON P.idMatch = M.idMatch AND M.archive = 1
WHERE (:idJoueur IS NULL OR J.idJoueur = :idJoueur)  -- Conditional filter: Either all players or specific player
GROUP BY J.idJoueur
ORDER BY J.idJoueur, victory_ratio DESC, avg_note DESC
";
            $statement = $connexion->prepare($query);
            $statement->bindParam(':idJoueur', $idJoueur);

            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as &$row){
                $row = formatJoueurs($row);
            }
//            var_dump($grouped_result);
            if ($idJoueur !== null) {
                return array_merge($result[0], readConsecutiveWinsPourJoueur($idJoueur)[0]);
            } else {
                $grouped_result = [];
                unset($row);
                foreach ($result as $row){
                    $idJoueur = $row["idJoueur"];
                    $grouped_result[$idJoueur] = $row;
                }
                unset($row);
                foreach (readConsecutiveWins() as $row) {
                    $grouped_result[$row["idJoueur"]] = array_merge($grouped_result[$row["idJoueur"]],$row);
                    unset($grouped_result[$row["idJoueur"]]["idJoueur"]);
                }
                return $grouped_result;
            }
        } catch (PDOException $e) {
            echo "Erreur lors de la lecture des joueurs: " . $e->getMessage();
        }
        return [];
    }

    function readConsecutiveWinsPourJoueur(string $idJoueur): array {
        try {
            $connexion = getPDO();
            $query = "WITH RankedMatches AS ( 
    SELECT
        p.idJoueur,
        m.idMatch - ROW_NUMBER() OVER (PARTITION BY p.idJoueur ORDER BY m.dateHeure) AS gap_group
    FROM Participer AS p
    JOIN MatchDeRugby AS m ON p.idMatch = m.idMatch
)
SELECT COALESCE((
    SELECT MAX(streak)
    FROM Joueur j
    LEFT JOIN (
        SELECT idJoueur, COUNT(*) AS streak
        FROM RankedMatches
        GROUP BY idJoueur, gap_group
    ) AS Streaks ON j.idJoueur = Streaks.idJoueur
    WHERE j.idJoueur = :idJoueur
    GROUP BY j.idJoueur
), 0) AS max_consecutive_matches
";
            $statement = $connexion->prepare($query);
            $statement->bindParam(':idJoueur', $idJoueur);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    function readConsecutiveWins(): array
    {
        try {
            $connexion = getPDO();
            $query = 'WITH RankedMatches AS (
    SELECT
        p.idJoueur,
        m.idMatch - ROW_NUMBER() OVER (PARTITION BY p.idJoueur ORDER BY m.dateHeure) AS gap_group
    FROM Participer AS p
             JOIN MatchDeRugby AS m ON p.idMatch = m.idMatch
),
     Consec_Matchs AS (
         SELECT j.idJoueur, COALESCE(MAX(streak), 0) AS streak
         FROM Joueur j
                  LEFT JOIN (
             SELECT idJoueur, COUNT(*) AS streak
             FROM RankedMatches
             GROUP BY idJoueur, gap_group
         ) AS Streaks ON j.idJoueur = Streaks.idJoueur
         GROUP BY j.idJoueur
     )
SELECT idJoueur, streak AS max_consecutive_matches
FROM Consec_Matchs
';
            $statement = $connexion->prepare($query);
            $statement->execute();
            //            var_dump($grouped_result);
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }


    function create(array $joueur): string {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare(
                "INSERT INTO Joueur (numeroLicence, nom, prenom, dateNaissance, taille, poids, statut, postePrefere, estPremiereLigne, commentaire, url) 
                   VALUES (:numeroLicence, :nom, :prenom, :dateNaissance, :taille, :poids, :statut, :postePrefere, :estPremiereLigne, :commentaire, :url)");

            bindParams($joueur, $statement);
            $url = $joueur["nom"] . "_" . $joueur["prenom"] . "_" . $joueur["dateNaissance"];
            $statement->bindParam(':url', $url);
            $statement->execute();

            return $connexion->lastInsertId();
        } catch (PDOException $e) {
            echo "Erreur lors de la création du joueur" . $e->getMessage();
        }
        return "";
    }


    function read(): array {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare("SELECT * FROM Joueur ORDER BY postePrefere, nom");
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur lors de la lecture des joueurs: " . $e->getMessage();
        }
        return [];
    }


    function readBynumeroLicence(string $numeroLicence): array {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare("SELECT * FROM Joueur WHERE numeroLicence = :numeroLicence");
            $statement->bindParam(':numeroLicence', $numeroLicence);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur lors de la lecture du joueur: " . $e->getMessage();
        }
        return [];
    }


    function readNonParticiperMatch(string $idMatch): array {
        try {
            $connection = getPDO();
            $statement = $connection->prepare("SELECT * FROM Joueur WHERE idJoueur NOT IN (SELECT idJoueur FROM Participer WHERE idMatch = :idMatch) AND statut = 'ACTIF' ORDER BY postePrefere, nom");
            $statement->bindParam(':idMatch', $idMatch);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            echo "Erreur lors de la lecture des joueurs participant au match: " . $e->getMessage();
        }
        return [];
    }

    function readOnMatch(string $idMatch): array {
        try {
            $connection = getPDO();
            $statement = $connection->prepare("SELECT J.*,P.numero FROM Joueur AS J JOIN Participer AS P ON P.idJoueur = J.idJoueur WHERE P.idMatch = :idMatch ORDER BY J.postePrefere, J.nom");
            $statement->bindParam(':idMatch', $idMatch);
            $statement->execute();

            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            $array = [];
            foreach($result as $row) {
                $array[$row["numero"]] = $row;
                unset($array[$row["numero"]]["numero"]);
            }
            return $array;
        }
        catch (PDOException $e) {
            echo "Erreur lors de la lecture des joueurs participant au match: " . $e->getMessage();
        }
        return [];
    }

    function update(array $joueur): bool {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare(
                "UPDATE Joueur SET taille = :taille, poids = :poids, statut = :statut,
                    postePrefere = :postePrefere, estPremiereLigne = :estPremiereLigne,
                    numeroLicence = :numeroLicence, nom = :nom, prenom = :prenom, dateNaissance = :dateNaissance, commentaire= :commentaire
              WHERE idJoueur = :idJoueur"
            );
            bindParams($joueur, $statement);
            $statement->bindParam(':idJoueur', $joueur["idJoueur"]);

            return $statement->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour du joueur: " . $e->getMessage();
        }
        return false;
    }

    function delete(string $joueur): bool {
        try {
            $connexion = getPDO();
            $statementParticiper = $connexion->prepare("DELETE FROM Participer WHERE idJoueur = :idJoueur");
            $statementParticiper->bindParam(':idJoueur', $joueur);
            $statementJoueur = $connexion->prepare("DELETE FROM Joueur WHERE idJoueur = :idJoueur");
            $statementJoueur->bindParam(':idJoueur', $joueur);
            return $statementParticiper->execute() && $statementJoueur->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression du joueur: " . $e->getMessage();
        }
        return false;
    }

    function readById(string $idJoueur): array {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare("SELECT * FROM Joueur WHERE idJoueur = :idJoueur");
            $statement->bindParam(':idJoueur', $idJoueur);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur lors de la lecture du joueur: " . $e->getMessage();
        }
        return [];
    }

    /**
     * @param array $joueur
     * @param bool|PDOStatement $statement
     * @return void
     */
    function bindParams(array $joueur, bool|PDOStatement $statement): void {
        $statement->bindParam(':numeroLicence', $joueur["numeroLicence"]);
        $statement->bindParam(':nom', $joueur["nom"]);
        $statement->bindParam(':prenom', $joueur["prenom"]);
        $statement->bindParam(':dateNaissance', $joueur["dateNaissance"]);
        $statement->bindParam(':taille', $joueur["taille"]);
        $statement->bindParam(':poids', $joueur["poids"]);
        $statement->bindParam(':statut', $joueur["statut"]);
        $statement->bindParam(':postePrefere', $joueur["postePrefere"]);
        $statement->bindParam(':estPremiereLigne', $joueur["estPremiereLigne"]);
        $statement->bindParam(':commentaire', $joueur["commentaire"]);
    }
}