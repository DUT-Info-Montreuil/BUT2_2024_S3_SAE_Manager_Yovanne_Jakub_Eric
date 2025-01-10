<?php
include_once 'Connexion.php';

class ModeleEvaluationSoutenance extends Connexion
{
    public function __construct()
    {

    }

    public function getEvaluationByIdSoutenance($id_soutenance){
        $bdd = $this->getBdd();
        $query = "SELECT id_evaluation FROM Soutenance WHERE id_soutenance = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$id_soutenance]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result && isset($result['id_evaluation'])) {
            return $result['id_evaluation'];
        }
        return null;
    }

    public function getIdEvaluationBySoutenance($id_soutenance){
        $bdd = $this->getBdd();
        $query = "SELECT id_evaluation FROM Soutenance WHERE id_soutenance = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$id_soutenance]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result && isset($result['id_evaluation'])) {
            return $result['id_evaluation'];
        } else {
            return null;
        }
    }

    public function infNoteMaxSoutenance($id_evaluation)
    {
        $bdd = $this->getBdd();

        $query = "
        SELECT e.note_max
        FROM Soutenance s
        JOIN Evaluation e ON s.id_evaluation = e.id_evaluation
        WHERE s.id_evaluation = ?
    ";

        $stmt = $bdd->prepare($query);
        $stmt->execute([$id_evaluation]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result && isset($result['note_max'])) {
            return $result['note_max'];
        } else {
            return null;
        }
    }

    public function getSoutenanceEvaluation($idSae, $id_soutenance)
    {
        $bdd = self::getBdd();
        $query = "
    SELECT 
        g.nom AS groupe_nom,
        s.titre AS soutenance_titre,
        s.date_soutenance AS soutenance_date,
        GROUP_CONCAT(
            CONCAT(u.nom, ' ', u.prenom, ' : ', COALESCE(se.note, 'Non noté'))
            SEPARATOR '\n'
        ) AS notes_individuelles,
        e.note_max AS note_max,
        e.coefficient AS note_coef,
        sg.id_soutenance,
        s.id_evaluation,
        se.note AS soutenance_note,
        sg.id_groupe
    FROM Soutenance s
    INNER JOIN Soutenance_Groupe sg ON s.id_soutenance = sg.id_soutenance
    INNER JOIN Groupe g ON sg.id_groupe = g.id_groupe
    INNER JOIN Groupe_Etudiant ge ON g.id_groupe = ge.id_groupe
    INNER JOIN Utilisateur u ON ge.id_utilisateur = u.id_utilisateur
    LEFT JOIN Soutenance_Evaluation se 
        ON sg.id_soutenance = se.id_soutenance 
        AND sg.id_groupe = se.id_groupe 
        AND se.id_etudiant = u.id_utilisateur
    LEFT JOIN Evaluation e ON s.id_evaluation = e.id_evaluation
    WHERE s.id_projet = ? AND s.id_soutenance = ? AND s.id_evaluation IS NOT NULL
    GROUP BY sg.id_soutenance, sg.id_groupe, s.id_evaluation
    ORDER BY g.nom, s.date_soutenance;
    ";

        $stmt = $bdd->prepare($query);
        $stmt->execute([$idSae, $id_soutenance]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSoutenanceEvaluationGerer($idSae, $id_soutenance, $idEvaluateur)
    {
        $bdd = self::getBdd();
        $query = "
        SELECT 
            g.nom AS groupe_nom,
            s.titre AS soutenance_titre,
            s.date_soutenance AS soutenance_date,
            GROUP_CONCAT(
                CONCAT(u.nom, ' ', u.prenom, ' : ', COALESCE(se.note, 'Non noté'))
                SEPARATOR '\n'
            ) AS notes_individuelles,
            e.note_max AS note_max,
            e.coefficient AS note_coef,
            sg.id_soutenance,
            s.id_evaluation,
            se.note AS soutenance_note,
            sg.id_groupe
        FROM Soutenance s
        INNER JOIN Soutenance_Groupe sg ON s.id_soutenance = sg.id_soutenance
        INNER JOIN Groupe g ON sg.id_groupe = g.id_groupe
        INNER JOIN Groupe_Etudiant ge ON g.id_groupe = ge.id_groupe
        INNER JOIN Utilisateur u ON ge.id_utilisateur = u.id_utilisateur
        LEFT JOIN Soutenance_Evaluation se 
            ON sg.id_soutenance = se.id_soutenance 
            AND sg.id_groupe = se.id_groupe 
            AND se.id_etudiant = u.id_utilisateur
        LEFT JOIN Evaluation e ON s.id_evaluation = e.id_evaluation
        LEFT JOIN Evaluation_Evaluateur ee ON e.id_evaluation = ee.id_evaluation
        WHERE s.id_projet = ? 
            AND s.id_soutenance = ? 
            AND s.id_evaluation IS NOT NULL 
            AND ee.id_utilisateur = ?
        GROUP BY sg.id_soutenance, sg.id_groupe, s.id_evaluation
        ORDER BY g.nom, s.date_soutenance;
    ";

        $stmt = $bdd->prepare($query);
        $stmt->execute([$idSae, $id_soutenance, $idEvaluateur]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllSoutenanceSAE($idSae)
    {
        $bdd = self::getBdd();
        $query = "
    SELECT S.*, EE.id_utilisateur AS id_evaluateur
    FROM Soutenance S
    LEFT JOIN Evaluation E ON S.id_evaluation = E.id_evaluation
    LEFT JOIN Evaluation_Evaluateur EE ON E.id_evaluation = EE.id_evaluation AND EE.is_principal = 1
    WHERE S.id_projet = ?";

        $stmt = $bdd->prepare($query);
        $stmt->execute([$idSae]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function creerEvaluationPourSoutenance($id_soutenance, $coefficient, $note_max, $evaluateur)
    {
        $bdd = self::getBdd();
        $query = "
        INSERT INTO Evaluation (id_evaluation, coefficient, note_max)
        VALUES (DEFAULT, ?, ?)
    ";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$coefficient, $note_max]);

        $id_evaluation = $bdd->lastInsertId();
        $queryLink = "UPDATE Soutenance SET id_evaluation = ? WHERE id_soutenance = ?";

        $stmtLink = $bdd->prepare($queryLink);
        $stmtLink->execute([$id_evaluation, $id_soutenance]);

        return $id_evaluation;
    }
    public function sauvegarderNoteSoutenance($idUtilisateur, $note, $id_soutenance, $id_groupe, $isIndividualEvaluation, $id_evaluation, $idEvaluateur, $commentaire)
    {
        $bdd = $this->getBdd();
        $insertQuery = "
                        INSERT INTO Soutenance_Evaluation (id_evaluation, id_soutenance, id_groupe, id_etudiant, id_evaluateur, isIndividualEvaluation, note, commentaire)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                    ";
        $insertStmt = $bdd->prepare($insertQuery);
        $insertStmt->execute([$id_evaluation, $id_soutenance, $id_groupe, $idUtilisateur, $idEvaluateur, $isIndividualEvaluation, $note, $commentaire]);
    }

    public function modifierEvaluationSoutenance($id_evaluation, $id_groupe, $id_etudiant, $note)
    {
        $bdd = $this->getBdd();
        $query = "UPDATE Soutenance_Evaluation
              SET note = ?
              WHERE id_evaluation = ?
              AND id_groupe = ?
              AND id_etudiant = ?";

        $stmt = $bdd->prepare($query);
        $stmt->execute([$note, $id_evaluation, $id_groupe, $id_etudiant]);
        $stmt->execute();
    }

    public function getNotesParEvaluationSoutenance($id_groupe, $id_evaluation)
    {
        $bdd = $this->getBdd();
        $query = "
        SELECT 
            s.titre, u.nom, u.prenom, se.note, u.id_utilisateur, u.email
        FROM Soutenance_Groupe sg
        INNER JOIN Soutenance s ON sg.id_soutenance = s.id_soutenance
        INNER JOIN Groupe g ON sg.id_groupe = g.id_groupe
        INNER JOIN Groupe_Etudiant ge ON g.id_groupe = ge.id_groupe
        INNER JOIN Utilisateur u ON ge.id_utilisateur = u.id_utilisateur
        LEFT JOIN Soutenance_Evaluation se ON sg.id_soutenance = se.id_soutenance
            AND sg.id_groupe = se.id_groupe
            AND se.id_etudiant = u.id_utilisateur
        WHERE sg.id_groupe = ? AND s.id_evaluation = ?
    ";

        $stmt = $bdd->prepare($query);
        $stmt->execute([$id_groupe, $id_evaluation]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}