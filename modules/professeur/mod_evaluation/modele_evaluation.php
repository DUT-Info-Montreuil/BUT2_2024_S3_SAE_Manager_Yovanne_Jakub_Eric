<?php
include_once 'Connexion.php';

class ModeleEvaluation extends Connexion
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
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function modifierEvaluation($id, $note_max, $coefficient){
        $bdd = $this->getBdd();
        $query = "UPDATE Evaluation SET note_max = ? , coefficient = ? WHERE id_evaluation = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$note_max,$coefficient,$id]);
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
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getRenduEvaluation($idSae)
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
        WHERE r.id_projet = ? AND r.id_evaluation IS NOT NULL
        GROUP BY rg.id_rendu, rg.id_groupe
        ORDER BY g.nom, r.date_limite;
    ";

        $stmt = $bdd->prepare($query);
        $stmt->execute([$idSae]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function getSoutenanceEvaluation($idSae)
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
        WHERE s.id_projet = ? AND s.id_evaluation IS NOT NULL
        GROUP BY sg.id_soutenance, sg.id_groupe
        ORDER BY g.nom, s.date_soutenance;
    ";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idSae]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function checkEvaluationSoutenanceExist($id_soutenance)
    {
        $bdd = self::getBdd();
        $query = "
    SELECT id_evaluation
    FROM Soutenance
    WHERE id_soutenance = ?
    ";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$id_soutenance]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result && $result['id_evaluation'] !== null) {
            return $result['id_evaluation'];
        }

        return null;
    }

    public function checkEvaluationRenduExist($id_rendu)
    {
        $bdd = self::getBdd();
        $query = "
    SELECT id_evaluation
    FROM Rendu
    WHERE id_rendu = ?
    ";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$id_rendu]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result && $result['id_evaluation'] !== null) {
            return $result['id_evaluation'];
        }

        return null;
    }


    public function getAllMembreSAE($id_groupe)
    {
        $bdd = self::getBdd();
        $query = "SELECT u.id_utilisateur, u.nom, u.prenom, u.email
              FROM Utilisateur u
              INNER JOIN Groupe_Etudiant ge ON u.id_utilisateur = ge.id_utilisateur
              WHERE ge.id_groupe = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$id_groupe]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllRenduSAE($idSae)
    {
        $bdd = self::getBdd();
        $query = "SELECT * FROM Rendu WHERE id_projet = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idSae]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllSoutenanceSAE($idSae)
    {
        $bdd = self::getBdd();
        $query = "SELECT * FROM Soutenance WHERE id_projet = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idSae]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function creerEvaluationPourRendu($id_rendu, $coefficient, $note_max)
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
    }

    public function creerEvaluationPourSoutenance($id_soutenance, $coefficient, $note_max)
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
    }

    public function sauvegarderNoteRendu($idUtilisateur, $note, $id_rendu, $id_groupe, $grpOuIndividuelle)
    {
        $bdd = $this->getBdd();

        $queryEval = "
                        SELECT id_evaluation
                        FROM Rendu
                        WHERE id_rendu = ?
                     ";

        $stmtEval = $bdd->prepare($queryEval);
        $stmtEval->execute([$id_rendu]);
        $resultEval = $stmtEval->fetch(PDO::FETCH_ASSOC);

        $id_evaluation = $resultEval['id_evaluation'];

        $insertQuery = "INSERT INTO Rendu_Evaluation (id_evaluation, id_rendu, id_groupe, id_etudiant, id_evaluateur, groupeOuIndividuelle, note)
                        VALUES (?, ?, ?, ?, ?, ?, ?)
                            ";
        $insertStmt = $bdd->prepare($insertQuery);
        $insertStmt->execute([$id_evaluation, $id_rendu, $id_groupe, $idUtilisateur, $_SESSION['id_utilisateur'], $grpOuIndividuelle, $note]);

        return true;

    }

    public function sauvegarderNoteSoutenance($idUtilisateur, $note, $id_soutenance, $id_groupe, $grpOuIndividuelle)
    {
        $bdd = $this->getBdd();
        $queryEval = "
                    SELECT id_evaluation
                    FROM Soutenance
                    WHERE id_soutenance = ?
                 ";
        $stmtEval = $bdd->prepare($queryEval);
        $stmtEval->execute([$id_soutenance]);
        $resultEval = $stmtEval->fetch(PDO::FETCH_ASSOC);

        $id_evaluation = $resultEval['id_evaluation'];

        $insertQuery = "
                        INSERT INTO Soutenance_Evaluation (id_evaluation, id_soutenance, id_groupe, id_etudiant, id_evaluateur, groupeOuIndividuelle, note)
                        VALUES (?, ?, ?, ?, ?, ?, ?)
                    ";
        $insertStmt = $bdd->prepare($insertQuery);
        $insertStmt->execute([$id_evaluation, $id_soutenance, $id_groupe, $idUtilisateur, $_SESSION['id_utilisateur'], $grpOuIndividuelle, $note]);
    }

    public function getNotesParEvaluation($id_groupe, $id_evaluation, $type_evaluation)
    {
        $bdd = $this->getBdd();
        $query = "";

        if ($type_evaluation === 'rendu') {
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
        } elseif ($type_evaluation === 'soutenance') {
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
        }

        $stmt = $bdd->prepare($query);
        $stmt->execute([$id_groupe, $id_evaluation]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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




}