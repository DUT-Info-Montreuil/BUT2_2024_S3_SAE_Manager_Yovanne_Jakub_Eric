<?php

include_once 'modules/professeur/mod_evaluationprof/modele_evaluationprof.php';
include_once 'modules/professeur/mod_evaluationprof/vue_evaluationprof.php';
require_once "ModeleCommun.php";
require_once "ControllerCommun.php";
require_once "TokenManager.php";

class ContEvaluationProf
{
    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        $this->modele = new ModeleEvaluationProf();
        $this->vue = new VueEvaluationProf();
    }

    public function exec()
    {
        $this->action = isset($_GET['action']) ? $_GET['action'] : "gestionEvaluationsSAE";
        if (ControllerCommun::estProfOuIntervenant()) {
            switch ($this->action) {
                case "gestionEvaluationsSAE":
                    $this->gestionEvaluationsSAE();
                    break;
                case "choixNotation" :
                    $this->choixNotation();
                    break;
                case "traitementNotationIndividuelle" :
                    $this->traitementNotationIndividuelle();
                    break;
                case "formEvaluation" :
                    $this->formEvaluation();
                    break;
                case "creerEvaluation" :
                    $this->creerEvaluation();
                    break;
                case "traitementNotationGroupe" :
                    $this->traitementNotationGroupe();
                    break;
                case "modifierEvaluation" :
                    $this->modifierEvaluation();
                    break;
                case "traitementModificationNote" :
                    $this->traitementModificationNote();
                    break;
                case "supprimerEvaluation" :
                    $this->supprimerEvaluation();
                    break;
                case "versModifierEvaluation":
                    $this->versModifierEvaluation();
                    break;
            }
        } else {
            echo "Accès interdit. Vous devez être professeur ou intervenant pour accéder à cette page.";
        }

    }

    public function gestionEvaluationsSAE()
    {
        TokenManager::stockerAndGenerateToken();
        $idSae = isset($_GET['idProjet']) ? $_GET['idProjet'] : NULL;
        $allRendue = $this->modele->getAllRenduSAE($idSae);
        $allSoutenance = $this->modele->getAllSoutenanceSAE($idSae);
        $id_prof = $_SESSION['id_utilisateur'];

        foreach ($allRendue as &$rendue) {
            $rendue['is_evaluateur'] = $rendue['id_evaluation']
                ? $this->modele->estDejaEvaluateur($id_prof, $rendue['id_evaluation'])
                : false;
            $rendue['typeDemande'] = $rendue['id_evaluation'] ? ($rendue['is_evaluateur'] ? 'gestion' : 'voir') : 'creer';
        }
        foreach ($allSoutenance as &$soutenance) {
            $soutenance['is_evaluateur'] = $soutenance['id_evaluation']
                ? $this->modele->estDejaEvaluateur($id_prof, $soutenance['id_evaluation'])
                : false;
            $soutenance['typeDemandeSoutenance'] = $soutenance['id_evaluation'] ? ($soutenance['is_evaluateur'] ? 'gestion' : 'voir') : 'creer';

        }
        $this->vue->afficherTableauAllEvaluation($allRendue, $allSoutenance, $idSae);
    }


    public function versModifierEvaluation()
    {
        if (isset($_POST['id_evaluation'])) {
            $idEvaluation = $_POST['id_evaluation'];
            $idSAE = $_GET['idProjet'];
            $tabAllGerant = $this->modele->getAllGerantSae($idSAE);
            $tabAllGerantNonEvaluateur = $this->modele->getAllGerantNonEvaluateur($idSAE, $idEvaluation);
            $tabAllEvaluateur = $this->modele->getAllEvaluateurSansLePrincipal($idEvaluation);
            $this->vue->formulaireModificationEvaluation($idEvaluation, $tabAllGerant, $tabAllGerantNonEvaluateur, $tabAllEvaluateur, $idSAE);
        }

    }

    public function formEvaluation()
    {
        $idSAE = $_GET['idProjet'];
        if (isset($_POST['id_soutenance'])) {
            $this->handleEvaluationForm('soutenance', $_POST['id_soutenance'], $idSAE);
        } elseif (isset($_POST['id_rendu'])) {
            $this->handleEvaluationForm('rendu', $_POST['id_rendu'], $idSAE);
        }
    }

    private function handleEvaluationForm($type, $id_evaluation, $idSAE)
    {
        if (isset($_POST['type_demande'])) {
            $type_demande = $_POST['type_demande'];
            $validTypes = ['rendu', 'soutenance'];
            if (in_array($type, $validTypes)) {
                switch ($type_demande) {
                    case 'gestion':
                        $this->gestionEvaluations($id_evaluation, $type);
                        break;
                    case 'voir':
                        $this->{"voir".ucfirst($type)}($id_evaluation);
                        break;
                    case 'creer':
                        $this->vue->formulaireCreationEvaluation($id_evaluation, $type, $idSAE);
                        break;
                    default:
                        break;
                }
            } else {
                die("Type d'évaluation invalide.");
            }
        }
    }
    public function voirRendu($id_rendu)
    {
        $idSae = $_GET['idProjet'];
        $rendueEvaluations = $this->modele->getRenduEvaluation($idSae, $id_rendu);
        $idEvaluation = $this->modele->getIdEvaluationByRendu($id_rendu);
        $evaluateurs = $this->modele->getAllEvaluateur($idEvaluation);
        $this->vue->afficherTableauRenduNonGerer($rendueEvaluations, $evaluateurs);
    }

    public function voirSoutenance($id_soutenance)
    {
        $idSae = $_GET['idProjet'];
        $soutenanceEvaluations = $this->modele->getSoutenanceEvaluation($idSae, $id_soutenance);
        $idEvaluation = $this->modele->getIdEvaluationBySoutenance($id_soutenance);
        $evaluateurs = $this->modele->getAllEvaluateur($idEvaluation);
        if (!empty($soutenanceEvaluations)) {
            $this->vue->afficherTableauSoutenanceNonGerer($soutenanceEvaluations, $evaluateurs);
        }
    }

    public function modifierEvaluation()
    {
        if (!TokenManager::verifierToken()) {
            die("Token invalide ou expiré.");
        }
        if (!isset($_POST['id'])) {
            $this->gestionEvaluationsSAE();
            return;
        }
        $id = $_POST['id'];
        $note_max = $_POST['note_max'] ?? null;
        $coefficient = $_POST['coefficient'] ?? null;
        if ($note_max === null || $coefficient === null) {
            $evaluation = $this->modele->getEvaluationById($id);
            $note_max = $note_max ?? $evaluation['note_max'];
            $coefficient = $coefficient ?? $evaluation['coefficient'];
        }
        $this->modele->modifierEvaluation($id, $note_max, $coefficient);
        $this->mettreAJourNotesFinales($id);
        $this->gererDelegationEvaluateurs($id);
        $this->gererEvaluateurs($id);
        $this->gestionEvaluationsSAE();
    }

    /**
     * Met à jour les notes finales des étudiants pour une évaluation donnée.
     */
    private function mettreAJourNotesFinales($idEvaluation)
    {
        $etudiants = $this->modele->getEtudiantsParEvaluation($idEvaluation);

        foreach ($etudiants as $etudiant) {
            $idEtudiant = $etudiant['id_utilisateur'];
            $idGroupe = $etudiant['id_groupe'];
            ModeleCommun::mettreAJourNoteFinale($idEtudiant, $idGroupe);
        }
    }

    /**
     * Gère la délégation des évaluations à un autre évaluateur.
     */
    private function gererDelegationEvaluateurs($idEvaluation)
    {
        if (!isset($_POST['deleguer_evaluation'], $_POST['delegation_action'])) {
            return;
        }

        $idNvEvalueur = $_POST['deleguer_evaluation'];
        $delegation_action = $_POST['delegation_action'];
        $this->modele->modifierEvaluateurPrincipal($idNvEvalueur, $idEvaluation, $delegation_action);
    }

    /**
     * Gère l'ajout et la suppression d'évaluateurs.
     */
    private function gererEvaluateurs($idEvaluation)
    {
        // Ajouter des évaluateurs
        if (!empty($_POST['ajouter_evaluateurs'])) {
            foreach ($_POST['ajouter_evaluateurs'] as $idEvaluateur) {
                if (!$this->modele->estDejaEvaluateur($idEvaluateur, $idEvaluation)) {
                    $this->modele->ajouterEvaluateur($idEvaluateur, $idEvaluation);
                }
            }
        }

        // Supprimer des évaluateurs
        if (!empty($_POST['supprimer_evaluateurs'])) {
            foreach ($_POST['supprimer_evaluateurs'] as $idEvaluateur) {
                $this->modele->supprimerEvaluateur($idEvaluateur, $idEvaluation);
            }
        }
    }

    public function creerEvaluation()
    {
        if (!TokenManager::verifierToken()) {
            die("Token invalide ou expiré.");
        }

        if (!isset($_POST['id'], $_POST['type_evaluation'], $_POST['coefficient'], $_POST['note_max'])) {
            $this->gestionEvaluationsSAE();
            return;
        }

        $id = (int)$_POST['id'];
        $type_evaluation = $_POST['type_evaluation'];
        $coefficient = (float)$_POST['coefficient'];
        $note_max = (float)$_POST['note_max'];
        $evaluateur = $_SESSION['id_utilisateur'];

        $id_evaluation = $this->creerEvaluationParType($type_evaluation, $id, $coefficient, $note_max, $evaluateur);

        if (isset($_POST['criteria'])) {
            $this->ajouterCriteres($_POST['criteria'], $id_evaluation);
        }
        $this->gestionEvaluationsSAE();
    }

    /**
     * Crée une évaluation en fonction de son type.
     */
    private function creerEvaluationParType($type_evaluation, $id, $coefficient, $note_max, $evaluateur)
    {
        switch ($type_evaluation) {
            case 'rendu':
                return $this->modele->creerEvaluationPourRendu($id, $coefficient, $note_max, $evaluateur);
            case 'soutenance':
                return $this->modele->creerEvaluationPourSoutenance($id, $coefficient, $note_max, $evaluateur);
            default:
                throw new InvalidArgumentException("Type d'évaluation invalide : $type_evaluation");
        }
    }

    /**
     * Ajoute des critères à une évaluation donnée.
     */
    private function ajouterCriteres(array $criteres, $id_evaluation)
    {
        foreach ($criteres as $critere) {
            $nom = $critere['nom'] ?? null;
            $description = $critere['description'] ?? null;
            $coefficient = $critere['coefficient'] ?? null;
            $note_max = $critere['note_max'] ?? null;

            if ($nom && $coefficient && $note_max) {
                $this->modele->ajouterCritere($nom, $description, $coefficient, $note_max, $id_evaluation);
            }
        }
    }

    public function gestionEvaluations($id_evaluation, $type)
    {
        $idSae = $_GET['idProjet'];
        $id_evaluateur = $_SESSION['id_utilisateur'];
        if (!in_array($type, ['rendu', 'soutenance'])) {
            throw new InvalidArgumentException("Type d'évaluation invalide : $type");
        }
        $evaluationMethods = [
            'rendu' => [
                'getEvaluations' => 'getRenduEvaluationGerer',
                'getEvaluationById' => 'getEvaluationByIdRendu',
                'afficherTableau' => 'afficherTableauRenduGerer'
            ],
            'soutenance' => [
                'getEvaluations' => 'getSoutenanceEvaluationGerer',
                'getEvaluationById' => 'getEvaluationByIdSoutenance',
                'afficherTableau' => 'afficherTableauSoutenanceGerer'
            ]
        ];
        $methods = $evaluationMethods[$type];
        $evaluations = $this->modele->{$methods['getEvaluations']}($idSae, $id_evaluation, $id_evaluateur);
        $idEvaluation = $this->modele->{$methods['getEvaluationById']}($id_evaluation);
        $tabAllEvaluateurs = $this->modele->getAllEvaluateur($idEvaluation);
        $iAmEvaluateurPrincipal = $this->modele->iAmEvaluateurPrincipal($idEvaluation, $id_evaluateur);
        if (!empty($evaluations)) {
            $this->vue->{$methods['afficherTableau']}($evaluations, $iAmEvaluateurPrincipal, $tabAllEvaluateurs, $idSae);
        }
    }


    public function choixNotation()
    {
        if (isset($_POST['id_groupe'], $_POST['type_evaluation'])) {
            $idSae = $_GET['idProjet'];
            $type_evaluation = $_POST['type_evaluation'];
            $id_groupe = $_POST['id_groupe'];

            $allMembres = $this->modele->getAllMembreSAE($id_groupe);
            $champsRemplis = $this->modele->getChampsRemplisParGroupe($id_groupe);

            $contenue = null;
            $criteres = [];
            $id = $this->getEvaluationId($type_evaluation);

            if ($type_evaluation === 'rendu') {
                $contenue = $this->modele->getFichierRendu($id, $id_groupe);
                $criteres = $this->modele->getCriteresNotationRendu($id);
            } elseif ($type_evaluation === 'soutenance') {
                $criteres = $this->modele->getCriteresNotationSoutenance($id);
            }

            $criteres = $this->transformCritereKeys($criteres);

            if (!isset($_POST['id_evaluation'])) {
                $this->vue->afficherFormulaireNotation($allMembres, $id_groupe, $id, $type_evaluation, $contenue, $champsRemplis, $idSae, $criteres);
            } else {
                $id_evaluation = $_POST['id_evaluation'];
                $notes = $this->modele->getNotesParEvaluation($id_groupe, $id_evaluation, $type_evaluation);
                $this->vue->afficherFormulaireModifierNote($notes, $id_groupe, $id_evaluation, $type_evaluation, $idSae);
            }
        }
    }

    /**
     * Retourne l'ID d'évaluation en fonction du type d'évaluation.
     *
     * @param string $type_evaluation
     * @return int
     */
    private function getEvaluationId(string $type_evaluation): int
    {
        if ($type_evaluation === 'rendu') {
            return $_POST['id_rendu'];
        } elseif ($type_evaluation === 'soutenance') {
            return $_POST['id_soutenance'];
        }

        return 0;
    }

    /**
     * Transforme les clés des critères pour les rendre uniformes.
     *
     * @param array $criteres
     * @return array
     */
    private function transformCritereKeys(array $criteres): array
    {
        foreach ($criteres as &$critere) {
            if (isset($critere['id_critere_rendu'])) {
                $critere['id_critere'] = $critere['id_critere_rendu'];
                unset($critere['id_critere_rendu']);
            }
            if (isset($critere['id_critere_soutenance'])) {
                $critere['id_critere'] = $critere['id_critere_soutenance'];
                unset($critere['id_critere_soutenance']);
            }
        }
        return $criteres;
    }



    public function traitementModificationNote()
    {
        if (isset($_POST['id_groupe'], $_POST['id_evaluation'], $_POST['type_evaluation'], $_POST['notes'])) {
            $id_groupe = $_POST['id_groupe'];
            $id_evaluation = $_POST['id_evaluation'];
            $type_evaluation = $_POST['type_evaluation'];
            $notes = $_POST['notes'];
            $noteMax = $this->getNoteMaxByType($type_evaluation, $id_evaluation);
            $id_evaluateur = $_SESSION['id_utilisateur'];
            foreach ($notes as $id_etudiant => $note) {
                if ($this->isValidNote($note, $noteMax) && $this->iAmEvaluateur($id_evaluation, $id_evaluateur)) {
                    $this->updateNote($id_etudiant, $note, $id_evaluation, $id_groupe, $type_evaluation);
                }
            }
        }
        $this->gestionEvaluationsSAE();
    }

    private function getNoteMaxByType($type_evaluation, $id_evaluation)
    {
        if ($type_evaluation === 'rendu') {
            return $this->modele->infNoteMaxRendu($id_evaluation);
        } else {
            return $this->modele->infNoteMaxSoutenance($id_evaluation);
        }
    }

    private function iAmEvaluateur($id_evaluation, $id_evaluateur)
    {
        return $this->modele->isEvaluateur($id_evaluation, $id_evaluateur);
    }

    private function isValidNote($note, $noteMax)
    {
        $note = (float)$note;
        return is_numeric($note) && $note >= 0 && $note <= $noteMax;
    }

    private function updateNote($id_etudiant, $note, $id_evaluation, $id_groupe, $type_evaluation)
    {
        if ($type_evaluation === 'rendu') {
            $this->modele->modifierEvaluationRendu($id_evaluation, $id_groupe, $id_etudiant, $note);
        } else {
            $this->modele->modifierEvaluationSoutenance($id_evaluation, $id_groupe, $id_etudiant, $note);
        }
    }

    public function traitementNotationIndividuelle()
    {
        if ($this->isValidRequest()) {
            $id_groupe = $_POST['id_groupe'];
            $type_evaluation = $_POST['type_evaluation'];
            $notes = $_POST['notes'];
            $id = $_POST['id'];
            $evaluationData = $this->getEvaluationAndMaxNote($id, $type_evaluation);
            $noteMax = $evaluationData['noteMax'];
            $id_evaluateur = $_SESSION['id_utilisateur'];
            $commentaire = $_POST['commentaire'] ?? null;

            $criteres = $this->getEvaluationCriteres($id, $type_evaluation);

            $this->processNotes($notes, $criteres, $type_evaluation, $id_groupe, $id, $noteMax, $id_evaluateur, $commentaire);

            $this->gestionEvaluationsSAE();
        }
    }

    public function traitementNotationGroupe()
    {
        if ($this->isValidRequest()) {
            $id_groupe = $_POST['id_groupe'];
            $notes = $_POST['notes'];
            $type_evaluation = $_POST['type_evaluation'];
            $id = $_POST['id'];
            $evaluationData = $this->getEvaluationAndMaxNote($id, $type_evaluation);
            $noteMax = $evaluationData['noteMax'];
            $id_evaluateur = $_SESSION['id_utilisateur'];
            $commentaire = $_POST['commentaire'] ?? null;

            $criteres = $this->getEvaluationCriteres($id, $type_evaluation);
            $allMembres = $this->modele->getAllMembreSAE($id_groupe);

            $this->processGroupNotes($notes, $criteres, $type_evaluation, $id_groupe, $id, $noteMax, $id_evaluateur, $commentaire, $allMembres);

            $this->gestionEvaluationsSAE();
        }
    }

    private function isValidRequest(): bool
    {
        return isset($_POST['notes'], $_POST['id'], $_POST['id_groupe'], $_POST['type_evaluation']);
    }

    private function getEvaluationCriteres(int $id, string $type_evaluation): array
    {
        return $type_evaluation === 'rendu'
            ? $this->modele->getCritereRenduById($id)
            : $this->modele->getCritereSoutenanceById($id);
    }

    /**
     * Traite les notes pour une notation individuelle (pour chaque utilisateur).
     *
     */
    private function processNotes($notes, $criteres, $type_evaluation, $id_groupe, $id, $noteMax, $id_evaluateur, $commentaire)
    {
        if (empty($criteres)) {
            foreach ($notes as $idUtilisateur => $noteCriteria) {
                $note = $noteCriteria['default'];
                $this->saveGlobalNote($id_groupe, $id, $idUtilisateur, $type_evaluation, $id_evaluateur, $note, $commentaire);
            }
        } else {
            foreach ($notes as $idUtilisateur => $noteCriteria) {
                foreach ($noteCriteria as $idCritere => $note) {
                    if ($this->isValidNote($note, $noteMax)) {
                        $this->saveCriterionNote($idUtilisateur, $note, $id, $id_groupe, $idCritere, $type_evaluation, $id_evaluateur, $commentaire);
                    }
                }
                $this->saveEvaluationNote($id, $id_groupe, $idUtilisateur, $type_evaluation, $id_evaluateur);
            }
        }
    }

    /**
     * Sauvegarde la note globale pour une évaluation (rendu ou soutenance).
     *
     */
    private function saveGlobalNote($id_groupe, $id, $idUtilisateur, $type_evaluation, $id_evaluateur, $note, $commentaire)
    {
        if ($type_evaluation === 'rendu') {
            $id_evaluation = $this->modele->getIdEvaluationByRendu($id);
            $this->modele->sauvegarderNoteGlobaleRendu($id_groupe, $id, $idUtilisateur, $id_evaluation, $id_evaluateur, $note, $commentaire);
        } else {
            $id_evaluation = $this->modele->getIdEvaluationBySoutenance($id);
            $this->modele->sauvegarderNoteGlobaleSoutenance($id_groupe, $id, $idUtilisateur, $id_evaluation, $id_evaluateur, $note, $commentaire);
        }
    }

    /**
     * Sauvegarde la note d'un critère spécifique pour un utilisateur.
     *
     */
    private function saveCriterionNote($idUtilisateur, $note, $id, $id_groupe, $idCritere, $type_evaluation, $id_evaluateur, $commentaire)
    {
        if ($type_evaluation === 'rendu') {
            $id_evaluation = $this->modele->getIdEvaluationByRendu($id);
            if ($this->iAmEvaluateur($id_evaluation, $id_evaluateur)) {
                $this->modele->sauvegarderNoteRenduCritere($idUtilisateur, $note, $id, $id_groupe, $idCritere, $id_evaluation, $id_evaluateur, $commentaire);
            }
        } else {
            $id_evaluation = $this->modele->getIdEvaluationBySoutenance($id);
            if ($this->iAmEvaluateur($id_evaluation, $id_evaluateur)) {
                $this->modele->sauvegarderNoteSoutenanceCritere($idUtilisateur, $note, $id, $id_groupe, $idCritere, $id_evaluation, $id_evaluateur, $commentaire);
            }
        }
    }

    /**
     * Sauvegarde la note de l'évaluation (rendu ou soutenance) pour un utilisateur.
     *
     */
    private function saveEvaluationNote(int $id, int $id_groupe, int $idUtilisateur, string $type_evaluation, int $id_evaluateur)
    {
        if ($type_evaluation === 'rendu') {
            $id_evaluation = $this->modele->getIdEvaluationByRendu($id);
            $this->modele->sauvegarderNoteRenduEvaluation($id, $id_groupe, $id_evaluation, $idUtilisateur, $id_evaluateur);
        } else {
            $id_evaluation = $this->modele->getIdEvaluationBySoutenance($id);
            $this->modele->sauvegarderNoteSoutenanceEvaluation($id, $id_groupe, $id_evaluation, $idUtilisateur, $id_evaluateur);
        }
    }

    /**
     * Traite les notes pour un groupe d'utilisateurs.
     *
     */
    private function processGroupNotes($notes, $criteres, $type_evaluation, $id_groupe, $id, $noteMax, $id_evaluateur, $commentaire, $allMembres)
    {
        if (empty($criteres)) {
            $note = $_POST['notes']['default'];
            foreach ($allMembres as $membre) {
                $idUtilisateur = $membre['id_utilisateur'];
                $this->saveGlobalNote($id_groupe, $id, $idUtilisateur, $type_evaluation, $id_evaluateur, $note, $commentaire);
            }
        } else {
            foreach ($allMembres as $membre) {
                foreach ($notes as $idCritere => $noteCriteria) {
                    if ($this->isValidNote($noteCriteria, $noteMax)) {
                        $this->saveCriterionNote($membre['id_utilisateur'], $noteCriteria, $id, $id_groupe, $idCritere, $type_evaluation, $id_evaluateur, $commentaire);
                    }
                }
                $this->saveEvaluationNote($id, $id_groupe, $membre['id_utilisateur'], $type_evaluation, $id_evaluateur);
            }
        }
    }


    private function getEvaluationAndMaxNote($id, $type_evaluation)

    {
        if ($type_evaluation === 'rendu') {
            $id_evaluation = $this->modele->getIdEvaluationByRendu($id);
            $noteMax = $this->modele->infNoteMaxRendu($id_evaluation);
        } else {
            $id_evaluation = $this->modele->getIdEvaluationBySoutenance($id);
            $noteMax = $this->modele->infNoteMaxSoutenance($id_evaluation);
        }

        return ['id_evaluation' => $id_evaluation, 'noteMax' => $noteMax];
    }

    public function supprimerEvaluation()
    {
        if (!TokenManager::verifierToken()) {
            die("Token invalide ou expiré.");
        }
        if (isset($_POST['id_evaluation'])) {
            $id_evaluation = $_POST['id_evaluation'];
            $etudiants = $this->modele->getEtudiantsParEvaluation($id_evaluation);
            $this->modele->supprimerEvaluation($id_evaluation);

            foreach ($etudiants as $etudiant) {
                $idEtudiant = $etudiant['id_utilisateur'];
                $idGroupe = $etudiant['id_groupe'];
                ModeleCommun::mettreAJourNoteFinale($idEtudiant, $idGroupe);
            }
        }
        $this->gestionEvaluationsSAE();
    }

}