<?php

include_once 'Connexion.php';
Class ModeleGroupeProf extends Connexion{

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
    public function getEtudiantsSansGroupe($idSae) {
        $bdd = $this->getBdd();
        $query = "
        SELECT 
            login_utilisateur, 
            id_utilisateur, 
            CONCAT(prenom, ' ', nom) AS nom_complet 
        FROM 
            Utilisateur 
        WHERE 
            type_utilisateur = 'etudiant' 
            AND id_utilisateur NOT IN (
                SELECT ge.id_utilisateur 
                FROM Groupe_Etudiant ge
                JOIN Projet_Groupe pg ON ge.id_groupe = pg.id_groupe
                WHERE pg.id_projet = ?
            )
    ";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idSae]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function ajouterGroupe($nomGroupe, $idProjet)
    {
        $bdd = $this->getBdd();

        try {
            $bdd->beginTransaction();

            $idGroupe = $this->ajouterGroupeDansBase($bdd, $nomGroupe);
            $this->lierRendusAuGroupe($bdd, $idProjet, $idGroupe);
            $this->lierSoutenancesAuGroupe($bdd, $idProjet, $idGroupe);
            $bdd->commit();

            return $idGroupe;
        } catch (Exception $e) {
            $bdd->rollBack();
            throw new Exception("erreur pdt ajout du groupe : " . $e->getMessage());
        }
    }

    private function ajouterGroupeDansBase($bdd, $nomGroupe)
    {
        $query = "INSERT INTO Groupe (id_groupe, nom, image_titre, modifiable_par_groupe) VALUES (DEFAULT, ?, NULL, FALSE)";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$nomGroupe]);
        return $bdd->lastInsertId();
    }
    private function lierRendusAuGroupe($bdd, $idProjet, $idGroupe)
    {
        $query = "SELECT id_rendu FROM Rendu WHERE id_projet = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idProjet]);
        $rendus = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($rendus)) {
            $query = "INSERT INTO Rendu_Groupe (id_rendu, id_groupe, statut) VALUES (?, ?, 'En attente')";
            $stmt = $bdd->prepare($query);
            foreach ($rendus as $rendu) {
                $stmt->execute([$rendu['id_rendu'], $idGroupe]);
            }
        }
    }
    private function lierSoutenancesAuGroupe($bdd, $idProjet, $idGroupe)
    {
        $query = "SELECT id_soutenance FROM Soutenance WHERE id_projet = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idProjet]);
        $soutenances = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($soutenances)) {
            $query = "INSERT INTO Soutenance_Groupe (id_soutenance, id_groupe) VALUES (?, ?)";
            $stmt = $bdd->prepare($query);
            foreach ($soutenances as $soutenance) {
                $stmt->execute([$soutenance['id_soutenance'], $idGroupe]);
            }
        }
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
    public function modifierModifiableParGroupe($modifiable, $idGroupe) {
        $bdd = $this->getBdd();
        $query = "UPDATE Groupe SET modifiable_par_groupe = ? WHERE id_groupe = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$modifiable, $idGroupe]);
    }

    public function supprimerEtudiantDuGroupe($idGroupe, $idUtilisateur) {
        $bdd = $this->getBdd();
        $query = "DELETE FROM Groupe_Etudiant WHERE id_groupe = :id_groupe AND id_utilisateur = :id_utilisateur";
        $stmt = $bdd->prepare($query);
        $stmt->bindParam(':id_groupe', $idGroupe, PDO::PARAM_INT);
        $stmt->bindParam(':id_utilisateur', $idUtilisateur, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function modifierNomGrp($idGroupe, $nomGroupe){
        $bdd = $this->getBdd();
        $query = "UPDATE Groupe SET nom = ? WHERE id_groupe = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$nomGroupe, $idGroupe]);
    }

    public function supprimerGroupe($idGroupe){
        $bdd = $this->getBdd();
        $query = "DELETE FROM Groupe WHERE id_groupe = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idGroupe]);
    }

    public function getNomGroupe($idGroupe){
        $bdd = $this->getBdd();
        $query = "SELECT nom FROM Groupe WHERE id_groupe = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idGroupe]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['nom'];
    }



}
