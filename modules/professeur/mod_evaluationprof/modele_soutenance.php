<?php
include_once 'Connexion.php';

class ModeleEvaluationSoutenance extends Connexion
{
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
    public function ajouterCritereSoutenance($nom, $description, $coefficient, $note_max, $id_soutenance, $id_evaluation)
    {
        $bdd = $this->getBdd();
        $sql = "INSERT INTO Critere_Soutenance (id_soutenance, nom_critere, description, coefficient, note_max, id_evaluation)
            VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([$id_soutenance, $nom, $description,$coefficient, $note_max, $id_evaluation]);

        
    }
    public function sauvegarderNoteSoutenanceCritere($idEtudiant, $note, $idSoutenance, $idGroupe, $idCritere, $idEvaluation, $idEvaluateur, $commentaire)
    {
        $checkQuery = "
        SELECT COUNT(*) FROM Critere_Notation_Soutenance
        WHERE id_critere = ? 
        AND id_groupe = ?
        AND id_etudiant = ?
    ";
        $stmtCheck = $this->getBdd()->prepare($checkQuery);
        $stmtCheck->execute([$idCritere, $idGroupe, $idEtudiant]);
        $count = $stmtCheck->fetchColumn();

        if ($count > 0) {
            $updateQuery = "
            UPDATE Critere_Notation_Soutenance 
            SET note = ?
            WHERE id_critere = ?
            AND id_groupe = ?
            AND id_etudiant = ?
        ";
            $stmtUpdate = $this->getBdd()->prepare($updateQuery);
            $stmtUpdate->execute([$note, $idCritere, $idGroupe, $idEtudiant]);
        } else {
            $insertQuery = "
            INSERT INTO Critere_Notation_Soutenance (id_critere, id_soutenance, id_groupe, id_etudiant, note)
            VALUES (?, ?, ?, ?, ?)
        ";
            $stmtInsert = $this->getBdd()->prepare($insertQuery);
            $stmtInsert->execute([$idCritere, $idSoutenance, $idGroupe, $idEtudiant, $note]);
        }

        if ($commentaire) {
            $this->sauvegarderCommentaireSoutenance($idEtudiant, $idSoutenance, $idGroupe, $idEvaluateur, $commentaire, $note, $idEvaluation);
        }
    }
    public function sauvegarderCommentaireSoutenance($idUtilisateur, $idSoutenance, $idGroupe, $idEvaluateur, $commentaire, $note, $idEvaluation)
    {
        $query = "
        INSERT INTO Soutenance_Evaluation (id_evaluation, id_soutenance, id_groupe, id_etudiant, id_evaluateur, commentaire, note)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ";
        $stmt = $this->getBdd()->prepare($query);
        $stmt->execute([$idEvaluation, $idSoutenance, $idGroupe, $idUtilisateur, $idEvaluateur, $commentaire, $note]);
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
        $query = $query = "
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
            MAX(se.note) AS soutenance_note, -- Using MAX() to resolve the conflict
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
        GROUP BY sg.id_soutenance, sg.id_groupe, s.id_evaluation, g.nom, s.titre, s.date_soutenance, e.note_max, e.coefficient
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
    public function sauvegarderNoteSoutenance($idUtilisateur, $note, $id_soutenance, $id_groupe, $id_evaluation, $idEvaluateur, $commentaire)
    {
        $bdd = $this->getBdd();
        $insertQuery = "
                        INSERT INTO Soutenance_Evaluation (id_evaluation, id_soutenance, id_groupe, id_etudiant, id_evaluateur, note, commentaire)
                        VALUES (?, ?, ?, ?, ?, ?, ?)
                    ";
        $insertStmt = $bdd->prepare($insertQuery);
        $insertStmt->execute([$id_evaluation, $id_soutenance, $id_groupe, $idUtilisateur, $idEvaluateur, $note, $commentaire]);
    }
    public function getCriteresNotationSoutenance($idSoutenance)
    {
        $sql = "
        SELECT c.id_critere, c.nom_critere, c.description, c.coefficient, c.note_max 
        FROM Critere c
        INNER JOIN Evaluation e ON c.id_evaluation = e.id_evaluation
        INNER JOIN Soutenance s ON e.id_evaluation = s.id_evaluation
        WHERE s.id_soutenance = ?
    ";

        $stmt = $this->getBdd()->prepare($sql);
        $stmt->execute([$idSoutenance]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    public function sauvegarderNoteSoutenanceEvaluation($idSoutenance, $idGroupe, $idEvaluation, $idEtudiant, $idEvaluateur)
    {
        $queryCritere = "
    SELECT cs.id_critere, cs.coefficient, cs.note_max, c.note AS note_critere
    FROM Critere_Notation_Soutenance c
    JOIN Critere cs ON c.id_critere = cs.id_critere
    WHERE c.id_soutenance = ? AND cs.id_evaluation = ? AND c.id_groupe = ? AND c.id_etudiant = ?
    ";

        $stmtCritere = $this->getBdd()->prepare($queryCritere);
        $stmtCritere->execute([$idSoutenance, $idEvaluation, $idGroupe, $idEtudiant]);
        $sommeNotes = 0;
        $sommeCoefficients = 0;

        while ($row = $stmtCritere->fetch(PDO::FETCH_ASSOC)) {
            $noteCritere = $row['note_critere'];
            $coefficient = $row['coefficient'];
            $sommeNotes += $noteCritere * $coefficient;
            $sommeCoefficients += $coefficient;
        }

        $noteGlobale = $sommeCoefficients > 0 ? $sommeNotes / $sommeCoefficients : 0;

        $checkQuery = "
    SELECT COUNT(*) 
    FROM Soutenance_Evaluation
    WHERE id_soutenance = ?
    AND id_groupe = ?
    AND id_etudiant = ?
    ";
        $stmtCheck = $this->getBdd()->prepare($checkQuery);
        $stmtCheck->execute([$idSoutenance, $idGroupe, $idEtudiant]);
        $count = $stmtCheck->fetchColumn();

        if ($count > 0) {
            $updateQuery = "
        UPDATE Soutenance_Evaluation
        SET note = ?, id_evaluation = ?, id_evaluateur = ?
        WHERE id_soutenance = ?
        AND id_groupe = ?
        AND id_etudiant = ?
        ";
            $stmtUpdate = $this->getBdd()->prepare($updateQuery);
            $stmtUpdate->execute([$noteGlobale, $idEvaluation, $idEvaluateur, $idSoutenance, $idGroupe, $idEtudiant]);
        } else {
            $insertQuery = "
        INSERT INTO Soutenance_Evaluation (id_soutenance, id_groupe, note, id_evaluation, id_etudiant, id_evaluateur)
        VALUES (?, ?, ?, ?, ?, ?)
        ";
            $stmtInsert = $this->getBdd()->prepare($insertQuery);
            $stmtInsert->execute([$idSoutenance, $idGroupe, $noteGlobale, $idEvaluation, $idEtudiant, $idEvaluateur]);
        }
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
    public function sauvegarderNoteGlobaleSoutenance($id_groupe, $idSoutenance, $idEtudiant, $id_evaluation, $idEvaluateur, $note, $commentaire)
    {
        $bdd = $this->getBdd();
    
        $checkQuery = "SELECT COUNT(*) FROM Soutenance_Evaluation
                       WHERE id_evaluation = ? AND id_groupe = ? AND id_etudiant = ? AND id_soutenance = ?";
        $checkStmt = $bdd->prepare($checkQuery);
        $checkStmt->execute([$id_evaluation, $id_groupe, $idEtudiant, $idSoutenance]);
        $exists = $checkStmt->fetchColumn();
    
        if ($exists > 0) {
            $updateQuery = "UPDATE Soutenance_Evaluation
                            SET note = ?, commentaire = ?, id_evaluateur = ?
                            WHERE id_evaluation = ? AND id_groupe = ? AND id_etudiant = ? AND id_soutenance = ?";
            $updateStmt = $bdd->prepare($updateQuery);
            $updateStmt->execute([$note, $commentaire, $idEvaluateur, $id_evaluation, $id_groupe, $idEtudiant, $idSoutenance]);
        } else {
            $insertQuery = "INSERT INTO Soutenance_Evaluation (id_evaluation, id_groupe, id_etudiant, id_evaluateur, id_soutenance, note, commentaire)
                            VALUES (?, ?, ?, ?, ?, ?, ?)";
            $insertStmt = $bdd->prepare($insertQuery);
            $insertStmt->execute([$id_evaluation, $id_groupe, $idEtudiant, $idEvaluateur, $idSoutenance, $note, $commentaire]);
        }
    
        return true;
    }
}