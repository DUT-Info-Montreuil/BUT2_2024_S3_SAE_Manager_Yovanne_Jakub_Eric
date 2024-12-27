<?php
include_once 'Connexion.php';

class ModeleEvaluation extends Connexion
{
    public function __construct()
    {
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
            re.note AS note_rendu,
            ge.id_groupe,
            r.id_rendu,
            e.coefficient AS note_coef,  -- Récupère le coefficient
            e.note_max AS note_max,     -- Récupère la note maximale
            GROUP_CONCAT(u.nom, ' ', u.prenom ORDER BY u.nom) AS etudiants,
            COUNT(u.id_utilisateur) AS nombre_etudiants
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
            Evaluation e ON e.id_evaluation = r.id_evaluation  -- Jointure avec Evaluation pour récupérer le coefficient et la note max
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



    public function getAllMembreSAE($id_groupe){
        $bdd = self::getBdd();
        $query = "SELECT u.id_utilisateur, u.nom, u.prenom, u.email
              FROM Utilisateur u
              INNER JOIN Groupe_Etudiant ge ON u.id_utilisateur = ge.id_utilisateur
              WHERE ge.id_groupe = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$id_groupe]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllRenduSAE($idSae){
        $bdd = self::getBdd();
        $query = "SELECT * FROM Rendu WHERE id_projet = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idSae]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllSoutenanceSAE($idSae){
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

    public function sauvegarderNoteIndividuelle($idUtilisateur, $note, $id_rendu, $id_groupe)
    {
        $bdd = self::getBdd();
        $query = "
        INSERT INTO Rendu_Evaluation (id_evaluation, id_rendu, id_groupe, id_etudiant, groupeOuIndividuelle, note)
        VALUES (?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE note = VALUES(note);
    ";

        $stmt = $bdd->prepare($query);
        $stmt->execute([
            $idEvaluation,
            $id_rendu,
            $id_groupe,
            $idUtilisateur,
            true,
            $note
        ]);
    }




}