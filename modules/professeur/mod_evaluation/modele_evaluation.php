<?php
include_once 'Connexion.php';

class ModeleEvaluation extends Connexion
{
    public function __construct()
    {
    }

    public function infNoteMaxRendu($id_rendu)
    {
        $bdd = $this->getBdd();

        $query = "
        SELECT e.note_max
        FROM Rendu r
        JOIN Evaluation e ON r.id_evaluation = e.id_evaluation
        WHERE r.id_rendu = ?
    ";

        $stmt = $bdd->prepare($query);
        $stmt->execute([$id_rendu]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function modifierEvaluation($id, $note_max, $coefficient){
        $bdd = $this->getBdd();
        $query = "UPDATE Evaluation SET note_max = ? , coefficient = ? WHERE id_evaluation = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$note_max,$coefficient,$id]);
    }

    public function infNoteMaxSoutenance($id_soutenance)
    {
        $bdd = $this->getBdd();

        $query = "
        SELECT e.note_max
        FROM Soutenance s
        JOIN Evaluation e ON s.id_evaluation = e.id_evaluation
        WHERE s.id_soutenance = ?
    ";

        $stmt = $bdd->prepare($query);
        $stmt->execute([$id_soutenance]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getRenduEvaluation($idSae)
    {
        $bdd = self::getBdd();
        $query = "
SELECT 
    g.nom AS groupe_nom, 
    r.titre AS rendu_titre, 
    r.date_limite AS rendu_date_limite, 
    rg.statut AS rendu_statut, 
    GROUP_CONCAT(DISTINCT CONCAT(u.nom, ' ', u.prenom, ' : ', COALESCE(re.note, 'Non noté')) 
                 ORDER BY u.nom SEPARATOR '\n') AS notes_individuelles,
    ge.id_groupe,
    r.id_rendu,
    e.coefficient AS note_coef,
    e.note_max AS note_max
FROM 
    Projet p
JOIN 
    Rendu r ON r.id_projet = p.id_projet
LEFT JOIN 
    Rendu_Groupe rg ON rg.id_rendu = r.id_rendu
LEFT JOIN 
    Rendu_Evaluation re ON re.id_rendu = r.id_rendu AND re.id_groupe = rg.id_groupe
JOIN 
    Projet_Groupe pg ON pg.id_projet = p.id_projet
JOIN 
    Groupe g ON g.id_groupe = pg.id_groupe
JOIN 
    Groupe_Etudiant ge ON ge.id_groupe = g.id_groupe
JOIN 
    Utilisateur u ON u.id_utilisateur = ge.id_utilisateur
LEFT JOIN 
    Evaluation e ON e.id_evaluation = r.id_evaluation
WHERE 
    p.id_projet = ?
    AND r.id_evaluation IS NOT NULL
GROUP BY 
    g.id_groupe, r.id_rendu
ORDER BY 
    g.nom, r.date_limite;



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
        se.note AS note_soutenance,
        ge.id_groupe,
        s.id_soutenance,
        e.coefficient AS note_coef,
        e.note_max AS note_max,
        GROUP_CONCAT(DISTINCT CONCAT(u.nom, ' ', u.prenom, ' : ', COALESCE(se.note, 'Non noté')) 
                     ORDER BY u.nom SEPARATOR ', ') AS notes_individuelles,
        COUNT(u.id_utilisateur) AS nombre_etudiants
    FROM 
        Projet p
    JOIN 
        Soutenance s ON s.id_projet = p.id_projet
    LEFT JOIN 
        Soutenance_Groupe sg ON sg.id_soutenance = s.id_soutenance
    LEFT JOIN 
        Soutenance_Evaluation se ON se.id_soutenance = s.id_soutenance AND se.id_groupe = sg.id_groupe
    JOIN 
        Projet_Groupe pg ON pg.id_projet = p.id_projet
    JOIN 
        Groupe g ON g.id_groupe = pg.id_groupe
    JOIN 
        Groupe_Etudiant ge ON ge.id_groupe = g.id_groupe
    JOIN 
        Utilisateur u ON u.id_utilisateur = ge.id_utilisateur
    LEFT JOIN 
        Evaluation e ON e.id_evaluation = s.id_evaluation
    WHERE 
        p.id_projet = ?
        AND s.id_evaluation IS NOT NULL
    GROUP BY 
        g.id_groupe, s.id_soutenance
    ORDER BY 
        g.nom, s.date_soutenance;
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


}