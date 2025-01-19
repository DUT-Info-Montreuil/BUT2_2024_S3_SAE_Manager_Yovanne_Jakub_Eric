<?php

include_once 'Connexion.php';

class ModeleDashboard extends Connexion
{
    public function getProjectData($idProjet)
    {
        $bdd = $this->getBdd();
        $stmt = $bdd->prepare("
        SELECT p.*, CONCAT(u.prenom, ' ', u.nom) AS responsable_projet, u.email AS responsable_email
        FROM Projet p
        LEFT JOIN Gerant g ON p.id_projet = g.id_projet
        LEFT JOIN Utilisateur u ON g.id_utilisateur = u.id_utilisateur
        WHERE p.id_projet = ? AND g.role_utilisateur = 'Responsable'
    ");
        $stmt->execute([$idProjet]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getGroupsForProject($idProjet)
    {
        $bdd=$this->getBdd();
        $stmt = $bdd->prepare("
            SELECT g.id_groupe, g.nom, g.image_titre, g.modifiable_par_groupe 
            FROM Groupe g
            JOIN Projet_Groupe pg ON g.id_groupe = pg.id_groupe
            WHERE pg.id_projet = ?");
        $stmt->execute([$idProjet]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEvaluationsForProject($idProjet)
    {
        $bdd = $this->getBdd();
        $stmt = $bdd->prepare("
        SELECT 
            e.id_evaluation, 
            e.coefficient, 
            e.note_max, 
            e.type_evaluation,
            r.titre AS titre_rendu,
            s.titre AS titre_soutenance
        FROM Evaluation e
        LEFT JOIN Rendu r ON e.id_evaluation = r.id_evaluation
        LEFT JOIN Soutenance s ON e.id_evaluation = s.id_evaluation
        WHERE r.id_projet = ? OR s.id_projet = ?
    ");
        $stmt->execute([$idProjet, $idProjet]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getResourcesForProject($idProjet)
    {
        $bdd=$this->getBdd();
        $stmt = $bdd->prepare("SELECT * FROM Ressource WHERE id_projet = ?");
        $stmt->execute([$idProjet]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSoutenanceProjet($idProjet)
    {
        $bdd=$this->getBdd();
        $stmt = $bdd->prepare("
            SELECT s.id_soutenance, s.titre, s.date_soutenance
            FROM Soutenance s
            WHERE s.id_projet = ?");
        $stmt->execute([$idProjet]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRenduProjet($idProjet)
    {
        $bdd = $this->getBdd();
        $stmt = $bdd->prepare("
        SELECT r.id_rendu, r.titre, r.date_limite
        FROM Rendu r
        WHERE r.id_projet = ?");
        $stmt->execute([$idProjet]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getMembersForGroup($idGroupe)
    {
        $bdd = $this->getBdd();
        $stmt = $bdd->prepare("
        SELECT u.id_utilisateur, u.nom, u.prenom, u.email
        FROM Utilisateur u
        JOIN Groupe_Etudiant ge ON u.id_utilisateur = ge.id_utilisateur
        WHERE ge.id_groupe = ?");
        $stmt->execute([$idGroupe]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNotesForGroup($idGroupe)
    {
        $bdd = $this->getBdd();
        $stmt = $bdd->prepare("
        SELECT e.type_evaluation, ae.note, r.titre AS nom_rendu, e.note_max,
               u.prenom, u.nom AS nom_etudiant, s.titre AS nom_soutenance
        FROM Activite_Evaluation ae
        JOIN Evaluation e ON ae.id_evaluation = e.id_evaluation
        LEFT JOIN Rendu r ON e.id_evaluation = r.id_evaluation
        JOIN Utilisateur u ON ae.id_etudiant = u.id_utilisateur
        LEFT JOIN Soutenance s ON e.id_evaluation = s.id_evaluation 
        WHERE ae.id_groupe = ?
    ");
        $stmt->execute([$idGroupe]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}


