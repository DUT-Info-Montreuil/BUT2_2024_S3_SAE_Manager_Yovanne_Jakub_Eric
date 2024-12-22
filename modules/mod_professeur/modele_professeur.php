<?php
include_once 'Connexion.php';
Class ModeleProfesseur extends Connexion{
    public function __construct() {
    }


    public function utilisateurExiste($login){
        $bdd = $this->getBdd();
        $stmt = $bdd->prepare("SELECT * FROM Utilisateur WHERE login_utilisateur = ?");
        $stmt->execute([$login]);
        $user = $stmt->fetch();
        if($user){
            return true;
        }
        return false;
    }
    public function saeGerer($id_utilisateur) {
        $bdd = $this->getBdd();
        $stmt = $bdd->prepare("SELECT * FROM Projet INNER JOIN Gerant ON Projet.id_projet = Gerant.id_projet WHERE id_utilisateur = ?");
        $stmt->execute([$id_utilisateur]);
        $sae = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $sae;
    }

    public function ajouterProjet($titre, $annee, $description, $semestre) {
        $bdd = $this->getBdd();
        $stmt = $bdd->prepare("INSERT INTO Projet (id_projet, titre, annee_universitaire, description_projet, semestre) VALUES (DEFAULT, ?, ?, ?, ?)");
        $stmt->execute([$titre, $annee, $description, $semestre]);

        $idProjet = $bdd->lastInsertId();
        $insertionGerant = $bdd->prepare("INSERT INTO Gerant (id_projet, id_utilisateur, role_utilisateur) VALUES (?, ?, ?)");

        $insertionGerant->execute([$idProjet, $_SESSION['id_utilisateur'], 'Responsable']);
    }

    public function getSaeDetails($idProjet) {
        $bdd = $this->getBdd();
        $stmt = $bdd->prepare("SELECT * FROM Projet WHERE id_projet = ?");
        $stmt->execute([$idProjet]);
        $saeDetails = $stmt->fetch(PDO::FETCH_ASSOC);
        return $saeDetails;
    }

    public function modifierInfoGeneralSae($idSae, $titre, $annee, $semestre, $description) {
        $bdd = $this->getBdd();
        $stmt = $bdd->prepare("UPDATE Projet 
                           SET titre = ?, annee_universitaire = ?, semestre = ?, description_projet = ? 
                           WHERE id_projet = ?");
        $stmt->execute([$titre, $annee, $semestre, $description, $idSae]);
    }

    public function getSaeGroupe($idSae){
        $bdd = $this->getBdd();
        $stmt = $bdd->prepare("SELECT 
                            g.nom AS nom_groupe,
                            g.id_groupe AS id_groupe,
                            u.nom AS nom_membre,
                            u.prenom AS prenom_membre
                        FROM 
                            Projet_Groupe pg
                        INNER JOIN 
                            Groupe g ON pg.id_groupe = g.id_groupe
                        INNER JOIN 
                            Groupe_Etudiant ge ON g.id_groupe = ge.id_groupe
                        INNER JOIN 
                            Utilisateur u ON ge.id_utilisateur = u.id_utilisateur
                        WHERE 
                            pg.id_projet = ?");
        $stmt->execute([$idSae]);
        $groupes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $groupes;
    }

    public function getEtudiants() {
        $bdd = $this->getBdd();
        $query = "SELECT login_utilisateur, id_utilisateur, CONCAT(prenom, ' ', nom) AS nom_complet FROM Utilisateur WHERE type_utilisateur = 'etudiant'";
        $stmt = $bdd->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ajouterGroupe($nomGroupe){
        $bdd = $this->getBdd();
        $query = "INSERT INTO Groupe (id_groupe, nom, image_titre, modifiable_par_groupe) VALUES (DEFAULT, ?, 'jsp', 'non')";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$nomGroupe]);

        return $bdd->lastInsertId();
    }

    public function lieeProjetGrp($idGroupe, $idSae){
        $bdd = $this->getBdd();
        $projetgrp = "INSERT INTO projet_groupe (id_groupe, id_projet) VALUES (?, ?)";
        $stmt2 = $bdd->prepare($projetgrp);
        $stmt2->execute([$idGroupe, $idSae]);
    }
    public function ajouterEtudiantAuGroupe($idGroupe, $idEtudiant) {
        $bdd = $this->getBdd();
        $query = "INSERT INTO groupe_etudiant (id_utilisateur, id_groupe) VALUES (?, ?)";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idEtudiant, $idGroupe]);

    }



}