<?php

namespace Joueur {

    use DateMalformedStringException;
    use PDO;
    use PDOException;
    use PDOStatement;

    require_once "{$_SERVER["DOCUMENT_ROOT"]}/../libs/db/db.php";

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
                "INSERT INTO Joueur (numeroLicence, nom, prenom, dateNaissance, taille, poids, statut, postePrefere, estPremiereLigne, commentaire) 
                   VALUES (:numeroLicence, :nom, :prenom, :dateNaissance, :taille, :poids, :statut, :postePrefere, :estPremiereLigne, :commentaire)");

            bindParams($joueur, $statement);
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


    function readBynumeroLicence(int $numeroLicence): array {
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


    function readNonParticiperMatch(int $idMatch): array {
        try {
            $connection = getPDO();
            $statement = $connection->prepare("SELECT * FROM Joueur WHERE idJoueur NOT IN (SELECT idJoueur FROM Participer WHERE idMatch = :idMatch)");
            $statement->bindParam(':idMatch', $idMatch);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
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