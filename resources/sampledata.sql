DELETE FROM Participer;
DELETE FROM Joueur;
DELETE FROM MatchDeRugby;

INSERT INTO Joueur (numeroLicence, nom, prenom, dateNaissance, taille, poids, statut, postePrefere, estPremiereLigne, commentaire, url)
VALUES
-- PILIERs
(2001, 'Atonio', 'Uini', '1990-03-26', 196, 145, 'ACTIF', 'PILIER', TRUE, 'Puissance brute et PILIER expérimenté.', 'Atonio_Uini_1990-03-26.png'),
(2002, 'Colombe', 'Georges-Henri', '1998-04-17', 190, 125, 'ACTIF', 'PILIER', TRUE, 'Un jeune joueur en plein essor.', 'Colombe_Georges-Henri_1998-04-17.png'),
(2003, 'Gros', 'Jean-Baptiste', '1999-05-25', 185, 115, 'ACTIF', 'PILIER', TRUE, 'Technique et solide en mêlée.', 'Gros_Jean-Baptiste_1999-05-25.png'),
(2004, 'Tatafu', 'Tevita', '1997-11-15', 182, 118, 'ACTIF', 'PILIER', TRUE, 'Polyvalent et puissant.', 'Tatafu_Tevita_1997-11-15.png'),
(2005, 'Wardi', 'Reda', '1995-09-16', 183, 115, 'ACTIF', 'PILIER', TRUE, 'Excellent soutien en mêlée fermée.', 'Wardi_Reda_1995-09-16.png'),
(2006, 'Baille', 'Cyril', '1993-09-15', 182, 119, 'ACTIF', 'PILIER', 1, 'Strong and reliable in scrums.', 'Baille_Cyril_1993-09-15.png'),

-- Talonneurs
(2101, 'Barlot', 'Gaëtan', '1997-07-04', 180, 102, 'ACTIF', 'TALONNEUR', TRUE, 'Très mobile avec une excellente technique.', 'Barlot_Gaëtan_1997-07-04.png'),
(2102, 'Marchand', 'Julien', '1995-05-29', 178, 106, 'BLESSE', 'TALONNEUR', TRUE, 'Capitaine exemplaire et bon leader.', 'Marchand_Julien_1995-05-29.png'),
(2103, 'Mauvaka', 'Peato', '1997-01-10', 184, 118, 'ACTIF', 'TALONNEUR', TRUE, 'Un impact player exceptionnel.', 'Mauvaka_Peato_1997-01-10.png'),
(2104, 'Bourgarit', 'Pierre', '1997-09-12', 185, 108, 'ACTIF', 'TALONNEUR', 1, 'Dynamic hooker with a strong presence on the field.', 'Bourgarit_Pierre_1997-09-12.png'),

-- DEUXIEME_LIGNEs
(2201, 'Flament', 'Thibaud', '1997-04-29', 200, 112, 'ACTIF', 'DEUXIEME_LIGNE', TRUE, 'Mobile et solide en touche.', 'Flament_Thibaud_1997-04-29.png'),
(2202, 'Guillard', 'Mickaël', '1998-02-17', 195, 115, 'ACTIF', 'DEUXIEME_LIGNE', TRUE, 'Fiable en touche et très physique.', 'Guillard_Mickaël_1998-02-17.png'),
(2203, 'Meafou', 'Emmanuel', '1998-07-12', 202, 140, 'ACTIF', 'DEUXIEME_LIGNE', TRUE, 'Puissant avec une grosse présence physique.', 'Meafou_Emmanuel_1998-07-12.png'),
(2204, 'Taofifenua', 'Romain', '1990-09-14', 200, 140, 'ACTIF', 'DEUXIEME_LIGNE', TRUE, 'Force de la nature avec beaucoup d’expérience.', 'Taofifenua_Romain_1990-09-14.png'),
(2205, 'Willemse', 'Paul', '1992-11-13', 201, 135, 'ACTIF', 'DEUXIEME_LIGNE', 0, 'Towering lock, key in lineouts and scrums.', 'Willemse_Paul_1992-11-13.png'),

-- Troisième lignes Ailes
(2301, 'Boudehent', 'Paul', '1999-11-22', 193, 105, 'ACTIF', 'TROISIEME_LIGNE_AILE', FALSE, 'Plaqueur efficace et très mobile.', 'Boudehent_Paul_1999-11-22.png'),
(2302, 'Cros', 'François', '1994-03-25', 192, 104, 'ACTIF', 'TROISIEME_LIGNE_AILE', FALSE, 'Leader défensif et plaqueur hors pair.', 'Cros_François_1994-03-25.png'),
(2303, 'Nouchi', 'Lenni', '2001-06-15', 190, 100, 'ACTIF', 'TROISIEME_LIGNE_AILE', FALSE, 'Joueur prometteur et polyvalent.', 'Nouchi_Lenni_2001-06-15.png'),
(2304, 'Ollivon', 'Charles', '1993-05-11', 199, 113, 'ACTIF', 'TROISIEME_LIGNE_AILE', FALSE, 'Un des leaders naturels du XV.', 'Ollivon_Charles_1993-05-11.png'),
(2305, 'Tixeront', 'Killian', '1998-08-22', 190, 102, 'ACTIF', 'TROISIEME_LIGNE_AILE', FALSE, 'Plaqueur et gratteur efficace.', 'Tixeront_Killian_1998-08-22.png'),

-- Troisième lignes CENTREs
(2401, 'Alldritt', 'Grégory', '1997-03-23', 191, 115, 'ACTIF', 'TROISIEME_LIGNE_CENTRE', FALSE, 'Puissant avec une excellente lecture de jeu.', 'Alldritt_Grégory_1997-03-23.png'),
(2402, 'Gazzotti', 'Marko', '2004-04-08', 192, 110, 'ACTIF', 'TROISIEME_LIGNE_CENTRE', FALSE, 'Jeune joueur à fort potentiel.', 'Gazzotti_Marko_2004-04-08.png'),
(2403, 'Roumat', 'Alexandre', '1997-06-13', 200, 112, 'ACTIF', 'TROISIEME_LIGNE_CENTRE', FALSE, 'Très efficace en touche.', 'Roumat_Alexandre_1997-06-13.png'),

-- Demis de mêlée
(2501, 'Dupont', 'Antoine', '1996-11-15', 175, 84, 'ACTIF', 'DEMI_MELEE', FALSE, 'Le meilleur 9 du monde.', 'Dupont_Antoine_1996-11-15.png'),
(2502, 'Le Garrec', 'Nolann', '2002-04-22', 180, 78, 'ACTIF', 'DEMI_MELEE', FALSE, 'Un jeune 9 prometteur.', 'Le_Garrec_Nolann_2002-04-22.png'),
(2503, 'Lucu', 'Maxime', '1993-05-12', 177, 80, 'ACTIF', 'DEMI_MELEE', FALSE, 'Solide dans la gestion du jeu.', 'Lucu_Maxime_1993-05-12.png'),
(2504, 'Serin', 'Baptiste', '1994-06-20', 180, 82, 'ACTIF', 'DEMI_MELEE', FALSE, 'Créatif et technique.', 'Serin_Baptiste_1994-06-20.png'),
(2505, 'Couilloud', 'Baptiste', '1997-07-22', 178, 82, 'ACTIF', 'DEMI_MELEE', 0, 'Agile scrum-half with excellent passing skills.', 'Couilloud_Baptiste_1997-07-22.png'),

-- Demis d’ouverture
(2601, 'Jalibert', 'Matthieu', '1998-11-06', 184, 86, 'ACTIF', 'DEMI_OUVERTURE', FALSE, 'Explosif et très bon en attaque.', 'Jalibert_Matthieu_1998-11-06.png'),
(2602, 'Ntamack', 'Romain', '1999-05-01', 186, 87, 'ACTIF', 'DEMI_OUVERTURE', 0, 'Star player known for his quick decision-making.', 'Ntamack_Romain_1999-05-01.png'),
(2603, 'Carbonel', 'Louis', '2000-10-18', 183, 85, 'ACTIF', 'DEMI_OUVERTURE', FALSE, 'Excellent buteur et gestionnaire de jeu.', 'Carbonel_Louis_2000-10-18.png'),

-- Centres
(2701, 'Fickou', 'Gaël', '1994-03-26', 191, 101, 'ACTIF', 'CENTRE', FALSE, 'Leader sur le terrain et très expérimenté.', 'Fickou_Gaël_1994-03-26.png'),
(2702, 'Frisch', 'Antoine', '1996-11-04', 188, 95, 'ACTIF', 'CENTRE', FALSE, 'Polyvalent et solide.', 'Frisch_Antoine_1996-11-04.png'),
(2703, 'Gailleton', 'Emilien', '2003-01-13', 190, 92, 'ACTIF', 'CENTRE', FALSE, 'Jeune CENTRE très prometteur.', 'Gailleton_Emilien_2003-01-13.png'),

-- Ailiers
(2801, 'Moefana', 'Yoram', '2000-06-18', 182, 93, 'ACTIF', 'AILIER', FALSE, 'Puissant et rapide.', 'Moefana_Yoram_2000-06-18.png'),
(2802, 'Villiere', 'Gabin', '1995-12-19', 180, 85, 'SUSPENDU', 'AILIER', FALSE, 'Connu pour son agressivité positive.', 'Villiere_Gabin_1995-12-19.png'),
(2803, 'Penaud', 'Damian', '1996-11-25', 190, 96, 'ACTIF', 'AILIER', 0, 'Versatile winger with great scoring abilities.', 'Penaud_Damian_1996-11-25.png'),
(2804, 'Attisogbe', 'Theo', '2001-03-15', 184, 92, 'ACTIF', 'AILIER', 0, 'Young and promising winger with explosive speed.', 'Attisogbe_Theo_2001-03-15.png'),

-- Arrières
(2901, 'Barré', 'Léo', '1999-07-15', 185, 85, 'ACTIF', 'ARRIERE', FALSE, 'Un joueur polyvalent et sûr sous les ballons hauts.', 'Barré_Léo_1999-07-15.png'),
(2902, 'Bielle-Biarrey', 'Louis', '2003-06-14', 184, 83, 'ACTIF', 'ARRIERE', FALSE, 'Un jeune joueur prometteur avec un bon jeu au pied.', 'Bielle-Biarrey_Louis_2003-06-14.png'),
(2903, 'Buros', 'Romain', '1997-09-08', 188, 90, 'ACTIF', 'ARRIERE', FALSE, 'Excellent relanceur avec de la vitesse.', 'Buros_Romain_1997-09-08.png'),
(2904, 'Ramos', 'Thomas', '1995-07-23', 178, 82, 'ACTIF', 'ARRIERE', FALSE, 'Solide au pied et intelligent dans sa gestion du jeu.', 'Ramos_Thomas_1995-07-23.png');



INSERT INTO MatchDeRugby (dateHeure, adversaire, lieu, resultat, valider,archive)
VALUES
    ('2025-02-03 15:00:00', 'Pays de Galles', 'DOMICILE', 'VICTOIRE', TRUE,TRUE),
    ('2025-02-10 17:30:00', 'Irlande', 'EXTERIEUR', NULL, FALSE,FALSE),
    ('2025-03-02 16:00:00', 'Ecosse', 'DOMICILE', NULL, FALSE,FALSE),
    ('2025-03-09 18:00:00', 'Angleterre', 'EXTERIEUR', NULL, FALSE,FALSE),
    ('2025-03-16 20:45:00', 'Italie', 'DOMICILE', NULL, FALSE,TRUE),
    ('2024-11-25 15:00:00', 'Australie', 'EXTERIEUR', 'VICTOIRE', TRUE,TRUE),
    ('2024-10-30 20:00:00', 'Argentine', 'DOMICILE', 'VICTOIRE', TRUE,TRUE),
    ('2024-08-15 19:00:00', 'Fidji', 'EXTERIEUR', 'VICTOIRE', TRUE,TRUE);


-- INSERT INTO Participer (idMatch, idJoueur, estTitulaire, numero)
-- VALUES
--     (1,1,1,1)
--     ,(1,2,1,2)
--     ,(1,3,1,3)
--     ,(1,4,1,4);
