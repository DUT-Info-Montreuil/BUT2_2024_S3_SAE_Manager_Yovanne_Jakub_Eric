CREATE TABLE Utilisateur (
                             id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
                             nom VARCHAR(50) NOT NULL,
                             prenom VARCHAR(50) NOT NULL,
                             email VARCHAR(50) NOT NULL,
                             type_utilisateur ENUM('admin', 'professeur', 'etudiant', 'intervenant'),
                             login_utilisateur VARCHAR(200) NOT NULL,
                             password_utilisateur VARCHAR(200) NOT NULL,
                             profil_picture VARCHAR(500)
);

CREATE TABLE Projet (
                        id_projet INT AUTO_INCREMENT PRIMARY KEY,
                        titre VARCHAR(50) NOT NULL,
                        annee_universitaire VARCHAR(4) NOT NULL,
                        description_projet VARCHAR(400),
                        semestre INT NOT NULL
);

CREATE TABLE Groupe (
                        id_groupe INT AUTO_INCREMENT PRIMARY KEY,
                        nom VARCHAR(50) NOT NULL,
                        image_titre VARCHAR(50),
                        modifiable_par_groupe BOOLEAN NOT NULL
);

CREATE TABLE Champ (
                       id_champ INT AUTO_INCREMENT PRIMARY KEY,
                       id_projet INT NOT NULL,
                       champ_nom VARCHAR(50) NOT NULL,
                       rempli_par ENUM('Responsable', 'Groupe') NOT NULL,
                       FOREIGN KEY (id_projet) REFERENCES Projet(id_projet) ON DELETE CASCADE
);

CREATE TABLE Champ_Groupe (
                              id_champ INT NOT NULL,
                              id_groupe INT NOT NULL,
                              champ_valeur VARCHAR(400),
                              PRIMARY KEY (id_champ, id_groupe),
                              FOREIGN KEY (id_champ) REFERENCES Champ(id_champ) ON DELETE CASCADE,
                              FOREIGN KEY (id_groupe) REFERENCES Groupe(id_groupe) ON DELETE CASCADE
);

CREATE TABLE Gerant (
                        id_projet INT NOT NULL,
                        id_utilisateur INT NOT NULL,
                        role_utilisateur ENUM('Responsable', 'Co-Responsable', 'Intervenant'),
                        PRIMARY KEY (id_projet, id_utilisateur),
                        FOREIGN KEY (id_projet) REFERENCES Projet(id_projet) ON DELETE CASCADE,
                        FOREIGN KEY (id_utilisateur) REFERENCES Utilisateur(id_utilisateur) ON DELETE CASCADE
);

CREATE TABLE Projet_Groupe (
                               id_projet INT NOT NULL,
                               id_groupe INT NOT NULL,
                               PRIMARY KEY (id_projet, id_groupe),
                               FOREIGN KEY (id_projet) REFERENCES Projet(id_projet) ON DELETE CASCADE,
                               FOREIGN KEY (id_groupe) REFERENCES Groupe(id_groupe) ON DELETE CASCADE
);

CREATE TABLE Groupe_Etudiant (
                                 id_utilisateur INT NOT NULL,
                                 id_groupe INT NOT NULL,
                                 note_finale FLOAT,
                                 PRIMARY KEY (id_utilisateur, id_groupe),
                                 FOREIGN KEY (id_utilisateur) REFERENCES Utilisateur(id_utilisateur) ON DELETE CASCADE,
                                 FOREIGN KEY (id_groupe) REFERENCES Groupe(id_groupe) ON DELETE CASCADE
);

CREATE TABLE Ressource (
                           id_ressource INT AUTO_INCREMENT PRIMARY KEY,
                           titre VARCHAR(50) NOT NULL,
                           lien VARCHAR(200) NOT NULL,
                           mise_en_avant BOOLEAN NOT NULL,
                           id_projet INT NOT NULL,
                           FOREIGN KEY (id_projet) REFERENCES Projet(id_projet) ON DELETE CASCADE
);

CREATE TABLE Evaluation (
                            id_evaluation INT AUTO_INCREMENT PRIMARY KEY,
                            coefficient FLOAT NOT NULL,
                            note_max FLOAT NOT NULL,
                            type_evaluation ENUM('Rendu', 'Soutenance')
);

CREATE TABLE Evaluation_Evaluateur (
                                       id_evaluation INT NOT NULL,
                                       id_utilisateur INT NOT NULL,
                                       is_principal BOOLEAN NOT NULL DEFAULT FALSE,
                                       PRIMARY KEY (id_evaluation, id_utilisateur),
                                       FOREIGN KEY (id_evaluation) REFERENCES Evaluation(id_evaluation) ON DELETE CASCADE,
                                       FOREIGN KEY (id_utilisateur) REFERENCES Utilisateur(id_utilisateur) ON DELETE CASCADE
);

CREATE TABLE Rendu (
                       id_rendu INT AUTO_INCREMENT PRIMARY KEY,
                       titre VARCHAR(50) NOT NULL,
                       date_limite DATE NOT NULL,
                       id_projet INT NOT NULL,
                       id_evaluation INT,
                       FOREIGN KEY (id_projet) REFERENCES Projet(id_projet) ON DELETE CASCADE,
                       FOREIGN KEY (id_evaluation) REFERENCES Evaluation(id_evaluation) ON DELETE SET NULL
);

CREATE TABLE Rendu_Groupe (
                              id_rendu INT NOT NULL,
                              id_groupe INT NOT NULL,
                              date_limite DATE NOT NULL,
                              statut ENUM('Remis', 'En retard', 'En attente') NOT NULL,
                              id_auteur INT,
                              date_remise timestamp,
                              PRIMARY KEY (id_rendu, id_groupe),
                              FOREIGN KEY (id_rendu) REFERENCES Rendu(id_rendu) ON DELETE CASCADE,
                              FOREIGN KEY (id_groupe) REFERENCES Groupe(id_groupe) ON DELETE CASCADE,
                              FOREIGN KEY (id_auteur) REFERENCES Utilisateur(id_utilisateur)
);

CREATE TABLE Rendu_Fichier (
                               id_fichier INT AUTO_INCREMENT PRIMARY KEY,
                               id_rendu INT NOT NULL,
                               id_groupe INT NOT NULL,
                               nom_fichier VARCHAR(255) NOT NULL,
                               chemin_fichier VARCHAR(500) NOT NULL,
                               date_remise TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                               FOREIGN KEY (id_rendu) REFERENCES Rendu(id_rendu) ON DELETE CASCADE,
                               FOREIGN KEY (id_groupe) REFERENCES Groupe(id_groupe) ON DELETE CASCADE
);

CREATE TABLE Soutenance (
                            id_soutenance INT AUTO_INCREMENT PRIMARY KEY,
                            id_projet INT NOT NULL,
                            titre VARCHAR(50) NOT NULL,
                            date_soutenance DATE NOT NULL,
                            id_evaluation INT,
                            FOREIGN KEY (id_projet) REFERENCES Projet(id_projet) ON DELETE CASCADE,
                            FOREIGN KEY (id_evaluation) REFERENCES Evaluation(id_evaluation) ON DELETE CASCADE
);

CREATE TABLE Soutenance_Groupe (
                                   id_soutenance INT NOT NULL,
                                   id_groupe INT NOT NULL,
                                   heure_passage TIME NOT NULL,
                                   PRIMARY KEY (id_soutenance, id_groupe),
                                   FOREIGN KEY (id_soutenance) REFERENCES Soutenance(id_soutenance) ON DELETE CASCADE,
                                   FOREIGN KEY (id_groupe) REFERENCES Groupe(id_groupe) ON DELETE CASCADE
);


CREATE TABLE Activite_Evaluation (
                                     id_evaluation INT NOT NULL,
                                     id_groupe INT NOT NULL,
                                     id_etudiant INT NOT NULL,
                                     id_evaluateur INT NOT NULL,
                                     commentaire VARCHAR(2000),
                                     note FLOAT NOT NULL,
                                     PRIMARY KEY (id_evaluation, id_groupe, id_etudiant),
                                     FOREIGN KEY (id_evaluation) REFERENCES Evaluation(id_evaluation) ON DELETE CASCADE,
                                     FOREIGN KEY (id_groupe) REFERENCES Groupe(id_groupe) ON DELETE CASCADE,
                                     FOREIGN KEY (id_etudiant) REFERENCES Utilisateur(id_utilisateur) ON DELETE CASCADE,
                                     FOREIGN KEY (id_evaluateur) REFERENCES Evaluation_Evaluateur(id_utilisateur) ON DELETE CASCADE
);


CREATE TABLE Critere (
                         id_critere INT AUTO_INCREMENT PRIMARY KEY,
                         nom_critere VARCHAR(100) NOT NULL,
                         description TEXT,
                         coefficient FLOAT NOT NULL,
                         note_max FLOAT NOT NULL,
                         id_evaluation INT NOT NULL,
                         FOREIGN KEY (id_evaluation) REFERENCES Evaluation(id_evaluation) ON DELETE CASCADE
);

CREATE TABLE Critere_Notation(
                                 id_critere INT NOT NULL,
                                 id_groupe INT NOT NULL,
                                 id_etudiant INT NOT NULL,
                                 note FLOAT NOT NULL,
                                 PRIMARY KEY (id_critere, id_groupe, id_etudiant),
                                 FOREIGN KEY (id_critere) REFERENCES Critere(id_critere) ON DELETE CASCADE,
                                 FOREIGN KEY (id_groupe) REFERENCES Groupe(id_groupe) ON DELETE CASCADE,
                                 FOREIGN KEY (id_etudiant) REFERENCES Utilisateur(id_utilisateur) ON DELETE CASCADE
);

CREATE TABLE Annee_Scolaire (
                                id_annee INT AUTO_INCREMENT PRIMARY KEY,
                                annee_debut YEAR NOT NULL,
                                annee_fin YEAR NOT NULL,
                                semestre ENUM('1', '2', '3', '4', '5', '6') NOT NULL
);

CREATE TABLE Etudiant_Annee (
                                id_utilisateur INT NOT NULL,
                                id_annee INT NOT NULL,
                                PRIMARY KEY (id_utilisateur, id_annee),
                                FOREIGN KEY (id_utilisateur) REFERENCES Utilisateur(id_utilisateur) ON DELETE CASCADE,
                                FOREIGN KEY (id_annee) REFERENCES Annee_Scolaire(id_annee) ON DELETE CASCADE
);


