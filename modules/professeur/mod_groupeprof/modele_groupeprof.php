<?php

include_once 'Connexion.php';

class ModeleGroupeProf extends Connexion
{

    public function __construct()
    {

    }

    public function getGroupeDetails($idSae)
    {
        $bdd = $this->getBdd();
        $stmt = $bdd->prepare("SELECT 
                            g.nom AS nom_groupe,
                            g.id_groupe AS id_groupe,
                            u.nom AS nom_membre,
                            u.prenom AS prenom_membre,
                            c.champ_nom,
                            cg.champ_valeur
                        FROM 
                            Projet_Groupe pg
                        INNER JOIN 
                            Groupe g ON pg.id_groupe = g.id_groupe
                        INNER JOIN 
                            Groupe_Etudiant ge ON g.id_groupe = ge.id_groupe
                        INNER JOIN 
                            Utilisateur u ON ge.id_utilisateur = u.id_utilisateur
                        LEFT JOIN 
                            Champ_Groupe cg ON g.id_groupe = cg.id_groupe
                        LEFT JOIN 
                            Champ c ON cg.id_champ = c.id_champ
                        WHERE 
                            pg.id_projet = ?");
        $stmt->execute([$idSae]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $groupes = [];
        foreach ($result as $row) {
            $idGroupe = $row['id_groupe'];

            if (!isset($groupes[$idGroupe])) {
                $groupes[$idGroupe] = [
                    'nom_groupe' => $row['nom_groupe'],
                    'id_groupe' => $row['id_groupe'],
                    'membres' => [],
                    'champs' => []
                ];
            }

            $membreComplet = $row['prenom_membre'] . " " . $row['nom_membre'];
            if (!in_array($membreComplet, $groupes[$idGroupe]['membres'])) {
                $groupes[$idGroupe]['membres'][] = $membreComplet;
            }

            if (!empty($row['champ_nom']) && !in_array($row['champ_nom'] . ": " . $row['champ_valeur'], $groupes[$idGroupe]['champs'])) {
                $groupes[$idGroupe]['champs'][] = $row['champ_nom'] . ": " . $row['champ_valeur'];
            }
        }

        return $groupes;
    }

    public function getEtudiantsSansGroupe($idSae)
    {
        $bdd = $this->getBdd();

        $sql = "
    SELECT 
        u.login_utilisateur,
        u.id_utilisateur,
        CONCAT(u.prenom, ' ', u.nom) AS nom_complet,
        a.semestre
    FROM 
        Utilisateur u
    JOIN Etudiant_Annee ea ON u.id_utilisateur = ea.id_utilisateur
    JOIN Annee_Scolaire a ON ea.id_annee = a.id_annee
    JOIN Projet p ON p.semestre = a.semestre
    WHERE 
        u.type_utilisateur = 'etudiant' 
        AND u.id_utilisateur NOT IN (
            SELECT ge.id_utilisateur 
            FROM Groupe_Etudiant ge
            JOIN Projet_Groupe pg ON ge.id_groupe = pg.id_groupe
            WHERE pg.id_projet = ?
        )
        AND p.id_projet = ?";

        $stmt = $bdd->prepare($sql);
        $stmt->execute([$idSae, $idSae]);
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
            $this->lierChampAuGroupe($bdd, $idGroupe, $idProjet);
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

    private function lierChampAuGroupe($bdd, $idGroupe, $idProjet)
    {
        $query = "SELECT id_champ FROM Champ WHERE id_projet = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idProjet]);
        $champs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($champs)) {
            $query = "INSERT INTO Champ_Groupe (id_champ, id_groupe) VALUES (?, ?)";
            $stmt = $bdd->prepare($query);
            foreach ($champs as $champ) {
                $stmt->execute([$champ['id_champ'], $idGroupe]);
            }
        }
    }

    public function lieeProjetGrp($idGroupe, $idSae)
    {
        $bdd = $this->getBdd();
        $projetgrp = "INSERT INTO Projet_Groupe (id_groupe, id_projet) VALUES (?, ?)";
        $stmt2 = $bdd->prepare($projetgrp);
        $stmt2->execute([$idGroupe, $idSae]);
    }

    public function ajouterEtudiantAuGroupe($idGroupe, $idEtudiant)
    {
        $bdd = $this->getBdd();
        $query = "INSERT INTO Groupe_Etudiant (id_utilisateur, id_groupe) VALUES (?, ?)";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idEtudiant, $idGroupe]);
    }

    public function getGroupeInfoById($idGroupe)
    {
        $bdd = $this->getBdd();
        $stmt = $bdd->prepare("
    SELECT 
        g.nom AS nom_groupe,
        g.id_groupe AS id_groupe,
        g.modifiable_par_groupe,
        u.id_utilisateur,
        u.nom AS nom_membre,
        u.prenom AS prenom_membre,
        u.email,
        cg.champ_valeur,
        c.champ_nom,
        c.id_champ
    FROM 
        Groupe g
    LEFT JOIN 
        Groupe_Etudiant ge ON g.id_groupe = ge.id_groupe
    LEFT JOIN 
        Utilisateur u ON ge.id_utilisateur = u.id_utilisateur
    LEFT JOIN 
        Champ_Groupe cg ON g.id_groupe = cg.id_groupe
    LEFT JOIN 
        Champ c ON cg.id_champ = c.id_champ
    WHERE 
        g.id_groupe = ?");

        $stmt->execute([$idGroupe]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $detailsGroupe = [];
        $detailsGroupe['membres'] = [];
        $detailsGroupe['champs'] = [];

        $membresAjoutes = [];

        foreach ($result as $row) {
            $detailsGroupe['nom_groupe'] = $row['nom_groupe'];
            $detailsGroupe['id_groupe'] = $row['id_groupe'];
            $detailsGroupe['modifiable_par_groupe'] = $row['modifiable_par_groupe'];

            if (!isset($membresAjoutes[$row['id_utilisateur']])) {
                $detailsGroupe['membres'][] = [
                    'id_utilisateur' => $row['id_utilisateur'],
                    'prenom' => $row['prenom_membre'],
                    'nom' => $row['nom_membre'],
                    'email' => $row['email']
                ];

                $membresAjoutes[$row['id_utilisateur']] = true;
            }

            if ($row['champ_nom'] && !in_array($row['champ_nom'], array_column($detailsGroupe['champs'], 'champ_nom'))) {
                $detailsGroupe['champs'][] = [
                    'champ_nom' => $row['champ_nom'],
                    'champ_id' => $row['id_champ'],
                    'champ_valeur' => $row['champ_valeur']
                ];
            }
        }

        return $detailsGroupe;
    }

    public function modifierValeurChampGroupe($idGroupe, $idChamp, $champValeur) {
        $bdd = $this->getBdd();
        $query = "
        UPDATE Champ_Groupe
        SET champ_valeur = ?
        WHERE id_groupe = ? AND id_champ = ?
    ";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$champValeur, $idGroupe, $idChamp]);
    }


    public function modifierModifiableParGroupe($modifiable, $idGroupe)
    {
        $bdd = $this->getBdd();
        $query = "UPDATE Groupe SET modifiable_par_groupe = ? WHERE id_groupe = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$modifiable, $idGroupe]);
    }

    public function supprimerEtudiantDuGroupe($idGroupe, $idUtilisateur)
    {
        $bdd = $this->getBdd();
        $query = "DELETE FROM Groupe_Etudiant WHERE id_groupe = :id_groupe AND id_utilisateur = :id_utilisateur";
        $stmt = $bdd->prepare($query);
        $stmt->bindParam(':id_groupe', $idGroupe, PDO::PARAM_INT);
        $stmt->bindParam(':id_utilisateur', $idUtilisateur, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function modifierNomGrp($idGroupe, $nomGroupe)
    {
        $bdd = $this->getBdd();
        $query = "UPDATE Groupe SET nom = ? WHERE id_groupe = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$nomGroupe, $idGroupe]);
    }

    public function supprimerGroupe($idGroupe)
    {
        $bdd = $this->getBdd();
        $query = "DELETE FROM Groupe WHERE id_groupe = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idGroupe]);
    }

    public function getNomGroupe($idGroupe)
    {
        $bdd = $this->getBdd();
        $query = "SELECT nom FROM Groupe WHERE id_groupe = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idGroupe]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['nom'];
    }


}
