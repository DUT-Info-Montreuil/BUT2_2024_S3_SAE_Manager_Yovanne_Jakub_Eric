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

        $queryRenduEvaluations = "
        SELECT RE.id_evaluation, RE.note, E.coefficient
        FROM Rendu_Evaluation RE
        JOIN Evaluation E ON RE.id_evaluation = E.id_evaluation
        WHERE RE.id_etudiant = ? AND RE.id_groupe = ?
    ";
        $stmtRenduEvaluations = $bdd->prepare($queryRenduEvaluations);
        $stmtRenduEvaluations->execute([$idEtudiant, $idGroupe]);
        $renduEvaluations = $stmtRenduEvaluations->fetchAll(PDO::FETCH_ASSOC);

        $querySoutenanceEvaluations = "
        SELECT SE.id_evaluation, SE.note, E.coefficient
        FROM Soutenance_Evaluation SE
        JOIN Evaluation E ON SE.id_evaluation = E.id_evaluation
        WHERE SE.id_etudiant = ? AND SE.id_groupe = ?
    ";
        $stmtSoutenanceEvaluations = $bdd->prepare($querySoutenanceEvaluations);
        $stmtSoutenanceEvaluations->execute([$idEtudiant, $idGroupe]);
        $soutenanceEvaluations = $stmtSoutenanceEvaluations->fetchAll(PDO::FETCH_ASSOC);

        $totalNotePonderee = 0;
        $totalCoef = 0;

        foreach ($renduEvaluations as $rendu) {
            $totalNotePonderee += $rendu['note'] * $rendu['coefficient'];
            $totalCoef += $rendu['coefficient'];
        }

        foreach ($soutenanceEvaluations as $soutenance) {
            $totalNotePonderee += $soutenance['note'] * $soutenance['coefficient'];
            $totalCoef += $soutenance['coefficient'];
        }

        if ($totalCoef > 0) {
            $nouvelleNoteFinale = $totalNotePonderee / $totalCoef;
        } else {
            $nouvelleNoteFinale = 0;
        }

        $updateQuery = "
        UPDATE Groupe_Etudiant
        SET note_finale = ?
        WHERE id_utilisateur = ? AND id_groupe = ?
    ";
        $updateStmt = $bdd->prepare($updateQuery);
        $updateStmt->execute([$nouvelleNoteFinale, $idEtudiant, $idGroupe]);

        return true;
    }

}