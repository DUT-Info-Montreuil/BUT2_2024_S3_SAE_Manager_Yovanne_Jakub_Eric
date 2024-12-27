<?php

include_once 'modules/professeur/mod_evaluation/modele_evaluation.php';
include_once 'modules/professeur/mod_evaluation/vue_evaluation.php';

class ContEvaluation
{
    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        $this->modele = new ModeleEvaluation();
        $this->vue = new VueEvaluation();
    }

    public function exec()
    {
        $this->action = isset($_GET['action']) ? $_GET['action'] : "gestionEvaluationsSAE";

        switch ($this->action) {
            case "gestionEvaluationsSAE":
                $this->gestionEvaluationsSAE();
                break;
            case "choixNotationRendu" :
                $this->choixNotationRendu();
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
            case "choixNotationSoutenance" :
                $this->choixNotationSoutenance();
                break;
        }
    }
    public function gestionEvaluationsSAE()
    {
        $this->creationEvaluation();
        $this->gestionEvaluationsRendu();
        $this->gestionEvaluationsSoutenance();
    }

    public function formEvaluation()
    {
        if (isset($_POST['id_soutenance'])) {
            $id_soutenance = $_POST['id_soutenance'];
            $evaluation = $this->modele->checkEvaluationSoutenanceExist($id_soutenance);
            if ($evaluation) {
                $this->vue->formulaireModificationEvaluation($id_soutenance, 'soutenance');
            } else {
                $this->vue->formulaireCreationEvaluation($id_soutenance, 'soutenance');
            }
        } else if (isset($_POST['id_rendu'])) {
            $id_rendu = $_POST['id_rendu'];
            $evaluation = $this->modele->checkEvaluationRenduExist($id_rendu);
            if ($evaluation) {
                $this->vue->formulaireModificationEvaluation($id_rendu, 'rendu');
            } else {
                $this->vue->formulaireCreationEvaluation($id_rendu, 'rendu');
            }
        }
    }


    public function creerEvaluation()
    {
        if (isset($_POST['id'], $_POST['type_evaluation'], $_POST['coefficient'], $_POST['note_max'])) {
            $id = (int)$_POST['id'];
            $type_evaluation = $_POST['type_evaluation'];
            $coefficient = (float)$_POST['coefficient'];
            $note_max = (float)$_POST['note_max'];

            if ($type_evaluation === 'rendu') {
                $this->modele->creerEvaluationPourRendu($id, $coefficient, $note_max);
            } elseif ($type_evaluation === 'soutenance') {
                $this->modele->creerEvaluationPourSoutenance($id, $coefficient, $note_max);
            }
        }
        $this->gestionEvaluationsSAE();
    }

    public function creationEvaluation(){
        $idSae = $_SESSION['id_projet'];
        $allRendue = $this->modele->getAllRenduSAE($idSae);
        $allSoutenance = $this->modele->getAllSoutenanceSAE($idSae);
        $this->vue->afficherTableauAllRendu($allRendue, $allSoutenance);
    }
    public function gestionEvaluationsRendu(){
        $idSae = $_SESSION['id_projet'];
        $rendueEvaluations = $this->modele->getRenduEvaluation($idSae);
        $this->vue->afficherTableauRendu($rendueEvaluations);
    }

    public function gestionEvaluationsSoutenance(){
        $idSae = $_SESSION['id_projet'];
        $soutenanceEvaluations = $this->modele->getSoutenanceEvaluation($idSae);
        $this->vue->afficherTableauSoutenance($soutenanceEvaluations);
    }
    public function choixNotationRendu(){
        if(isset($_POST['id_groupe']) && isset($_POST['id_rendu'])){
            $id_groupe = $_POST['id_groupe'];
            $id_rendu = $_POST['id_rendu'];
            $allMembres = $this->modele->getAllMembreSAE($id_groupe);
            $this->vue->afficherFormulaireNotation($allMembres ,$id_groupe, $id_rendu);
        }
    }

    public function choixNotationSoutenance(){

    }

    public function traitementNotationIndividuelle()
    {
        if (isset($_POST['notes']) && isset($_POST['id_rendu']) && isset($_POST['id_groupe'])) {
            $id_groupe = $_POST['id_groupe'];
            $notes = $_POST['notes'];
            $id_rendu = $_POST['id_rendu'];
            foreach ($notes as $idUtilisateur => $note) {
                if (is_numeric($note) && $note >= 0 && $note <= $this->modele->infNoteMax($id_rendu, $note)) {
                    $this->modele->sauvegarderNote((int)$idUtilisateur, (float)$note, $id_rendu, $id_groupe);
                }
            }
        }
        $this->gestionEvaluationsSAE();
    }

    public function traitementNotationGroupe(){
        if (isset($_POST['note_groupe']) && isset($_POST['id_rendu']) && isset($_POST['id_groupe'])) {
            $id_groupe = $_POST['id_groupe'];
            $id_rendu = $_POST['id_rendu'];
            $note_groupe = $_POST['note_groupe'];
            $allMembres = $this->modele->getAllMembreGroupe($id_groupe);
            foreach ($allMembres as $membre) {
                if (is_numeric($note_groupe) && $note_groupe >= 0 && $note_groupe <= $this->modele->infNoteMax($id_rendu, $note_groupe)) {
                    $this->modele->sauvegarderNote($membre['id_utilisateur'], $note_groupe, $id_rendu, $id_groupe);
                }
            }
            $this->gestionEvaluationsSAE();

        }
    }


}