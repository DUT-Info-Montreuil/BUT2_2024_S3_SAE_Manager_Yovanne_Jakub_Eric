<?php

include_once 'Connexion.php';

class ModeleNotesEtud extends Connexion
{
    public function __construct()
    {
    }

    public function getAllRendus($idUtilisateur, $idProjet)
    {
        $bdd = $this->getBdd();

        $query = "
    SELECT 
        e.id_evaluation,
        r.titre AS titre_rendu,
        re.note AS note_rendu,
        e.coefficient AS coefficient,
        e.note_max
    FROM 
        Utilisateur u
    LEFT JOIN 
        Groupe_Etudiant ge ON u.id_utilisateur = ge.id_utilisateur
    LEFT JOIN 
        Projet_Groupe pg ON ge.id_groupe = pg.id_groupe AND pg.id_projet = ?
    LEFT JOIN 
        Rendu_Evaluation re ON ge.id_groupe = re.id_groupe AND u.id_utilisateur = re.id_etudiant
    LEFT JOIN 
        Rendu r ON re.id_rendu = r.id_rendu
    LEFT JOIN 
        Evaluation e ON r.id_evaluation = e.id_evaluation
    WHERE 
        u.id_utilisateur = ? AND pg.id_projet = ?
    ";

        $stmt = $bdd->prepare($query);
        $stmt->execute([$idProjet, $idUtilisateur, $idProjet]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getAllSoutenances($idUtilisateur, $idProjet)
    {
        $bdd = $this->getBdd();

        $query = "
    SELECT 
        e.id_evaluation,
        s.titre AS titre_soutenance,
        se.note AS note_soutenance,
        e.coefficient AS coefficient,
        e.note_max
    FROM 
        Utilisateur u
    LEFT JOIN 
        Groupe_Etudiant ge ON u.id_utilisateur = ge.id_utilisateur
    LEFT JOIN 
        Projet_Groupe pg ON ge.id_groupe = pg.id_groupe AND pg.id_projet = ?
    LEFT JOIN 
        Soutenance_Evaluation se ON ge.id_groupe = se.id_groupe AND u.id_utilisateur = se.id_etudiant
    LEFT JOIN 
        Soutenance s ON se.id_soutenance = s.id_soutenance
    LEFT JOIN 
        Evaluation e ON s.id_evaluation = e.id_evaluation
    WHERE 
        u.id_utilisateur = ? AND pg.id_projet = ?
    ";

        $stmt = $bdd->prepare($query);
        $stmt->execute([$idProjet, $idUtilisateur, $idProjet]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }


    public function getAllNotesSAE($idUtilisateur, $idProjet)
    {
        $rendus = $this->getAllRendus($idUtilisateur, $idProjet);
        $soutenances = $this->getAllSoutenances($idUtilisateur, $idProjet);

        $notesRendus = [];

        foreach ($rendus as $rendu) {
            $note = [
                'titre_rendu' => $rendu['titre_rendu'],
                'note_rendu' => $rendu['note_rendu'],
                'coef_rendu' => $rendu['coefficient'],
                'note_max' => $rendu['note_max']
            ];

            $notesRendus[] = $note;
        }

        $notesSoutenances = [];

        foreach ($soutenances as $soutenance) {
            $note = [
                'titre_soutenance' => $soutenance['titre_soutenance'],
                'note_soutenance' => $soutenance['note_soutenance'],
                'coef_soutenance' => $soutenance['coefficient'],
                'note_max' => $soutenance['note_max']
            ];

            $notesSoutenances[] = $note;
        }

        return [
            'rendus' => $notesRendus,
            'soutenances' => $notesSoutenances
        ];
    }

    public function getNoteFinal($idUtilisateur, $idProjet)
    {
        $bdd = $this->getBdd();

        $query = "
        SELECT note_finale
        FROM Groupe_Etudiant ge
        LEFT JOIN Projet_Groupe pg ON ge.id_groupe = pg.id_groupe
        WHERE ge.id_utilisateur = ? AND pg.id_projet = ?
    ";

        $stmt = $bdd->prepare($query);
        $stmt->execute([$idUtilisateur, $idProjet]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result['note_finale'];
        } else {
            return null;
        }
    }
}