<?php

require_once '../db/db.php';

class DAOJoueur {

    /**
     * @throws DateMalformedStringException
     */
    public static function readActif():array
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

    public static function create(array $joueur): string {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare(
                "INSERT INTO Joueur (numeroLicense, nom, prenom, dateNaissance, taille, poids, statut, postePrefere, estPremiereLigne, commentaire) 
                   VALUES (:numeroLicense, :nom, :prenom, :dateNaissance, :taille, :poids, :statut, :postePrefere, :estPremiereLigne, :commentaire)");

            self::bindParams($joueur, $statement);
            $statement->execute();

            return $connexion->lastInsertId();
        } catch (PDOException $e) {
            echo "Erreur lors de la création du joueur" . $e->getMessage();
        }
        return "";
    }


    public static function read(): array {
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


    public function readByNumeroLicense(int $numeroLicense): array {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare("SELECT * FROM Joueur WHERE numeroLicense = :numeroLicense");
            $statement->bindParam(':numeroLicense', $numeroLicense);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur lors de la lecture du joueur: " . $e->getMessage();
        }
        return [];
    }


    public static function readNonParticiperMatch(int $idMatch): array {
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

    public static function update(array $joueur): bool {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare(
                "UPDATE Joueur SET taille = :taille, poids = :poids, statut = :statut,
                    postePrefere = :postePrefere, estPremiereLigne = :estPremiereLigne,
                    numeroLicense = :numeroLicense, nom = :nom, prenom = :prenom, dateNaissance = :dateNaissance, commentaire= :commentaire
              WHERE idJoueur = :idJoueur"
            );
            self::bindParams($joueur, $statement);
            $statement->bindParam(':idJoueur', $joueur["idJoueur"]);

            return$statement->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour du joueur: " . $e->getMessage();
        }
        return false;
    }

    public static function delete(string $joueur): bool {
        try {
            $connexion = getPDO();
            $statement = $connexion->prepare("DELETE FROM Joueur WHERE idJoueur = :idJoueur");
            $statement->bindParam(':idJoueur', $joueur);
            return $statement->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression du joueur: " . $e->getMessage();
        }
        return false;
    }

    public static function readById(int $idJoueur): array {
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
    public static function bindParams(array $joueur, bool|PDOStatement $statement): void {
        $statement->bindParam(':numeroLicense', $joueur["numeroLicense"]);
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