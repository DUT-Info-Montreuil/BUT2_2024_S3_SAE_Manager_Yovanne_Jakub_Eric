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

    public function ajouterCritereRendu($nom, $description, $coefficient, $note_max, $id_rendu, $idEvaluation)
    {
        $bdd = $this->getBdd();
        $sql = "INSERT INTO Critere_Rendu (nom_critere, description, coefficient, note_max, id_evaluation, id_rendu)
            VALUES (?, ? , ?, ? , ?, ?)";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([$nom, $description, $coefficient, $note_max, $idEvaluation, $id_rendu]);
    }

    public function getCriteresNotationRendu($idRendu)
    {
        $sql = "
        SELECT c.id_critere_rendu, c.nom_critere, c.description, c.coefficient, c.note_max 
        FROM Critere_Rendu c
        WHERE c.id_rendu = ?
    ";

        $stmt = $this->getBdd()->prepare($sql);
        $stmt->execute([$idRendu]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function calculerNoteGlobale($idRendu, $idGroupe)
    {
        $query = "
        SELECT id_critere_rendu, coefficient, note_max
        FROM Critere_Rendu
        WHERE id_rendu = :idRendu
    ";
        $stmt = $this->getBdd()->prepare($query);
        $stmt->bindParam(':idRendu', $idRendu);
        $stmt->execute();

        $totalNotes = 0;
        $totalCoef = 0;
        while ($row = $stmt->fetch()) {
            $idCritere = $row['id_critere_rendu'];
            $coef = $row['coefficient'];

            $queryNote = "
            SELECT note 
            FROM Critere_Notation_Rendu
            WHERE id_critere_rendu = :idCritere
            AND id_groupe = :idGroupe
        ";
            $stmtNote = $this->getBdd()->prepare($queryNote);
            $stmtNote->bindParam(':idCritere', $idCritere);
            $stmtNote->bindParam(':idGroupe', $idGroupe);
            $stmtNote->execute();

            $note = $stmtNote->fetchColumn();
            if ($note !== false) {
                $totalNotes += $note * $coef;
                $totalCoef += $coef;
            }
        }

        if ($totalCoef > 0) {
            return $totalNotes / $totalCoef;
        } else {
            return 0;
        }
    }

    public function sauvegarderNoteRenduEvaluation($idRendu, $idGroupe, $idEvaluation, $idEtudiant, $idEvaluateur)
    {
        $queryCritere = "
        SELECT cr.id_critere_rendu, cr.coefficient, cr.note_max, c.note AS note_critere
        FROM Critere_Rendu cr
        JOIN Critere_Notation_Rendu c ON cr.id_critere_rendu = c.id_critere_rendu
        WHERE cr.id_rendu = :idRendu AND cr.id_evaluation = :idEvaluation AND c.id_groupe = :idGroupe AND c.id_etudiant = :idEtudiant
    ";

        $stmtCritere = $this->getBdd()->prepare($queryCritere);
        $stmtCritere->bindParam(':idRendu', $idRendu);
        $stmtCritere->bindParam(':idEvaluation', $idEvaluation);
        $stmtCritere->bindParam(':idGroupe', $idGroupe);
        $stmtCritere->bindParam(':idEtudiant', $idEtudiant);
        $stmtCritere->execute();

        $sommeNotes = 0;
        $sommeCoefficients = 0;

        while ($row = $stmtCritere->fetch(PDO::FETCH_ASSOC)) {
            $noteCritere = $row['note_critere'];
            $coefficient = $row['coefficient'];

            // Ajout de la note pondérée à la somme
            $sommeNotes += $noteCritere * $coefficient;
            $sommeCoefficients += $coefficient;
        }

        $noteGlobale = $sommeCoefficients > 0 ? $sommeNotes / $sommeCoefficients : 0;

        $checkQuery = "
        SELECT COUNT(*) 
        FROM Rendu_Evaluation
        WHERE id_rendu = :idRendu
        AND id_groupe = :idGroupe
        AND id_etudiant = :idEtudiant
    ";
        $stmtCheck = $this->getBdd()->prepare($checkQuery);
        $stmtCheck->bindParam(':idRendu', $idRendu);
        $stmtCheck->bindParam(':idGroupe', $idGroupe);
        $stmtCheck->bindParam(':idEtudiant', $idEtudiant);
        $stmtCheck->execute();
        $count = $stmtCheck->fetchColumn();

        if ($count > 0) {
            $updateQuery = "
            UPDATE Rendu_Evaluation
            SET note = :noteGlobale, id_evaluation = :idEvaluation, id_evaluateur = :idEvaluateur
            WHERE id_rendu = :idRendu
            AND id_groupe = :idGroupe
            AND id_etudiant = :idEtudiant
        ";
            $stmtUpdate = $this->getBdd()->prepare($updateQuery);
            $stmtUpdate->bindParam(':idRendu', $idRendu);
            $stmtUpdate->bindParam(':idGroupe', $idGroupe);
            $stmtUpdate->bindParam(':noteGlobale', $noteGlobale);
            $stmtUpdate->bindParam(':idEvaluation', $idEvaluation);
            $stmtUpdate->bindParam(':idEtudiant', $idEtudiant);
            $stmtUpdate->bindParam(':idEvaluateur', $idEvaluateur);
            $stmtUpdate->execute();
        } else {
            $insertQuery = "
            INSERT INTO Rendu_Evaluation (id_rendu, id_groupe, note, id_evaluation, id_etudiant, id_evaluateur)
            VALUES (:idRendu, :idGroupe, :noteGlobale, :idEvaluation, :idEtudiant, :idEvaluateur)
        ";
            $stmtInsert = $this->getBdd()->prepare($insertQuery);
            $stmtInsert->bindParam(':idRendu', $idRendu);
            $stmtInsert->bindParam(':idGroupe', $idGroupe);
            $stmtInsert->bindParam(':noteGlobale', $noteGlobale);
            $stmtInsert->bindParam(':idEvaluation', $idEvaluation);
            $stmtInsert->bindParam(':idEtudiant', $idEtudiant);
            $stmtInsert->bindParam(':idEvaluateur', $idEvaluateur);
            $stmtInsert->execute();
        }
    }




    public function sauvegarderNoteRenduCritere($idUtilisateur, $note, $idRendu, $idGroupe, $idCritere, $idEvaluation, $idEvaluateur, $commentaire)
    {
        // Vérification si la notation existe déjà
        $checkQuery = "
    SELECT COUNT(*) FROM Critere_Notation_Rendu
    WHERE id_critere_rendu = :idCritere 
    AND id_groupe = :idGroupe
    AND id_etudiant = :idUtilisateur
    ";
        $stmtCheck = $this->getBdd()->prepare($checkQuery);
        $stmtCheck->bindParam(':idCritere', $idCritere);
        $stmtCheck->bindParam(':idGroupe', $idGroupe);
        $stmtCheck->bindParam(':idUtilisateur', $idUtilisateur);
        $stmtCheck->execute();
        $count = $stmtCheck->fetchColumn();

        if ($count > 0) {
            // Mise à jour si l'entrée existe déjà
            $updateQuery = "
        UPDATE Critere_Notation_Rendu 
        SET note = :note 
        WHERE id_critere_rendu = :idCritere 
        AND id_groupe = :idGroupe 
        AND id_etudiant = :idUtilisateur
        ";
            $stmtUpdate = $this->getBdd()->prepare($updateQuery);
            $stmtUpdate->bindParam(':idCritere', $idCritere);
            $stmtUpdate->bindParam(':idGroupe', $idGroupe);
            $stmtUpdate->bindParam(':note', $note);
            $stmtUpdate->bindParam(':idUtilisateur', $idUtilisateur);
            $stmtUpdate->execute();
        } else {
            // Insertion si l'entrée n'existe pas
            $insertQuery = "
    INSERT INTO Critere_Notation_Rendu (id_critere_rendu, id_groupe, id_etudiant, note)
    VALUES (:idCritere, :idGroupe, :idUtilisateur, :note)
";

            $stmtInsert = $this->getBdd()->prepare($insertQuery);
            $stmtInsert->bindParam(':idCritere', $idCritere);
            $stmtInsert->bindParam(':idGroupe', $idGroupe);
            $stmtInsert->bindParam(':idUtilisateur', $idUtilisateur);  // Ajout de l'id de l'étudiant
            $stmtInsert->bindParam(':note', $note);
            $stmtInsert->execute();
        }

        // Sauvegarder le commentaire si fourni
        if ($commentaire) {
            // Méthode pour sauvegarder les commentaires
            $this->sauvegarderCommentaireRendu($idUtilisateur, $idRendu, $idGroupe, $idEvaluateur, $commentaire, $note, $idEvaluation);
        }
    }




    public function sauvegarderCommentaireRendu($idUtilisateur, $idRendu, $idGroupe, $idEvaluateur, $commentaire, $note, $idEvaluation)
    {
        $query = "
        INSERT INTO Rendu_Evaluation (id_evaluation, id_rendu, id_groupe, id_etudiant, id_evaluateur, commentaire, note)
        VALUES (:idEvaluation, :idRendu, :idGroupe, :idUtilisateur, :idEvaluateur, :commentaire, :note)
    ";
        $stmt = $this->getBdd()->prepare($query);
        $stmt->bindParam(':idEvaluation', $idEvaluation);
        $stmt->bindParam(':idRendu', $idRendu);
        $stmt->bindParam(':idGroupe', $idGroupe);
        $stmt->bindParam(':idUtilisateur', $idUtilisateur);
        $stmt->bindParam(':idEvaluateur', $idEvaluateur);
        $stmt->bindParam(':commentaire', $commentaire);
        $stmt->bindParam(':note', $note);
        $stmt->execute();
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
            MAX(re.note) AS rendu_note, -- Aggregate this column
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
            AND ee.id_utilisateur = ?
        GROUP BY rg.id_rendu, rg.id_groupe, r.id_evaluation, g.nom, r.titre, r.date_limite, rg.statut, e.note_max, e.coefficient
        ORDER BY g.nom, r.date_limite";

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

    public function sauvegarderNoteRendu($idEtudiant, $note, $id_rendu, $id_groupe, $id_evaluation, $idEvaluateur, $commentaire)
{
    $bdd = $this->getBdd();
    
    // Vérifiez si l'entrée existe déjà
    $checkQuery = "SELECT COUNT(*) FROM Rendu_Evaluation
                   WHERE id_evaluation = ? AND id_rendu = ? AND id_groupe = ? AND id_etudiant = ?";
    $checkStmt = $bdd->prepare($checkQuery);
    $checkStmt->execute([$id_evaluation, $id_rendu, $id_groupe, $idEtudiant]);
    $exists = $checkStmt->fetchColumn();
    
    if ($exists > 0) {
        // Mise à jour si une entrée existe déjà
        $updateQuery = "UPDATE Rendu_Evaluation
                        SET note = ?, commentaire = ?, id_evaluateur = ?
                        WHERE id_evaluation = ? AND id_rendu = ? AND id_groupe = ? AND id_etudiant = ?";
        $updateStmt = $bdd->prepare($updateQuery);
        $updateStmt->execute([$note, $commentaire, $idEvaluateur, $id_evaluation, $id_rendu, $id_groupe, $idEtudiant]);
    } else {
        // Insérer une nouvelle entrée si elle n'existe pas
        $insertQuery = "INSERT INTO Rendu_Evaluation (id_evaluation, id_rendu, id_groupe, id_etudiant, id_evaluateur, note, commentaire)
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
        $insertStmt = $bdd->prepare($insertQuery);
        $insertStmt->execute([$id_evaluation, $id_rendu, $id_groupe, $idEtudiant, $idEvaluateur, $note, $commentaire]);
    }
    
    return true;
}
public function sauvegarderNoteGlobaleRendu($id_groupe, $idRendu, $idEtudiant, $id_evaluation, $idEvaluateur, $note, $commentaire)
{
    $bdd = $this->getBdd();

    // Vérifiez si une entrée existe déjà
    $checkQuery = "SELECT COUNT(*) FROM Rendu_Evaluation
                   WHERE id_evaluation = ? AND id_groupe = ? AND id_etudiant = ?";
    $checkStmt = $bdd->prepare($checkQuery);
    $checkStmt->execute([$id_evaluation, $id_groupe, $idEtudiant]);
    $exists = $checkStmt->fetchColumn();

    if ($exists > 0) {
        // Mise à jour si une entrée existe déjà
        $updateQuery = "UPDATE Rendu_Evaluation
                        SET note = ?, commentaire = ?, id_evaluateur = ?, id_rendu = ?
                        WHERE id_evaluation = ? AND id_groupe = ? AND id_etudiant = ?";
        $updateStmt = $bdd->prepare($updateQuery);
        $updateStmt->execute([$note, $commentaire, $idEvaluateur, $idRendu, $id_evaluation, $id_groupe, $idEtudiant]);
    } else {
        // Insérer une nouvelle entrée si elle n'existe pas
        $insertQuery = "INSERT INTO Rendu_Evaluation (id_rendu, id_evaluation, id_groupe, id_etudiant, id_evaluateur, note, commentaire)
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
        $insertStmt = $bdd->prepare($insertQuery);
        $insertStmt->execute([$idRendu, $id_evaluation, $id_groupe, $idEtudiant, $idEvaluateur, $note, $commentaire]);
    }

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