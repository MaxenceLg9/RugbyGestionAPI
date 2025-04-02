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
    function readActif():array
    {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare("SELECT * FROM Joueur WHERE statut = 'ACTIF' ORDER BY postePrefere, nom");
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur lors de la lecture des joueurs: " . $e->getMessage();
        }
        return [];
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