INSERT INTO Utilisateur VALUE (DEFAULT, 'Alexandre', 'Pham', 'alex@gmail.com', 'etudiant', 'alex', 'alex', 'C:\\xampp\htdocs\\photo_profil\\default_avatar.png');
INSERT INTO Utilisateur VALUE (DEFAULT, 'Ponnou', 'Yovanne', 'yovanne@gmail.com', 'etudiant', 'yovanne', 'yovanne', 'C:\\xampp\htdocs\\photo_profil\\default_avatar.png');
INSERT INTO Utilisateur VALUE (DEFAULT, 'De Tommaso', 'Eric', 'eric@gmail.com', 'etudiant', 'eric', 'eric', 'C:\\xampp\htdocs\\photo_profil\\default_avatar.png');
INSERT INTO Utilisateur VALUE (DEFAULT, 'Mazur', 'Jakub', 'jakub@gmail.com', 'etudiant', 'jakub', 'jakub', 'C:\\xampp\htdocs\\photo_profil\\default_avatar.png');

INSERT INTO Utilisateur VALUE (DEFAULT, 'Aurélien', 'Bossard', 'bossard@gmail.com', 'professeur', 'bossard', 'bossard', 'C:\\xampp\htdocs\\photo_profil\\default_avatar.png');
INSERT INTO Utilisateur VALUE (DEFAULT, 'Philipe', 'Bonnot', 'bonnot@gmail.com', 'professeur', 'bonnot', 'bonnot', 'C:\\xampp\htdocs\\photo_profil\\default_avatar.png');
INSERT INTO Utilisateur VALUE (DEFAULT, 'Marc', 'Homps', 'homps@gmail.com', 'professeur', 'homps', 'homps', 'C:\\xampp\htdocs\\photo_profil\\default_avatar.png');

INSERT INTO Utilisateur VALUE (DEFAULT, 'pauline', 'pam', 'intervenant@gmail.com', 'intervenant', 'pam', 'pam', 'C:\\xampp\htdocs\\photo_profil\\default_avatar.png');


INSERT INTO Utilisateur VALUE (DEFAULT, 'admin', 'admin', 'admin@gmail.com', 'admin', 'admin', 'admin', 'C:\\xampp\htdocs\\photo_profil\\default_avatar.png');


INSERT INTO Annee_Scolaire (annee_debut, annee_fin, semestre) VALUES (2024, 2027, '1');
INSERT INTO Annee_Scolaire (annee_debut, annee_fin, semestre) VALUES (2023, 2026, '3');
INSERT INTO Annee_Scolaire (annee_debut, annee_fin, semestre) VALUES (2022, 2025, '5');

INSERT INTO Etudiant_Annee (id_utilisateur, id_annee)
VALUES
    ((SELECT id_utilisateur FROM Utilisateur WHERE login_utilisateur = 'alex'), (SELECT id_annee FROM Annee_Scolaire WHERE annee_debut = 2024 AND annee_fin = 2027)),
    ((SELECT id_utilisateur FROM Utilisateur WHERE login_utilisateur = 'yovanne'), (SELECT id_annee FROM Annee_Scolaire WHERE annee_debut = 2023 AND annee_fin = 2026)),
    ((SELECT id_utilisateur FROM Utilisateur WHERE login_utilisateur = 'eric'), (SELECT id_annee FROM Annee_Scolaire WHERE annee_debut = 2023 AND annee_fin = 2026)),
    ((SELECT id_utilisateur FROM Utilisateur WHERE login_utilisateur = 'jakub'), (SELECT id_annee FROM Annee_Scolaire WHERE annee_debut = 2022 AND annee_fin = 2025));

INSERT INTO Utilisateur (nom, prenom, email, type_utilisateur, login_utilisateur, password_utilisateur, profil_picture) VALUES
                                                                                                                            ('Dupont', 'Pierre', 'pierre.dupont@gmail.com', 'etudiant', 'pierredupont', 'pierredupont', 'C:\\xampp\htdocs\\photo_profil\\default_avatar.png'),
                                                                                                                            ('Martin', 'Julien', 'julien.martin@gmail.com', 'etudiant', 'julienmartin', 'julienmartin', 'C:\\xampp\htdocs\\photo_profil\\default_avatar.png'),
                                                                                                                            ('Bernard', 'Sophie', 'sophie.bernard@gmail.com', 'etudiant', 'sophiebernard', 'sophiebernard', 'C:\\xampp\htdocs\\photo_profil\\default_avatar.png'),
                                                                                                                            ('Durand', 'Luc', 'luc.durand@gmail.com', 'etudiant', 'lucdurand', 'lucdurand', 'C:\\xampp\htdocs\\photo_profil\\default_avatar.png'),
                                                                                                                            ('Lemoine', 'Claire', 'claire.lemoine@gmail.com', 'etudiant', 'clairelemoine', 'clairelemoine', 'C:\\xampp\htdocs\\photo_profil\\default_avatar.png'),
                                                                                                                            ('Rousseau', 'Marc', 'marc.rousseau@gmail.com', 'etudiant', 'marcrousseau', 'marcrousseau', 'C:\\xampp\htdocs\\photo_profil\\default_avatar.png'),
                                                                                                                            ('Thomas', 'Chloé', 'chloe.thomas@gmail.com', 'etudiant', 'chloethomas', 'chloethomas', 'C:\\xampp\htdocs\\photo_profil\\default_avatar.png'),
                                                                                                                            ('Leclerc', 'Léo', 'leo.leclerc@gmail.com', 'etudiant', 'leoleclerc', 'leoleclerc', 'C:\\xampp\htdocs\\photo_profil\\default_avatar.png'),
                                                                                                                            ('Vidal', 'Laura', 'laura.vidal@gmail.com', 'etudiant', 'lauravidal', 'lauravidal', 'C:\\xampp\htdocs\\photo_profil\\default_avatar.png'),
                                                                                                                            ('Pires', 'Hugo', 'hugo.pires@gmail.com', 'etudiant', 'hugopires', 'hugopires', 'C:\\xampp\htdocs\\photo_profil\\default_avatar.png'),
                                                                                                                            ('Fournier', 'Amélie', 'amelie.fournier@gmail.com', 'etudiant', 'ameliefournier', 'ameliefournier', 'C:\\xampp\htdocs\\photo_profil\\default_avatar.png'),
                                                                                                                            ('Lemoine', 'Mélanie', 'melanie.lemoine@gmail.com', 'etudiant', 'melanielemoine', 'melanielemoine', 'C:\\xampp\htdocs\\photo_profil\\default_avatar.png'),
                                                                                                                            ('Gauthier', 'Antoine', 'antoine.gauthier@gmail.com', 'etudiant', 'antoinegauthier', 'antoinegauthier', 'C:\\xampp\htdocs\\photo_profil\\default_avatar.png'),
                                                                                                                            ('Benoit', 'Elodie', 'elodie.benoit@gmail.com', 'etudiant', 'elodiebenoit', 'elodiebenoit', 'C:\\xampp\htdocs\\photo_profil\\default_avatar.png'),
                                                                                                                            ('Carre', 'Sylvain', 'sylvain.carre@gmail.com', 'etudiant', 'sylvaincarre', 'sylvaincarre', 'C:\\xampp\htdocs\\photo_profil\\default_avatar.png'),
                                                                                                                            ('Blanc', 'Isabelle', 'isabelle.blanc@gmail.com', 'etudiant', 'isabelleblanc', 'isabelleblanc', 'C:\\xampp\htdocs\\photo_profil\\default_avatar.png');

INSERT INTO Etudiant_Annee (id_utilisateur, id_annee)
VALUES
    ((SELECT id_utilisateur FROM Utilisateur WHERE login_utilisateur = 'pierredupont'), (SELECT id_annee FROM Annee_Scolaire WHERE annee_debut = 2024 AND annee_fin = 2027 AND semestre = '1')),
    ((SELECT id_utilisateur FROM Utilisateur WHERE login_utilisateur = 'julienmartin'), (SELECT id_annee FROM Annee_Scolaire WHERE annee_debut = 2023 AND annee_fin = 2026 AND semestre = '3')),
    ((SELECT id_utilisateur FROM Utilisateur WHERE login_utilisateur = 'sophiebernard'), (SELECT id_annee FROM Annee_Scolaire WHERE annee_debut = 2022 AND annee_fin = 2025 AND semestre = '5')),
    ((SELECT id_utilisateur FROM Utilisateur WHERE login_utilisateur = 'lucdurand'), (SELECT id_annee FROM Annee_Scolaire WHERE annee_debut = 2024 AND annee_fin = 2027 AND semestre = '1')),
    ((SELECT id_utilisateur FROM Utilisateur WHERE login_utilisateur = 'clairelemoine'), (SELECT id_annee FROM Annee_Scolaire WHERE annee_debut = 2023 AND annee_fin = 2026 AND semestre = '3')),
    ((SELECT id_utilisateur FROM Utilisateur WHERE login_utilisateur = 'marcrousseau'), (SELECT id_annee FROM Annee_Scolaire WHERE annee_debut = 2022 AND annee_fin = 2025 AND semestre = '5')),
    ((SELECT id_utilisateur FROM Utilisateur WHERE login_utilisateur = 'chloethomas'), (SELECT id_annee FROM Annee_Scolaire WHERE annee_debut = 2024 AND annee_fin = 2027 AND semestre = '1')),
    ((SELECT id_utilisateur FROM Utilisateur WHERE login_utilisateur = 'leoleclerc'), (SELECT id_annee FROM Annee_Scolaire WHERE annee_debut = 2023 AND annee_fin = 2026 AND semestre = '3')),
    ((SELECT id_utilisateur FROM Utilisateur WHERE login_utilisateur = 'lauravidal'), (SELECT id_annee FROM Annee_Scolaire WHERE annee_debut = 2022 AND annee_fin = 2025 AND semestre = '5')),
    ((SELECT id_utilisateur FROM Utilisateur WHERE login_utilisateur = 'hugopires'), (SELECT id_annee FROM Annee_Scolaire WHERE annee_debut = 2024 AND annee_fin = 2027 AND semestre = '1')),
    ((SELECT id_utilisateur FROM Utilisateur WHERE login_utilisateur = 'ameliefournier'), (SELECT id_annee FROM Annee_Scolaire WHERE annee_debut = 2023 AND annee_fin = 2026 AND semestre = '3')),
    ((SELECT id_utilisateur FROM Utilisateur WHERE login_utilisateur = 'melanielemoine'), (SELECT id_annee FROM Annee_Scolaire WHERE annee_debut = 2022 AND annee_fin = 2025 AND semestre = '5')),
    ((SELECT id_utilisateur FROM Utilisateur WHERE login_utilisateur = 'antoinegauthier'), (SELECT id_annee FROM Annee_Scolaire WHERE annee_debut = 2024 AND annee_fin = 2027 AND semestre = '1')),
    ((SELECT id_utilisateur FROM Utilisateur WHERE login_utilisateur = 'elodiebenoit'), (SELECT id_annee FROM Annee_Scolaire WHERE annee_debut = 2023 AND annee_fin = 2026 AND semestre = '3')),
    ((SELECT id_utilisateur FROM Utilisateur WHERE login_utilisateur = 'sylvaincarre'), (SELECT id_annee FROM Annee_Scolaire WHERE annee_debut = 2022 AND annee_fin = 2025 AND semestre = '5')),
    ((SELECT id_utilisateur FROM Utilisateur WHERE login_utilisateur = 'isabelleblanc'), (SELECT id_annee FROM Annee_Scolaire WHERE annee_debut = 2024 AND annee_fin = 2027 AND semestre = '1'));

