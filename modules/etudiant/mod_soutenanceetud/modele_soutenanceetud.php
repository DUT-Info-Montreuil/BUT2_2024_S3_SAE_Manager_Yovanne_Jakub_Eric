<?php

include_once 'Connexion.php';
Class ModeleSoutenanceEtud extends Connexion
{
    public function __construct()
    {
    }

    public function getAllSoutenances($idProjet, $id_groupe)
    {
        $bdd = $this->getBdd();
        $sql = "SELECT s.id_soutenance, s.titre, s.date_soutenance, s.id_projet, s.id_evaluation, 
                   e.coefficient, e.note_max, sg.heure_passage
            FROM Soutenance s
            LEFT JOIN Evaluation e ON s.id_evaluation = e.id_evaluation
            LEFT JOIN Soutenance_Groupe sg ON s.id_soutenance = sg.id_soutenance
            WHERE s.id_projet = ? AND sg.id_groupe = ?";

        $stmt = $bdd->prepare($sql);
        $stmt->execute([$idProjet, $id_groupe]);
        $soutenances = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($soutenances) {
            return $soutenances;
        } else {
            return [];
        }
    }


    public function getNoteEtCommentaire($idSoutenance, $idGroupe)
    {
        $bdd = $this->getBdd();

        $sql = "SELECT ae.note, ae.commentaire
            FROM Activite_Evaluation ae
            INNER JOIN Evaluation e ON ae.id_evaluation = e.id_evaluation
            INNER JOIN Soutenance s ON e.id_evaluation = s.id_evaluation
            WHERE s.id_soutenance = ? AND ae.id_groupe = ?
            LIMIT 1";

        $stmt = $bdd->prepare($sql);
        $stmt->execute([$idSoutenance, $idGroupe]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}