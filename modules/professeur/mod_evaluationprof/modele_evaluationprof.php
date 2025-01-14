<?php
include_once "modules/professeur/mod_evaluationprof/modele_rendu.php";
include_once "modules/professeur/mod_evaluationprof/modele_soutenance.php";
include_once 'Connexion.php';

class ModeleEvaluationProf extends Connexion
{

    private $modeleRendu;
    private $modeleSoutenance;
    public function __construct()
    {
        $this->modeleRendu = new ModeleEvaluationRendu();
        $this->modeleSoutenance = new ModeleEvaluationSoutenance();
    }

    public function getFichierRendu($idRendu, $idGroupe)
    {
        $bdd = $this->getBdd();
        $query = "SELECT * FROM Rendu_Fichier WHERE id_groupe = ? AND id_rendu = ?";
        $statement = $bdd->prepare($query);
        $statement->execute([$idGroupe, $idRendu]);
        return $statement->fetchAll();

    }
    public function infNoteMaxRendu($id_evaluation)
    {
        return $this->modeleRendu->infNoteMaxRendu($id_evaluation);
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

    public function getAllEvaluateurSansLePrincipal($idEvaluation)
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

    public function getAllEvaluateur($idEvaluation) {
        $bdd = $this->getBdd();

        $query = "
        SELECT 
            u.id_utilisateur, 
            u.nom, 
            u.prenom, 
            u.email, 
            ee.is_principal
        FROM Evaluation_Evaluateur ee
        JOIN Utilisateur u ON u.id_utilisateur = ee.id_utilisateur
        WHERE ee.id_evaluation = ?
    ";

        $stmt = $bdd->prepare($query);
        $stmt->execute([$idEvaluation]);
        $evaluateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $evaluateurs;
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
        return $this->modeleRendu->getEvaluationByIdRendu($id_rendu);
    }

    public function getEvaluationByIdSoutenance($id_soutenance){
        return $this->modeleSoutenance->getEvaluationByIdSoutenance($id_soutenance);
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
        return $this->modeleRendu->getIdEvaluationByRendu($id_rendu);
    }
    public function getIdEvaluationBySoutenance($id_soutenance){
        return $this->modeleSoutenance->getIdEvaluationBySoutenance($id_soutenance);
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

        return $this->modeleSoutenance->infNoteMaxSoutenance($id_evaluation);
    }
    public function getRenduEvaluationGerer($idSae, $id_rendu, $idEvaluateur)
    {
        return $this->modeleRendu->getRenduEvaluationGerer($idSae, $id_rendu, $idEvaluateur);
    }


    public function getRenduEvaluation($idSae, $id_rendu)
    {
        return $this->getRenduEvaluation($idSae, $id_rendu);
    }
    public function getSoutenanceEvaluation($idSae, $id_soutenance)
    {

        return $this->modeleSoutenance->getSoutenanceEvaluation($idSae, $id_soutenance);
    }
    public function getSoutenanceEvaluationGerer($idSae, $id_soutenance, $idEvaluateur)
    {
        return $this->modeleSoutenance->getSoutenanceEvaluationGerer($idSae, $id_soutenance, $idEvaluateur);
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
        return $this->modeleRendu->getAllRenduSAE($idSae);
    }


    public function getAllSoutenanceSAE($idSae)
    {
        return $this->modeleSoutenance->getAllSoutenanceSAE($idSae);
    }

    public function creerEvaluationPourRendu($id_rendu, $coefficient, $note_max, $evaluateur)
    {
        $id_evaluation = $this->modeleRendu->creerEvaluationPourRendu($id_rendu, $coefficient, $note_max, $evaluateur);
        $this->insererEvaluateur($id_evaluation, $evaluateur, true);
    }
    public function creerEvaluationPourSoutenance($id_soutenance, $coefficient, $note_max, $evaluateur)
    {
        $id_evaluation = $this->modeleSoutenance->creerEvaluationPourSoutenance($id_soutenance, $coefficient, $note_max, $evaluateur);
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

    public function getChampsRemplisParGroupe($id_groupe)
    {
        $bdd =$this->getBdd();
        $query = "
        SELECT c.champ_nom, cg.champ_valeur
        FROM Champ c
        JOIN Champ_Groupe cg ON c.id_champ = cg.id_champ
        WHERE cg.id_groupe = ?
    ";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$id_groupe]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function mettreAJourNoteFinale($idEtudiant, $idGroupe)
    {
        $bdd = $this->getBdd();

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


    public function sauvegarderNoteRendu($idEtudiant, $note, $id_rendu, $id_groupe, $isIndividualEvaluation, $id_evaluation, $idEvaluateur, $commentaire)
    {
        $this->modeleRendu->sauvegarderNoteRendu($idEtudiant, $note, $id_rendu, $id_groupe, $isIndividualEvaluation, $id_evaluation, $idEvaluateur, $commentaire);
        $this->mettreAJourNoteFinale($idEtudiant, $id_groupe);
    }
    public function sauvegarderNoteSoutenance($idEtudiant, $note, $id_soutenance, $id_groupe, $isIndividualEvaluation, $id_evaluation, $idEvaluateur, $commentaire)
    {
       $this->modeleSoutenance->sauvegarderNoteSoutenance($idEtudiant, $note, $id_soutenance, $id_groupe, $isIndividualEvaluation, $id_evaluation, $idEvaluateur, $commentaire);
        $this->mettreAJourNoteFinale($idEtudiant, $id_groupe);
    }
    public function getNotesParEvaluation($id_groupe, $id_evaluation, $type_evaluation)
    {
        if ($type_evaluation === 'rendu') {
            return $this->modeleRendu->getNotesParEvaluationRendu($id_groupe, $id_evaluation);
        } elseif ($type_evaluation === 'soutenance') {
            return $this->modeleSoutenance->getNotesParEvaluationSoutenance($id_groupe, $id_evaluation);
        }

        return [];
    }
    public function modifierEvaluationRendu($id_evaluation, $id_groupe, $id_etudiant, $note)
    {
        $this->modeleRendu->modifierEvaluationRendu($id_evaluation, $id_groupe, $id_etudiant, $note);
    }
    public function modifierEvaluationSoutenance($id_evaluation, $id_groupe, $id_etudiant, $note)
    {
        $this->modeleSoutenance->modifierEvaluationSoutenance($id_evaluation, $id_groupe, $id_etudiant, $note);
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