<?php

include_once 'Connexion.php';

class ModeleDashboard extends Connexion
{
    public function getProjectData($idProjet)
    {
        $bdd=$this->getBdd();
        $stmt = $bdd->prepare("SELECT * FROM Projet WHERE id_projet = ?");
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
        $bdd=$this->getBdd();
        $stmt =$bdd->prepare("
            SELECT e.id_evaluation, e.coefficient, e.note_max, e.type_evaluation 
            FROM Evaluation e
            JOIN Rendu r ON e.id_evaluation = r.id_evaluation
            WHERE r.id_projet = ?");
        $stmt->execute([$idProjet]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getResourcesForProject($idProjet)
    {
        $bdd=$this->getBdd();
        $stmt = $bdd->prepare("SELECT * FROM Ressource WHERE id_projet = ?");
        $stmt->execute([$idProjet]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDefensesForProject($idProjet)
    {
        $bdd=$this->getBdd();
        $stmt = $bdd->prepare("
            SELECT s.id_soutenance, s.titre, s.date_soutenance, sg.heure_passage
            FROM Soutenance s
            JOIN Soutenance_Groupe sg ON s.id_soutenance = sg.id_soutenance
            WHERE s.id_projet = ?");
        $stmt->execute([$idProjet]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


