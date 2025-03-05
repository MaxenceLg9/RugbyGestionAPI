DROP TABLE IF EXISTS Participer;
DROP TABLE IF EXISTS MatchDeRugby;
DROP TABLE IF EXISTS Joueur;
DROP TABLE IF EXISTS Entraineur;

CREATE TABLE IF NOT EXISTS Joueur (
                                      idJoueur INT PRIMARY KEY AUTO_INCREMENT,
                                      numeroLicense INT(4) NOT NULL,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    dateNaissance DATE NOT NULL,
    taille INT(3) NOT NULL,
    poids INT(3) NOT NULL,
    statut ENUM('ACTIF', 'BLESSE', 'SUSPENDU', 'ABSENT') NOT NULL,
    postePrefere ENUM(
                         'PILIER',
                         'TALONNEUR',
                         'DEUXIEME_LIGNE',
                         'TROISIEME_LIGNE_AILE',
                         'TROISIEME_LIGNE_CENTRE',
                         'DEMI_MELEE',
                         'DEMI_OUVERTURE',
                         'CENTRE',
                         'AILIER',
                         'ARRIERE'
                     ) NOT NULL,
    estPremiereLigne BOOLEAN NOT NULL,
    commentaire VARCHAR(400),
    url VARCHAR(100) NULL
    );

CREATE TABLE IF NOT EXISTS MatchDeRugby (
                                            idMatch INT PRIMARY KEY AUTO_INCREMENT,
                                            dateHeure DATETIME NOT NULL,
                                            adversaire VARCHAR(50) NOT NULL,
    lieu ENUM('DOMICILE', 'EXTERIEUR') NOT NULL,
    resultat ENUM('VICTOIRE', 'DEFAITE', 'NUL') NULL,
    valider BOOLEAN NOT NULL
    );

CREATE TABLE IF NOT EXISTS Participer (
                                              idMatch INT NOT NULL,
                                              idJoueur INT NOT NULL,
                                              estTitulaire BOOLEAN NULL,
                                              numero INT NOT NULL,
    note FLOAT NULL,
    archive BOOLEAN NOT NULL,
    PRIMARY KEY (idMatch, numero),
    FOREIGN KEY (idMatch) REFERENCES MatchDeRugby(idMatch),
    FOREIGN KEY (idJoueur) REFERENCES Joueur(idJoueur)
    );

CREATE TABLE IF NOT EXISTS Entraineur (
                                          idEntraineur INT PRIMARY KEY AUTO_INCREMENT,
                                          nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    equipe VARCHAR(20) NOT NULL,
    motDePasse VARCHAR(60) NOT NULL
    );

ALTER TABLE Joueur CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE MatchDeRugby CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE Participer CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE Entraineur CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;