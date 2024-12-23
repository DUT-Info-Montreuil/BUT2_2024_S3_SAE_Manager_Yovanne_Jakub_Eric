<?php

include_once 'Connexion.php';
Class ModeleGroupe extends Connexion{

    public function __construct() {
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
        $query = "INSERT INTO Groupe (id_groupe, nom, image_titre, modifiable_par_groupe) VALUES (DEFAULT, ?, 'jsp', FALSE)";
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

    public function getGroupeById($idGroupe) {
        $bdd = $this->getBdd();
        $requete = $bdd->prepare("
            SELECT 
                g.id_groupe, 
                g.nom AS nom_groupe, 
                g.image_titre, 
                g.modifiable_par_groupe,
                u.id_utilisateur,
                u.nom AS nom_membre,
                u.prenom AS prenom_membre,
                u.email
            FROM 
                Groupe g
            LEFT JOIN 
                Groupe_Etudiant ge ON g.id_groupe = ge.id_groupe
            LEFT JOIN 
                Utilisateur u ON ge.id_utilisateur = u.id_utilisateur
            WHERE 
                g.id_groupe = :id_groupe
        ");

        $requete->execute(['id_groupe' => $idGroupe]);

        $resultats = $requete->fetchAll(PDO::FETCH_ASSOC);

        if (empty($resultats)) {
            return null;
        }

        $detailsGroupe = [
            'id_groupe' => $resultats[0]['id_groupe'],
            'nom_groupe' => $resultats[0]['nom_groupe'],
            'image_titre' => $resultats[0]['image_titre'],
            'modifiable_par_groupe' => $resultats[0]['modifiable_par_groupe'],
            'membres' => []
        ];

        foreach ($resultats as $row) {
            if ($row['id_utilisateur'] !== null) {
                $detailsGroupe['membres'][] = [
                    'id_utilisateur' => $row['id_utilisateur'],
                    'nom' => $row['nom_membre'],
                    'prenom' => $row['prenom_membre'],
                    'email' => $row['email']
                ];
            }
        }

        return $detailsGroupe;
    }

    public function ajouterNouveauMembre($idGroupe) {
        $bdd = $this->getBdd();
        $query = "
        SELECT u.login_utilisateur, u.id_utilisateur, CONCAT(u.prenom, ' ', u.nom) AS nom_complet
        FROM Utilisateur u
        WHERE u.type_utilisateur = 'etudiant'
        AND u.id_utilisateur NOT IN (
            SELECT ge.id_utilisateur
            FROM Groupe_Etudiant ge
            WHERE ge.id_groupe = :idGroupe
        )
    ";

        $stmt = $bdd->prepare($query);
        $stmt->bindParam(':idGroupe', $idGroupe, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function supprimerEtudiantDuGroupe($idGroupe, $idUtilisateur) {
        $bdd = $this->getBdd();
        $query = "DELETE FROM Groupe_Etudiant WHERE id_groupe = :id_groupe AND id_utilisateur = :id_utilisateur";
        $stmt = $bdd->prepare($query);
        $stmt->bindParam(':id_groupe', $idGroupe, PDO::PARAM_INT);
        $stmt->bindParam(':id_utilisateur', $idUtilisateur, PDO::PARAM_INT);
        $stmt->execute();
    }

}
