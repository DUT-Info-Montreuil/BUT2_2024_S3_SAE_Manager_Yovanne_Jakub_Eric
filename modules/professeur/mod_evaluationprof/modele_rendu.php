<?php
include_once 'Connexion.php';

class ModeleEvaluationRendu extends Connexion
{
    public function __construct()
    {

    }

    public function infNoteMaxRendu($id_evaluation)
    {
        $bdd = $this->getBdd();

        $query = "
        SELECT e.note_max
        FROM Rendu r
        JOIN Evaluation e ON r.id_evaluation = e.id_evaluation
        WHERE r.id_evaluation = ?
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

    public function getEvaluationByIdRendu($id_rendu){
        $bdd = $this->getBdd();
        $query = "SELECT id_evaluation FROM Rendu WHERE id_rendu = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$id_rendu]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result && isset($result['id_evaluation'])) {
            return $result['id_evaluation'];
        }
        return null;
    }

    public function getIdEvaluationByRendu($id_rendu){
        $bdd = $this->getBdd();
        $query = "SELECT id_evaluation FROM Rendu WHERE id_rendu = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$id_rendu]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result && isset($result['id_evaluation'])) {
            return $result['id_evaluation'];
        } else {
            return null;
        }
    }

    public function getRenduEvaluationGerer($idSae, $id_rendu, $idEvaluateur)
    {
        $bdd = $this->getBdd();

        $query = "
    SELECT 
        g.nom AS groupe_nom,
        r.titre AS rendu_titre,
        r.date_limite AS rendu_date_limite,
        rg.statut AS rendu_statut,
        re.note AS rendu_note,
        GROUP_CONCAT(
            CONCAT(u.nom, ' ', u.prenom, ' : ', COALESCE(re.note, 'Non noté'))
            SEPARATOR '\n'
        ) AS notes_individuelles,
        e.note_max AS note_max,
        e.coefficient AS note_coef,
        rg.id_rendu,
        rg.id_groupe,
        r.id_evaluation
    FROM Rendu r
    INNER JOIN Rendu_Groupe rg ON r.id_rendu = rg.id_rendu
    INNER JOIN Groupe g ON rg.id_groupe = g.id_groupe
    INNER JOIN Groupe_Etudiant ge ON g.id_groupe = ge.id_groupe
    INNER JOIN Utilisateur u ON ge.id_utilisateur = u.id_utilisateur
    LEFT JOIN Rendu_Evaluation re 
        ON rg.id_rendu = re.id_rendu 
        AND rg.id_groupe = re.id_groupe 
        AND re.id_etudiant = u.id_utilisateur
    LEFT JOIN Evaluation e ON r.id_evaluation = e.id_evaluation
    LEFT JOIN Evaluation_Evaluateur ee ON e.id_evaluation = ee.id_evaluation
    WHERE r.id_projet = ? 
        AND r.id_rendu = ? 
        AND r.id_evaluation IS NOT NULL 
        AND ee.id_utilisateur = ?  -- Vérification que l'évaluateur est bien assigné à l'évaluation
    GROUP BY rg.id_rendu, rg.id_groupe, r.id_evaluation
    ORDER BY g.nom, r.date_limite;
    ";

        $stmt = $bdd->prepare($query);
        $stmt->execute([$idSae, $id_rendu, $idEvaluateur]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRenduEvaluation($idSae, $id_rendu)
    {
        $bdd = $this->getBdd();

        $query = "
    SELECT 
        g.nom AS groupe_nom,
        r.titre AS rendu_titre,
        r.date_limite AS rendu_date_limite,
        rg.statut AS rendu_statut,
        re.note AS rendu_note,
        GROUP_CONCAT(
            CONCAT(u.nom, ' ', u.prenom, ' : ', COALESCE(re.note, 'Non noté'))
            SEPARATOR '\n'
        ) AS notes_individuelles,
        e.note_max AS note_max,
        e.coefficient AS note_coef,
        rg.id_rendu,
        rg.id_groupe,
        r.id_evaluation
    FROM Rendu r
    INNER JOIN Rendu_Groupe rg ON r.id_rendu = rg.id_rendu
    INNER JOIN Groupe g ON rg.id_groupe = g.id_groupe
    INNER JOIN Groupe_Etudiant ge ON g.id_groupe = ge.id_groupe
    INNER JOIN Utilisateur u ON ge.id_utilisateur = u.id_utilisateur
    LEFT JOIN Rendu_Evaluation re 
        ON rg.id_rendu = re.id_rendu 
        AND rg.id_groupe = re.id_groupe 
        AND re.id_etudiant = u.id_utilisateur
    LEFT JOIN Evaluation e ON r.id_evaluation = e.id_evaluation
    WHERE r.id_projet = ? AND r.id_rendu = ? AND r.id_evaluation IS NOT NULL
    GROUP BY rg.id_rendu, rg.id_groupe, r.id_evaluation
    ORDER BY g.nom, r.date_limite;
    ";

        $stmt = $bdd->prepare($query);
        $stmt->execute([$idSae, $id_rendu]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllRenduSAE($idSae)
    {
        $bdd = self::getBdd();
        $query = "
    SELECT R.*, EE.id_utilisateur AS id_evaluateur
    FROM Rendu R
    LEFT JOIN Evaluation E ON R.id_evaluation = E.id_evaluation
    LEFT JOIN Evaluation_Evaluateur EE ON E.id_evaluation = EE.id_evaluation AND EE.is_principal = 1
    WHERE R.id_projet = ?";

        $stmt = $bdd->prepare($query);
        $stmt->execute([$idSae]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function creerEvaluationPourRendu($id_rendu, $coefficient, $note_max, $evaluateur)
    {
        $bdd = self::getBdd();
        $query = "
        INSERT INTO Evaluation (id_evaluation, coefficient, note_max)
        VALUES (DEFAULT, ?, ?)
    ";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$coefficient, $note_max]);

        $id_evaluation = $bdd->lastInsertId();
        $queryLink = "UPDATE Rendu SET id_evaluation = ? WHERE id_rendu = ?";

        $stmtLink = $bdd->prepare($queryLink);
        $stmtLink->execute([$id_evaluation, $id_rendu]);
        return $id_evaluation;
    }

    public function sauvegarderNoteRendu($idEtudiant, $note, $id_rendu, $id_groupe, $isIndividualEvaluation, $id_evaluation, $idEvaluateur)
    {
        $bdd = $this->getBdd();

        $insertQuery = "INSERT INTO Rendu_Evaluation (id_evaluation, id_rendu, id_groupe, id_etudiant, id_evaluateur, isIndividualEvaluation, note)
                        VALUES (?, ?, ?, ?, ?, ?, ?)
                            ";
        $insertStmt = $bdd->prepare($insertQuery);
        $insertStmt->execute([$id_evaluation, $id_rendu, $id_groupe, $idEtudiant, $idEvaluateur, $isIndividualEvaluation, $note]);

        return true;

    }

    public function modifierEvaluationRendu($id_evaluation, $id_groupe, $id_etudiant, $note)
    {
        $bdd = $this->getBdd();
        $query = "UPDATE Rendu_Evaluation
              SET note = ?
              WHERE id_evaluation = ?
              AND id_groupe = ?
              AND id_etudiant = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$note, $id_evaluation, $id_groupe, $id_etudiant]);
        $stmt->execute();
    }

    public function getNotesParEvaluationRendu($id_groupe, $id_evaluation)
    {
        $bdd = $this->getBdd();
        $query = "
        SELECT 
            r.titre, u.nom, u.prenom, u.email, re.note, u.id_utilisateur
        FROM Rendu_Groupe rg
        INNER JOIN Rendu r ON rg.id_rendu = r.id_rendu
        INNER JOIN Groupe g ON rg.id_groupe = g.id_groupe
        INNER JOIN Groupe_Etudiant ge ON g.id_groupe = ge.id_groupe
        INNER JOIN Utilisateur u ON ge.id_utilisateur = u.id_utilisateur
        LEFT JOIN Rendu_Evaluation re ON r.id_rendu = re.id_rendu
            AND rg.id_groupe = re.id_groupe
            AND re.id_etudiant = u.id_utilisateur
        WHERE rg.id_groupe = ? AND r.id_evaluation = ?
    ";

        $stmt = $bdd->prepare($query);
        $stmt->execute([$id_groupe, $id_evaluation]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }




}