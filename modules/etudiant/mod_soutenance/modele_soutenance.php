<?php

include_once 'Connexion.php';
Class ModeleSoutenance extends Connexion
{
    public function __construct()
    {
    }

    public function getAllSoutenances($idProjet)
    {
        $bdd = $this->getBdd();

        $sql = "SELECT s.id_soutenance, s.titre, s.date_soutenance, s.id_projet, s.id_evaluation, 
                       e.coefficient, e.note_max
                FROM Soutenance s
                LEFT JOIN Evaluation e ON s.id_evaluation = e.id_evaluation
                WHERE s.id_projet = ?";

        $stmt = $bdd->prepare($sql);
        $stmt->execute([$idProjet]);
        $soutenances = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($soutenances) {
            return $soutenances;
        } else {
            return [];
        }
    }
}