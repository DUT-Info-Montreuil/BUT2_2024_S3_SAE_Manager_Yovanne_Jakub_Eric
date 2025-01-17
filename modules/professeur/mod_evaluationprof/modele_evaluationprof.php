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
    public function __call($method, $arguments)
    {
        $renduMethods = [
            'getEvaluationByIdRendu',
            'getCriteresNotationRendu',
            'infNoteMaxRendu',
            'sauvegarderNoteGlobaleRendu',
            'getIdEvaluationByRendu',
            'getRenduEvaluationGerer',
            'getRenduEvaluation',
            'getAllRenduSAE',
            'getCriteresNotationRendu',
            'sauvegarderNoteRenduCritere',
            'sauvegarderNoteRenduEvaluation',
            'modifierEvaluationRendu'
        ];

        $soutenanceMethods = [
            'getCriteresNotationSoutenance',
            'getEvaluationByIdSoutenance',
            'sauvegarderNoteGlobaleSoutenance',
            'getIdEvaluationBySoutenance',
            'infNoteMaxSoutenance',
            'getSoutenanceEvaluation',
            'getEvaluationByIdSoutenance',
            'getSoutenanceEvaluationGerer',
            'getAllSoutenanceSAE',
            'getCriteresNotationSoutenance',
            'sauvegarderNoteSoutenanceCritere',
            'sauvegarderNoteSoutenanceEvaluation',
            'modifierEvaluationSoutenance'
        ];
        if (in_array($method, $renduMethods)) {
            if (method_exists($this->modeleRendu, $method)) {
                return call_user_func_array([$this->modeleRendu, $method], $arguments);
            }
        }

        if (in_array($method, $soutenanceMethods)) {
            if (method_exists($this->modeleSoutenance, $method)) {
                return call_user_func_array([$this->modeleSoutenance, $method], $arguments);
            }
        }
        throw new BadMethodCallException("La méthode $method n'existe pas");
    }
    public function getFichierRendu($idRendu, $idGroupe)
    {
        $bdd = $this->getBdd();
        $query = "SELECT * FROM Rendu_Fichier WHERE id_groupe = ? AND id_rendu = ?";
        $statement = $bdd->prepare($query);
        $statement->execute([$idGroupe, $idRendu]);
        return $statement->fetchAll();

    }
    public function getCritereRenduById($id_rendu)
    {
        $bdd = $this->getBdd();
        $query = "
        SELECT 
            c.id_critere,
            c.nom_critere,
            c.description,
            c.coefficient,
            c.note_max,
            c.id_evaluation
        FROM Critere c
        INNER JOIN Rendu r ON c.id_evaluation = r.id_evaluation
        WHERE r.id_rendu = ?
    ";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$id_rendu]);
        $criteres = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $criteres;
    }
    public function getCritereSoutenanceById($id_soutenance)
    {
        $bdd = $this->getBdd();
        $query = "
        SELECT 
            c.id_critere,
            c.nom_critere,
            c.description,
            c.coefficient,
            c.note_max,
            c.id_evaluation
        FROM Critere c
        INNER JOIN Soutenance s ON c.id_evaluation = s.id_evaluation
        WHERE s.id_soutenance = ?
    ";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$id_soutenance]);
        $criteres = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $criteres;
    }
    public function getAllGerantNonEvaluateur($idSAE, $idEvaluation)
    {
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
    public function getAllEvaluateur($idEvaluation)
    {
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
    public function getEtudiantsParEvaluation($idEvaluation)
    {
        $bdd = static::getBdd();
        $query = "
        SELECT GE.id_utilisateur, GE.id_groupe
        FROM Groupe_Etudiant GE
        JOIN Rendu_Evaluation RE ON GE.id_utilisateur = RE.id_etudiant
        WHERE RE.id_evaluation = ?
    ";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idEvaluation]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getEvaluationById($id)
    {
        $bdd = $this->getBdd();
        $query = "SELECT * FROM Evaluation WHERE id_evaluation = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getAllGerantSae($idSAE)
    {
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
    public function modifierEvaluation($idEvaluation, $note_max, $coefficient)
    {
        $bdd = $this->getBdd();
        $query = "UPDATE Evaluation SET note_max = ? , coefficient = ? WHERE id_evaluation = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$note_max, $coefficient, $idEvaluation]);
    }
    public function modifierEvaluateurPrincipal($idNvEvalueur, $idEvaluation, $delegation_action)
    {
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
    public function creerEvaluationPourRendu($id_rendu, $coefficient, $note_max, $evaluateur)
    {
        $id_evaluation = $this->modeleRendu->creerEvaluationPourRendu($id_rendu, $coefficient, $note_max, $evaluateur);
        $this->insererEvaluateur($id_evaluation, $evaluateur, true);
        return $id_evaluation;
    }
    public function creerEvaluationPourSoutenance($id_soutenance, $coefficient, $note_max, $evaluateur)
    {
        $id_evaluation = $this->modeleSoutenance->creerEvaluationPourSoutenance($id_soutenance, $coefficient, $note_max, $evaluateur);
        $this->insererEvaluateur($id_evaluation, $evaluateur, true);
        return $id_evaluation;

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
        $bdd = $this->getBdd();
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
    public function getNotesParEvaluation($id_groupe, $id_evaluation, $type_evaluation)
    {
        if ($type_evaluation === 'rendu') {
            return $this->modeleRendu->getNotesParEvaluationRendu($id_groupe, $id_evaluation);
        } elseif ($type_evaluation === 'soutenance') {
            return $this->modeleSoutenance->getNotesParEvaluationSoutenance($id_groupe, $id_evaluation);
        }

        return [];
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
    public function ajouterCritere($nom, $description, $coefficient, $note_max, $idEvaluation)
    {
        $bdd = $this->getBdd();
        $sql = "INSERT INTO Critere (nom_critere, description, coefficient, note_max, id_evaluation)
            VALUES (?, ? , ? , ?, ?)";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([$nom, $description, $coefficient, $note_max, $idEvaluation]);
    }
}