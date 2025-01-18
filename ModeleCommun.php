<?php

include_once "Connexion.php";
class ModeleCommun extends Connexion
{
    public function __construct() {
    }

    public static function getRoleSAE($idSAE, $idUtilisateur){
        $bdd = self::getBdd();

        $sql = "SELECT role_utilisateur 
            FROM Gerant 
            WHERE id_projet = ? AND id_utilisateur = ?";
        $req = $bdd->prepare($sql);
        $req->execute([$idSAE, $idUtilisateur]);

        if ($result = $req->fetch(PDO::FETCH_ASSOC)) {
            return $result['role_utilisateur'];
        } else {
            return null;
        }
    }

    public static function getDescriptionSAE($idProjet){
        $bdd = self::getBdd();
        $sql = "SELECT description_projet FROM Projet WHERE id_projet = ?";
        $req = $bdd->prepare($sql);
        $req->execute([$idProjet]);
        if ($result = $req->fetch(PDO::FETCH_ASSOC)) {
            return $result['description_projet'];
        }
        return null;
    }

    public static function getTypeUtilisateur($idUtilisateur) {
        $bdd = self::getBdd();
        $sql = "SELECT type_utilisateur FROM Utilisateur WHERE id_utilisateur = ?";
        $req = $bdd->prepare($sql);
        $req->execute([$idUtilisateur]);
        if ($result = $req->fetch(PDO::FETCH_ASSOC)) {
            return $result['type_utilisateur'];
        }
        return null;
    }

    public static function getTitreSAE($idProjet){
        $bdd = self::getBdd();
        $query = "SELECT titre FROM Projet WHERE id_projet = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idProjet]);
        $sae = $stmt->fetch(PDO::FETCH_ASSOC);
        return $sae['titre'];
    }

    public static function mettreAJourNoteFinale($idEtudiant, $idGroupe)
    {
        $bdd = static::getBdd();

        try {
            $queryEvaluations = "
        SELECT 
            AE.id_evaluation, 
            AE.note, 
            E.coefficient, 
            E.note_max
        FROM 
            Activite_Evaluation AE
        JOIN 
            Evaluation E ON AE.id_evaluation = E.id_evaluation
        WHERE 
            AE.id_etudiant = ? AND AE.id_groupe = ?
        ";
            $stmtEvaluations = $bdd->prepare($queryEvaluations);
            $stmtEvaluations->execute([$idEtudiant, $idGroupe]);
            $evaluations = $stmtEvaluations->fetchAll(PDO::FETCH_ASSOC);

            $totalNotePonderee = 0;
            $totalCoef = 0;

            foreach ($evaluations as $evaluation) {
                $noteSur20 = ($evaluation['note'] / $evaluation['note_max']) * 20;
                $totalNotePonderee += $noteSur20 * $evaluation['coefficient'];
                $totalCoef += $evaluation['coefficient'];
            }

            if ($totalCoef > 0) {
                $noteFinale = $totalNotePonderee / $totalCoef;
            } else {
                $noteFinale = 0;
            }

            $updateQuery = "
        UPDATE Groupe_Etudiant
        SET note_finale = ?
        WHERE id_utilisateur = ? AND id_groupe = ?
        ";
            $updateStmt = $bdd->prepare($updateQuery);
            $updateStmt->execute([$noteFinale, $idEtudiant, $idGroupe]);

            return true;

        } catch (PDOException $e) {
            error_log('Erreur SQL : ' . $e->getMessage());
            return false;
        }
    }



}