<?php
include_once 'Connexion.php';

class ModeleEvaluationProf extends Connexion
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

    public function getAllGerantNonEvaluateur($idSAE, $idEvaluation) {
        $bdd = $this->getBdd();

        $query = "
    SELECT u.id_utilisateur, u.login_utilisateur, u.prenom, u.nom, g.role_utilisateur 
    FROM Gerant g
    INNER JOIN Utilisateur u ON g.id_utilisateur = u.id_utilisateur
    WHERE g.id_projet = ?
    AND u.id_utilisateur NOT IN (
        SELECT ee.id_utilisateur
        FROM Evaluation_Evaluateur ee
        WHERE ee.id_evaluation = ?
    )
    ";

        $stmt = $bdd->prepare($query);
        $stmt->execute([$idSAE, $idEvaluation]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function estDejaEvaluateur($idEvaluateur, $idEvaluation)
    {
        $bdd = $this->getBdd();
        $req = $bdd->prepare("SELECT COUNT(*) AS nb FROM Evaluation_Evaluateur WHERE id_utilisateur = ? AND id_evaluation = ?");
        $req->execute([$idEvaluateur, $idEvaluation]);
        $result = $req->fetch();
        return $result['nb'] > 0;
    }

    public function ajouterEvaluateur($idEvaluateur, $idEvaluation)
    {
        $bdd = $this->getBdd();
        $req = $bdd->prepare("INSERT INTO Evaluation_Evaluateur (id_utilisateur, id_evaluation) VALUES (?, ?)");
        $req->execute([$idEvaluateur, $idEvaluation]);
    }

    public function getAllEvaluateur($idEvaluation)
    {
        $bdd = $this->getBdd();

        $req = $bdd->prepare("
        SELECT u.id_utilisateur, u.nom, u.prenom
        FROM Evaluation_Evaluateur ee
        JOIN Utilisateur u ON ee.id_utilisateur = u.id_utilisateur
        JOIN Evaluation e ON ee.id_evaluation = e.id_evaluation
        WHERE e.id_evaluation = ? AND ee.is_principal = 0
    ");
        $req->execute([$idEvaluation]);

        return $req->fetchAll();
    }

    public function supprimerEvaluateur($idEvaluateur, $idEvaluation)
    {
        $bdd = $this->getBdd();

        $req = $bdd->prepare("
        DELETE FROM Evaluation_Evaluateur
        WHERE id_utilisateur = ? AND id_evaluation = ?
    ");
        $req->execute([$idEvaluateur, $idEvaluation]);
    }


    public function getEvaluationById($id){
        $bdd = $this->getBdd();
        $query = "SELECT * FROM Evaluation WHERE id_evaluation = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
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

    public function getAllGerantSae($idSAE) {
        $bdd = $this->getBdd();
        $sql = "SELECT U.id_utilisateur, U.nom, U.prenom, U.email, G.role_utilisateur 
            FROM Gerant G
            JOIN Utilisateur U ON G.id_utilisateur = U.id_utilisateur
            WHERE G.id_projet = ?";

        $stmt = $bdd->prepare($sql);
        $stmt->execute([$idSAE]);
        $gerants = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $gerants;
    }
    public function iAmEvaluateurPrincipal($id_evaluation, $id_evaluateur)
    {
        $bdd = $this->getBdd();
        $query = "
        SELECT is_principal 
        FROM Evaluation_Evaluateur
        WHERE id_evaluation = ? 
        AND id_utilisateur = ?
    ";

        $stmt = $bdd->prepare($query);
        $stmt->execute([$id_evaluation, $id_evaluateur]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result && $result['is_principal'] == 1;
    }

    public function isEvaluateur($id_evaluation, $id_evaluateur)
    {
        $bdd = $this->getBdd();

        $query = "
    SELECT COUNT(*) AS count
    FROM Evaluation_Evaluateur
    WHERE id_evaluation = ? 
    AND id_utilisateur = ?
    ";

        $stmt = $bdd->prepare($query);
        $stmt->execute([$id_evaluation, $id_evaluateur]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result && $result['count'] > 0;
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
    public function modifierEvaluation($idEvaluation, $note_max, $coefficient)
    {
        $bdd = $this->getBdd();
        $query = "UPDATE Evaluation SET note_max = ? , coefficient = ? WHERE id_evaluation = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$note_max, $coefficient, $idEvaluation]);
    }
    public function modifierEvaluateurPrincipal($idNvEvalueur, $idEvaluation, $delegation_action) {
        $bdd = $this->getBdd();
        $bdd->beginTransaction();

        try {
            // si il ne veut plus être evaluateur ont le supprime
            if ($delegation_action === 'remove') {
                $query = "DELETE FROM Evaluation_Evaluateur 
                      WHERE id_evaluation = ? AND is_principal = TRUE AND id_utilisateur != ?";
                $stmt = $bdd->prepare($query);
                $stmt->execute([$idEvaluation, $idNvEvalueur]);
            }

            // verif nv évaluateur existe déjà comme évaluateur
            $query = "SELECT COUNT(*) FROM Evaluation_Evaluateur WHERE id_evaluation = ? AND id_utilisateur = ?";
            $stmt = $bdd->prepare($query);
            $stmt->execute([$idEvaluation, $idNvEvalueur]);
            $exists = $stmt->fetchColumn();

            // si existe pas ont ajoute
            if ($exists == 0) {
                $query = "INSERT INTO Evaluation_Evaluateur (id_evaluation, id_utilisateur, is_principal) 
                      VALUES (?, ?, TRUE)";
                $stmt = $bdd->prepare($query);
                $stmt->execute([$idEvaluation, $idNvEvalueur]);
            } else {
                $query = "UPDATE Evaluation_Evaluateur 
                      SET is_principal = TRUE 
                      WHERE id_evaluation = ? AND id_utilisateur = ?";
                $stmt = $bdd->prepare($query);
                $stmt->execute([$idEvaluation, $idNvEvalueur]);
            }

            // désactivation de l'ancien évaluateur
            $query = "UPDATE Evaluation_Evaluateur 
                  SET is_principal = FALSE 
                  WHERE id_evaluation = ? AND is_principal = TRUE AND id_utilisateur != ?";
            $stmt = $bdd->prepare($query);
            $stmt->execute([$idEvaluation, $idNvEvalueur]);

            $bdd->commit();
        } catch (Exception $e) {
            $bdd->rollBack();
            throw $e;
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

        $this->insererEvaluateur($id_evaluation, $evaluateur, true);
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

        $this->insererEvaluateur($id_evaluation, $evaluateur, true);

    }

    public function insererEvaluateur($id_evaluation, $id_utilisateur, $is_principal = false)
    {
        $bdd = self::getBdd();
        if ($is_principal) {
            $queryCheckPrincipal = "
            SELECT 1 
            FROM Evaluation_Evaluateur 
            WHERE id_evaluation = ? AND is_principal = 1
        ";
            $stmtCheck = $bdd->prepare($queryCheckPrincipal);
            $stmtCheck->execute([$id_evaluation]);

            if ($stmtCheck->fetch()) {
                throw new Exception("Un évaluateur principal existe déjà pour cette évaluation.");
            }
        }

        $queryInsert = "
        INSERT INTO Evaluation_Evaluateur (id_evaluation, id_utilisateur, is_principal)
        VALUES (?, ?, ?)
    ";
        $stmtInsert = $bdd->prepare($queryInsert);
        $stmtInsert->execute([$id_evaluation, $id_utilisateur, $is_principal ? 1 : 0]);
        return true;
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
    public function getIdEvaluationRendu($id_rendu){
        $bdd = $this->getBdd();

        $queryEval = "
                        SELECT id_evaluation
                        FROM Rendu
                        WHERE id_rendu = ?
                     ";

        $stmtEval = $bdd->prepare($queryEval);
        $stmtEval->execute([$id_rendu]);
        $resultEval = $stmtEval->fetch(PDO::FETCH_ASSOC);

        return $resultEval['id_evaluation'];
    }
    public function sauvegarderNoteSoutenance($idUtilisateur, $note, $id_soutenance, $id_groupe, $isIndividualEvaluation, $id_evaluation, $idEvaluateur)
    {
        $bdd = $this->getBdd();
        $insertQuery = "
                        INSERT INTO Soutenance_Evaluation (id_evaluation, id_soutenance, id_groupe, id_etudiant, id_evaluateur, isIndividualEvaluation, note)
                        VALUES (?, ?, ?, ?, ?, ?, ?)
                    ";
        $insertStmt = $bdd->prepare($insertQuery);
        $insertStmt->execute([$id_evaluation, $id_soutenance, $id_groupe, $idUtilisateur, $idEvaluateur, $isIndividualEvaluation, $note]);
    }
    public function getIdEvaluationSoutenance($id_soutenance){
        $bdd = $this->getBdd();
        $queryEval = "
                    SELECT id_evaluation
                    FROM Soutenance
                    WHERE id_soutenance = ?
                 ";
        $stmtEval = $bdd->prepare($queryEval);
        $stmtEval->execute([$id_soutenance]);
        $resultEval = $stmtEval->fetch(PDO::FETCH_ASSOC);

        return $resultEval['id_evaluation'];
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
    public function supprimerEvaluation($id_evaluation)
    {
        $bdd = $this->getBdd();

        $bdd->beginTransaction();

        try {

            $queryEvaluation = "UPDATE Soutenance SET id_evaluation = NULL WHERE id_evaluation = ?";
            $stmtEvaluation = $bdd->prepare($queryEvaluation);
            $stmtEvaluation->execute([$id_evaluation]);
            $stmtEvaluation->execute();

            $queryEvaluation = "UPDATE Rendu SET id_evaluation = NULL WHERE id_evaluation = ?";
            $stmtEvaluation = $bdd->prepare($queryEvaluation);
            $stmtEvaluation->execute([$id_evaluation]);
            $stmtEvaluation->execute();

            $queryEvaluation = "DELETE FROM Evaluation WHERE id_evaluation = ?";
            $stmtEvaluation = $bdd->prepare($queryEvaluation);
            $stmtEvaluation->execute([$id_evaluation]);
            $stmtEvaluation->execute();

            $bdd->commit();

        } catch (Exception $e) {
            $bdd->rollBack();
            echo "Erreur lors de la suppression de l'évaluation : " . $e->getMessage();
        }
    }
}