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
            $id_soutenance = $_POST['id_soutenance'];
            if (isset($_POST['type_demande'])) {
                $type_demande = $_POST['type_demande'];
                if ($type_demande === "gestion") {
                    $this->gestionEvaluationsSoutenance($id_soutenance);
                } else if ($type_demande === "voir") {
                    $this->voirSoutenance($id_soutenance);
                } else if ($type_demande === "creer") {
                    $this->vue->formulaireCreationEvaluation($id_soutenance, 'soutenance', $idSAE);
                }

            }
        } else if (isset($_POST['id_rendu'])) {
            $id_rendu = $_POST['id_rendu'];
            if (isset($_POST['type_demande'])) {
                $type_demande = $_POST['type_demande'];
                if ($type_demande === "gestion") {
                    $this->gestionEvaluationsRendu($id_rendu);
                } else if ($type_demande === "voir") {
                    $this->voirUnRendu($id_rendu);
                } else if ($type_demande === "creer") {
                    $this->vue->formulaireCreationEvaluation($id_rendu, 'rendu', $idSAE);
                }
            }
        }
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

    public function voirUnRendu($id_rendu)
    {
        $idSae = $_GET['idProjet'];
        $rendueEvaluations = $this->modele->getRenduEvaluation($idSae, $id_rendu);
        $idEvaluation = $this->modele->getIdEvaluationByRendu($id_rendu);
        $evaluateurs = $this->modele->getAllEvaluateur($idEvaluation);
        $this->vue->afficherTableauRenduNonGerer($rendueEvaluations, $evaluateurs);
    }


    public function modifierEvaluation()
    {
        if (!TokenManager::verifierToken()) {
            die("Token invalide ou expiré.");
        }
        if (isset($_POST['id'])) {
            $id = $_POST['id'];

            $note_max = null;
            $coefficient = null;

            if (isset($_POST['note_max']) && $_POST['note_max'] !== '') {
                $note_max = $_POST['note_max'];
            }

            if (isset($_POST['coefficient']) && $_POST['coefficient'] !== '') {
                $coefficient = $_POST['coefficient'];
            }

            if ($note_max === null || $coefficient === null) {
                $evaluation = $this->modele->getEvaluationById($id);
                if ($note_max === null) {
                    $note_max = $evaluation['note_max'];
                }
                if ($coefficient === null) {
                    $coefficient = $evaluation['coefficient'];
                }
            }

            $this->modele->modifierEvaluation($id, $note_max, $coefficient);

            $etudiants = $this->modele->getEtudiantsParEvaluation($id);

            foreach ($etudiants as $etudiant) {
                $idEtudiant = $etudiant['id_utilisateur'];
                $idGroupe = $etudiant['id_groupe'];
                ModeleCommun::mettreAJourNoteFinale($idEtudiant, $idGroupe);
            }

            if (isset($_POST['deleguer_evaluation']) && !empty($_POST['deleguer_evaluation']) && isset($_POST['delegation_action'])) {
                $idNvEvalueur = $_POST['deleguer_evaluation'];
                $delegation_action = $_POST['delegation_action'];
                $this->modele->modifierEvaluateurPrincipal($idNvEvalueur, $id, $delegation_action);
            }

            if (isset($_POST['ajouter_evaluateurs']) && !empty($_POST['ajouter_evaluateurs'])) {
                $ajouterEvaluateurs = $_POST['ajouter_evaluateurs'];
                foreach ($ajouterEvaluateurs as $idEvaluateur) {
                    if (!$this->modele->estDejaEvaluateur($idEvaluateur, $id)) {
                        $this->modele->ajouterEvaluateur($idEvaluateur, $id);
                    }
                }
            }

            if (isset($_POST['supprimer_evaluateurs']) && !empty($_POST['supprimer_evaluateurs'])) {
                $supprimerEvaluateurs = $_POST['supprimer_evaluateurs'];
                foreach ($supprimerEvaluateurs as $idEvaluateur) {
                    $this->modele->supprimerEvaluateur($idEvaluateur, $id);
                }
            }
        }
        $this->gestionEvaluationsSAE();
    }


    public function creerEvaluation()
    {
        if (!TokenManager::verifierToken()) {
            die("Token invalide ou expiré.");
        }

        if (isset($_POST['id'], $_POST['type_evaluation'], $_POST['coefficient'], $_POST['note_max'])) {
            $id = (int)$_POST['id'];
            $type_evaluation = $_POST['type_evaluation'];
            $coefficient = (float)$_POST['coefficient'];
            $note_max = (float)$_POST['note_max'];
            $evaluateur = $_SESSION['id_utilisateur'];

            if ($type_evaluation === 'rendu') {
                $id_evaluation = $this->modele->creerEvaluationPourRendu($id, $coefficient, $note_max, $evaluateur);
            } elseif ($type_evaluation === 'soutenance') {
                $id_evaluation = $this->modele->creerEvaluationPourSoutenance($id, $coefficient, $note_max, $evaluateur);
            }

            if (isset($_POST['criteria'])) {
                $criteres = $_POST['criteria'];
                foreach ($criteres as $critere) {
                    $this->modele->ajouterCritere($critere['nom'], $critere['description'], $critere['coefficient'], $critere['note_max'], $id_evaluation);
                }
            }
        }
        $this->gestionEvaluationsSAE();

    }


    public function gestionEvaluationsRendu($id_rendu)
    {
        $idSae = $_GET['idProjet'];
        $id_evaluateur = $_SESSION['id_utilisateur'];
        $rendueEvaluations = $this->modele->getRenduEvaluationGerer($idSae, $id_rendu, $id_evaluateur);
        $idEvaluation = $this->modele->getEvaluationByIdRendu($id_rendu);
        $tabAllEvaluateurs = $this->modele->getAllEvaluateur($idEvaluation);
        $iAmEvaluateurPrincipal = $this->modele->iAmEvaluateurPrincipal($idEvaluation, $id_evaluateur);
        if (!empty($rendueEvaluations)) {
            $this->vue->afficherTableauRenduGerer($rendueEvaluations, $tabAllEvaluateurs, $idSae, $iAmEvaluateurPrincipal);
        }

    }

    public function gestionEvaluationsSoutenance($id_soutenance)
    {
        $idSae = $_GET['idProjet'];
        $id_evaluateur = $_SESSION['id_utilisateur'];
        $soutenanceEvaluations = $this->modele->getSoutenanceEvaluationGerer($idSae, $id_soutenance, $id_evaluateur);
        $idEvaluation = $this->modele->getEvaluationByIdSoutenance($id_soutenance);
        $tabAllEvaluateurs = $this->modele->getAllEvaluateur($idEvaluation);
        $iAmEvaluateurPrincipal = $this->modele->iAmEvaluateurPrincipal($idEvaluation, $id_evaluateur);
        if (!empty($soutenanceEvaluations)) {
            $this->vue->afficherTableauSoutenanceGerer($soutenanceEvaluations, $iAmEvaluateurPrincipal, $tabAllEvaluateurs, $idSae);
        }

    }

    public function choixNotation()
    {
        if (isset($_POST['id_groupe']) && isset($_POST['type_evaluation'])) {
            $idSae = $_GET['idProjet'];
            $type_evaluation = $_POST['type_evaluation'];
            $id_groupe = $_POST['id_groupe'];
            $allMembres = $this->modele->getAllMembreSAE($id_groupe);
            $champsRemplis = $this->modele->getChampsRemplisParGroupe($id_groupe);

            $contenue = null;
            $criteres = [];
            if ($type_evaluation === 'rendu') {
                $id = $_POST['id_rendu'];
                $contenue = $this->modele->getFichierRendu($id, $id_groupe);
                $criteres = $this->modele->getCriteresNotationRendu($id);
            } else {
                $id = $_POST['id_soutenance'];
                $criteres = $this->modele->getCriteresNotationSoutenance($id);
            }

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


            if (!isset($_POST['id_evaluation'])) {
                $this->vue->afficherFormulaireNotation($allMembres, $id_groupe, $id, $type_evaluation, $contenue, $champsRemplis, $idSae, $criteres);
            } else {
                $id_evaluation = $_POST['id_evaluation'];
                $notes = $this->modele->getNotesParEvaluation($id_groupe, $id_evaluation, $type_evaluation);
                $this->vue->afficherFormulaireModifierNote($notes, $id_groupe, $id_evaluation, $type_evaluation, $idSae);
            }
        }
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

    public function iAmEvaluateur($id_evaluation, $id_evaluateur)
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
        if (isset($_POST['notes'], $_POST['id'], $_POST['id_groupe'], $_POST['type_evaluation'])) {
            $id_groupe = $_POST['id_groupe'];
            $type_evaluation = $_POST['type_evaluation'];
            $notes = $_POST['notes'];
            $id = $_POST['id'];
            $evaluationData = $this->getEvaluationAndMaxNote($id, $type_evaluation);
            $noteMax = $evaluationData['noteMax'];
            $id_evaluateur = $_SESSION['id_utilisateur'];
            $commentaire = isset($_POST['commentaire']) ? $_POST['commentaire'] : null;
            if ($type_evaluation == 'rendu') {
                $criteres = $this->modele->getCritereRenduById($id);
            } else {
                $criteres = $this->modele->getCritereSoutenanceById($id);
            }

            if (empty($criteres)) {
                foreach ($notes as $idUtilisateur => $noteCriteria) {
                    $note = $noteCriteria['default'];
                    $commentaire = $_POST['commentaire'] ?? '';

                    if ($type_evaluation === 'rendu') {
                        $id_evaluation = $this->modele->getIdEvaluationByRendu($id);
                        $idRendu = $_POST['id'];
                        $this->modele->sauvegarderNoteGlobaleRendu($id_groupe, $idRendu, $idUtilisateur, $id_evaluation, $id_evaluateur, $note, $commentaire);
                    } elseif ($type_evaluation === 'soutenance') {
                        $id_evaluation = $this->modele->getIdEvaluationBySoutenance($id);
                        $this->modele->sauvegarderNoteGlobaleSoutenance($id_groupe, $id, $idUtilisateur, $id_evaluation, $id_evaluateur, $note, $commentaire);
                    }
                }
            } else {
                foreach ($notes as $idUtilisateur => $noteCriteria) {
                    foreach ($noteCriteria as $idCritere => $note) {
                        if ($this->isValidNote($note, $noteMax)) {
                            if ($type_evaluation === 'rendu') {
                                $id_evaluation = $this->modele->getIdEvaluationByRendu($id);
                                if ($this->iAmEvaluateur($id_evaluation, $id_evaluateur)) {
                                    $this->modele->sauvegarderNoteRenduCritere(
                                        (int)$idUtilisateur,
                                        (float)$note,
                                        $id,
                                        $id_groupe,
                                        $idCritere,
                                        $id_evaluation,
                                        $id_evaluateur,
                                        $commentaire
                                    );
                                }
                            } else {
                                $id_evaluation = $this->modele->getIdEvaluationBySoutenance($id);
                                if ($this->iAmEvaluateur($id_evaluation, $id_evaluateur)) {
                                    $this->modele->sauvegarderNoteSoutenanceCritere(
                                        (int)$idUtilisateur,
                                        (float)$note,
                                        $id,
                                        $id_groupe,
                                        $idCritere,
                                        $id_evaluation,
                                        $id_evaluateur,
                                        $commentaire
                                    );
                                }
                            }
                        }
                    }

                    if ($type_evaluation === 'rendu') {
                        $this->modele->sauvegarderNoteRenduEvaluation($id, $id_groupe, $id_evaluation, $idUtilisateur, $id_evaluateur);
                    } else {
                        $this->modele->sauvegarderNoteSoutenanceEvaluation($id, $id_groupe, $id_evaluation, $idUtilisateur, $id_evaluateur);
                    }
                }
            }

            $this->gestionEvaluationsSAE();
        }
    }


    public function traitementNotationGroupe()
    {
        if (isset($_POST['notes'], $_POST['id'], $_POST['id_groupe'], $_POST['type_evaluation'])) {
            $id_groupe = $_POST['id_groupe'];
            $notes = $_POST['notes'];
            $type_evaluation = $_POST['type_evaluation'];
            $id = $_POST['id'];

            $evaluationData = $this->getEvaluationAndMaxNote($id, $type_evaluation);
            $noteMax = $evaluationData['noteMax'];
            $id_evaluateur = $_SESSION['id_utilisateur'];
            $commentaire = isset($_POST['commentaire']) ? $_POST['commentaire'] : null;

            if ($type_evaluation == 'rendu') {
                $criteres = $this->modele->getCritereRenduById($id);
            } else {
                $criteres = $this->modele->getCritereSoutenanceById($id);
            }
            $allMembres = $this->modele->getAllMembreSAE($id_groupe);

            if (empty($criteres)) {
                $note = $_POST['notes']['default'];
                $commentaire = $_POST['commentaire'] ?? '';

                if ($type_evaluation === 'rendu') {
                    $id_evaluation = $this->modele->getIdEvaluationByRendu($id);
                    foreach ($allMembres as $membre) {
                        $idUtilisateur = $membre['id_utilisateur'];
                        $this->modele->sauvegarderNoteGlobaleRendu($id_groupe, $id, $idUtilisateur, $id_evaluation, $id_evaluateur, $note, $commentaire);
                    }
                } else if ($type_evaluation === 'soutenance') {
                    $id_evaluation = $this->modele->getIdEvaluationBySoutenance($id);
                    foreach ($allMembres as $membre) {
                        $idUtilisateur = $membre['id_utilisateur'];
                        $this->modele->sauvegarderNoteGlobaleSoutenance($id_groupe, $id, $idUtilisateur, $id_evaluation, $id_evaluateur, $note, $commentaire);
                    }
                }
            } else {
                foreach ($allMembres as $membre) {
                    foreach ($notes as $idCritere => $noteCriteria) {
                        if ($this->isValidNote($noteCriteria, $noteMax)) {
                            if ($type_evaluation === 'rendu') {
                                $id_evaluation = $this->modele->getIdEvaluationByRendu($id);
                                if ($this->iAmEvaluateur($id_evaluation, $id_evaluateur)) {
                                    $this->modele->sauvegarderNoteRenduCritere(
                                        $membre['id_utilisateur'],
                                        $noteCriteria,
                                        $id,
                                        $id_groupe,
                                        $idCritere,
                                        $id_evaluation,
                                        $id_evaluateur,
                                        $commentaire
                                    );
                                }
                            } else {
                                $id_evaluation = $this->modele->getIdEvaluationBySoutenance($id);
                                if ($this->iAmEvaluateur($id_evaluation, $id_evaluateur)) {
                                    $this->modele->sauvegarderNoteSoutenanceCritere(
                                        $membre['id_utilisateur'],
                                        $noteCriteria,
                                        $id,
                                        $id_groupe,
                                        $idCritere,
                                        $id_evaluation,
                                        $id_evaluateur,
                                        $commentaire
                                    );
                                }
                            }

                        }
                    }
                    if ($type_evaluation === 'rendu') {
                        $this->modele->sauvegarderNoteRenduEvaluation($id, $id_groupe, $id_evaluation, $membre['id_utilisateur'], $id_evaluateur);
                    } else {
                        $this->modele->sauvegarderNoteSoutenanceEvaluation($id, $id_groupe, $id_evaluation, $membre['id_utilisateur'], $id_evaluateur);
                    }

                }
            }
        }

        $this->gestionEvaluationsSAE();
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