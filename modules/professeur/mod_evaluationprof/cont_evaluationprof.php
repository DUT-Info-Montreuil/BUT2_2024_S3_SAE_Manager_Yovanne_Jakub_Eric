<?php

include_once 'modules/professeur/mod_evaluationprof/modele_evaluationprof.php';
include_once 'modules/professeur/mod_evaluationprof/vue_evaluationprof.php';
require_once "ModeleCommun.php";
require_once "ControllerCommun.php";
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
        }else{
            echo "Accès interdit. Vous devez être professeur ou intervenant pour accéder à cette page.";
        }

    }
    public function gestionEvaluationsSAE()
    {
        $idSae = $_SESSION['id_projet'];
        $allRendue = $this->modele->getAllRenduSAE($idSae);
        $allSoutenance = $this->modele->getAllSoutenanceSAE($idSae);
        $id_prof = $_SESSION['id_utilisateur'];

        foreach ($allRendue as &$rendue) {
            $rendue['is_evaluateur'] = $rendue['id_evaluation']
                ? $this->modele->estDejaEvaluateur($id_prof, $rendue['id_evaluation'])
                : false;
        }

        foreach ($allSoutenance as &$soutenance) {
            $soutenance['is_evaluateur'] = $soutenance['id_evaluation']
                ? $this->modele->estDejaEvaluateur($id_prof, $soutenance['id_evaluation'])
                : false;
        }

        $this->vue->afficherTableauAllEvaluation($allRendue, $allSoutenance);
    }



    public function versModifierEvaluation(){
        if(isset($_POST['id_evaluation'])){
            $idEvaluation = $_POST['id_evaluation'];
            $idSAE = $_SESSION['id_projet'];
            $tabAllGerant = $this->modele->getAllGerantSae($idSAE);
            $tabAllGerantNonEvaluateur = $this->modele->getAllGerantNonEvaluateur($idSAE, $idEvaluation);
            $tabAllEvaluateur= $this->modele->getAllEvaluateurSansLePrincipal($idEvaluation);
            $this->vue->formulaireModificationEvaluation($idEvaluation, $tabAllGerant, $tabAllGerantNonEvaluateur, $tabAllEvaluateur);
        }

    }
    public function formEvaluation()
    {
        if (isset($_POST['id_soutenance'])) {
            $id_soutenance = $_POST['id_soutenance'];
            if (isset($_POST['type_demande'])) {
                $type_demande = $_POST['type_demande'];
                if ($type_demande === "gestion") {
                    $this->gestionEvaluationsSoutenance($id_soutenance);
                } else if ($type_demande === "voir") {
                    $this->voirSoutenance($id_soutenance);
                } else if ($type_demande === "creer") {
                    $this->vue->formulaireCreationEvaluation($id_soutenance, 'soutenance');
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
                    $this->vue->formulaireCreationEvaluation($id_rendu, 'rendu');
                }
            }
        }
    }


    public function voirSoutenance($id_soutenance){
        $idSae = $_SESSION['id_projet'];
        $soutenanceEvaluations = $this->modele->getSoutenanceEvaluation($idSae, $id_soutenance);
        $idEvaluation = $this->modele->getIdEvaluationBySoutenance($id_soutenance);
        $evaluateurs = $this->modele->getAllEvaluateur($idEvaluation);
        $this->vue->afficherTableauSoutenanceNonGerer($soutenanceEvaluations, $evaluateurs);
    }

    public function voirUnRendu($id_rendu){
        $idSae = $_SESSION['id_projet'];
        $rendueEvaluations = $this->modele->getRenduEvaluation($idSae, $id_rendu);
        $idEvaluation = $this->modele->getIdEvaluationByRendu($id_rendu);
        $evaluateurs = $this->modele->getAllEvaluateur($idEvaluation);
        $this->vue->afficherTableauRenduNonGerer($rendueEvaluations, $evaluateurs);
    }


    public function modifierEvaluation()
    {
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
        if (isset($_POST['id'], $_POST['type_evaluation'], $_POST['coefficient'], $_POST['note_max'])) {
            $id = (int)$_POST['id'];
            $type_evaluation = $_POST['type_evaluation'];
            $coefficient = (float)$_POST['coefficient'];
            $note_max = (float)$_POST['note_max'];
            $evaluateur = $_SESSION['id_utilisateur'];

            if ($type_evaluation === 'rendu') {
                $this->modele->creerEvaluationPourRendu($id, $coefficient, $note_max, $evaluateur);
            } elseif ($type_evaluation === 'soutenance') {
                $this->modele->creerEvaluationPourSoutenance($id, $coefficient, $note_max, $evaluateur);
            }
        }
        $this->gestionEvaluationsSAE();
    }

    public function gestionEvaluationsRendu($id_rendu)
    {
        $idSae = $_SESSION['id_projet'];
        $id_evaluateur = $_SESSION['id_utilisateur'];
        $rendueEvaluations = $this->modele->getRenduEvaluationGerer($idSae, $id_rendu, $id_evaluateur);
        $idEvaluation = $this->modele->getEvaluationByIdRendu($id_rendu);
        $tabAllEvaluateurs = $this->modele->getAllEvaluateur($idEvaluation);
        $iAmEvaluateurPrincipal = $this->modele->iAmEvaluateurPrincipal($idEvaluation, $id_evaluateur);
        $this->vue->afficherTableauRenduGerer($rendueEvaluations, $iAmEvaluateurPrincipal, $tabAllEvaluateurs);
    }

    public function gestionEvaluationsSoutenance($id_soutenance)
    {
        $idSae = $_SESSION['id_projet'];
        $id_evaluateur = $_SESSION['id_utilisateur'];
        $soutenanceEvaluations = $this->modele->getSoutenanceEvaluationGerer($idSae, $id_soutenance, $id_evaluateur);
        $idEvaluation = $this->modele->getEvaluationByIdSoutenance($id_soutenance);
        $tabAllEvaluateurs = $this->modele->getAllEvaluateur($idEvaluation);
        $iAmEvaluateurPrincipal = $this->modele->iAmEvaluateurPrincipal($idEvaluation, $id_evaluateur);
        $this->vue->afficherTableauSoutenanceGerer($soutenanceEvaluations, $iAmEvaluateurPrincipal, $tabAllEvaluateurs);
    }

    public function choixNotation()
    {
        if (isset($_POST['id_groupe']) && isset($_POST['type_evaluation'])) {
            $type_evaluation = $_POST['type_evaluation'];
            $id_groupe = $_POST['id_groupe'];
            $allMembres = $this->modele->getAllMembreSAE($id_groupe);
            $champsRemplis = $this->modele->getChampsRemplisParGroupe($id_groupe);

            $contenue = null;

            if ($type_evaluation === 'rendu') {
                $id = $_POST['id_rendu'];
                $contenue = $this->modele->getFichierRendu($id, $id_groupe);
            } else {
                $id = $_POST['id_soutenance'];
            }

            if (!isset($_POST['id_evaluation'])) {
                $this->vue->afficherFormulaireNotation($allMembres, $id_groupe, $id, $type_evaluation, $contenue, $champsRemplis);
            } else {
                $id_evaluation = $_POST['id_evaluation'];
                $notes = $this->modele->getNotesParEvaluation($id_groupe, $id_evaluation, $type_evaluation);
                $this->vue->afficherFormulaireModifierNote($notes, $id_groupe, $id_evaluation, $type_evaluation);
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
        return is_numeric($note) && $note >= 0 && $note <= $noteMax;
    }

    private function updateNote($id_etudiant, $note, $id_evaluation, $id_groupe, $type_evaluation)
    {
        if ($type_evaluation === 'rendu') {
            $this->modele->modifierEvaluationRendu($id_evaluation, $id_groupe, $id_etudiant, $note);
        } else {
            echo "soutenance";
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
            $commentaire = null;
            if(isset($_POST['commentaire'])) {
                $commentaire = $_POST['commentaire'];
            }
            foreach ($notes as $idUtilisateur => $note) {
                if ($this->isValidNote($note, $noteMax)) {
                    if ($type_evaluation === 'rendu') {
                        $id_evaluation = $this->modele->getIdEvaluationByRendu($id);
                        if ($this->iAmEvaluateur($id_evaluation, $id_evaluateur)) {
                            $this->modele->sauvegarderNoteRendu((int)$idUtilisateur, (float)$note, $id, $id_groupe, 1, $id_evaluation, $id_evaluateur, $commentaire);
                        }
                    } else {
                        $id_evaluation = $this->modele->getIdEvaluationBySoutenance($id);
                        if ($this->iAmEvaluateur($id_evaluation, $id_evaluateur)) {
                            $this->modele->sauvegarderNoteSoutenance((int)$idUtilisateur, (float)$note, $id, $id_groupe, 1, $id_evaluation, $id_evaluateur, $commentaire);
                        }
                    }
                }
            }
        }
        $this->gestionEvaluationsSAE();
    }


    public function traitementNotationGroupe()
    {
        if (isset($_POST['note_groupe'], $_POST['id'], $_POST['id_groupe'], $_POST['type_evaluation'])) {
            $id_groupe = $_POST['id_groupe'];
            $note_groupe = $_POST['note_groupe'];
            $type_evaluation = $_POST['type_evaluation'];
            $id = $_POST['id'];
            $evaluationData = $this->getEvaluationAndMaxNote($id, $type_evaluation);
            $noteMax = $evaluationData['noteMax'];
            $id_evaluateur = $_SESSION['id_utilisateur'];
            $commentaire = null;
            if(isset($_POST['commentaire'])) {
                $commentaire = $_POST['commentaire'];
            }
            if ($this->isValidNote($note_groupe, $noteMax)) {
                $allMembres = $this->modele->getAllMembreSAE($id_groupe);
                foreach ($allMembres as $membre) {
                    if ($type_evaluation === 'rendu') {
                        $id_evaluation = $this->modele->getIdEvaluationByRendu($id);
                        if ($this->iAmEvaluateur($id_evaluation, $id_evaluateur)) {
                            $this->modele->sauvegarderNoteRendu($membre['id_utilisateur'], $note_groupe, $id, $id_groupe, 0, $id_evaluation, $id_evaluateur, $commentaire);
                        }
                    } else {
                        $id_evaluation = $this->modele->getIdEvaluationBySoutenance($id);
                        if ($this->iAmEvaluateur($id_evaluation, $id_evaluateur)) {
                            $this->modele->sauvegarderNoteSoutenance($membre['id_utilisateur'], $note_groupe, $id, $id_groupe, 0, $id_evaluation, $id_evaluateur, $commentaire);
                        }
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