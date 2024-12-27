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
    public function choixNotation(){
        if(isset($_POST['id_groupe']) && isset($_POST['type_evaluation'])){
            $type_evaluation = $_POST['type_evaluation'];
            $id_groupe = $_POST['id_groupe'];
            $allMembres = $this->modele->getAllMembreSAE($id_groupe);
            if($type_evaluation === 'rendu'){
                $id = $_POST['id_rendu'];
            }else{
                $id = $_POST['id_soutenance'];
            }
            $this->vue->afficherFormulaireNotation($allMembres ,$id_groupe, $id, $type_evaluation);
        }
    }

    public function traitementNotationIndividuelle()
    {
        if (isset($_POST['notes']) && isset($_POST['id_eval']) && isset($_POST['id_groupe']) && isset($_POST['type_evaluation'])) {
            $id_groupe = $_POST['id_groupe'];
            $type_evaluation = $_POST['type_evaluation'];
            $notes = $_POST['notes'];

            if ($type_evaluation === 'rendu') {
                $id_rendu = $_POST['id_eval'];
                $noteMax = $this->modele->infNoteMaxRendu($id_rendu);
            } else {
                $id_soutenance = $_POST['id_eval'];
                $noteMax = $this->modele->infNoteMaxSoutenance($id_soutenance);
            }

            foreach ($notes as $idUtilisateur => $note) {
                if (is_numeric($note) && $note >= 0 && $note <= $noteMax) {
                    if ($type_evaluation === 'rendu') {
                        $this->modele->sauvegarderNoteRendu((int)$idUtilisateur, (float)$note, $id_rendu, $id_groupe, 0);
                    }
                    else {
                        $this->modele->sauvegarderNoteSoutenance((int)$idUtilisateur, (float)$note, $id_soutenance, $id_groupe,0);
                    }
                }
            }
        }
        $this->gestionEvaluationsSAE();
    }


    public function traitementNotationGroupe()
    {
        if (isset($_POST['note_groupe']) && isset($_POST['id_eval']) && isset($_POST['id_groupe']) && isset($_POST['type_evaluation'])) {
            $id_groupe = $_POST['id_groupe'];
            $note_groupe = $_POST['note_groupe'];
            $type_evaluation = $_POST['type_evaluation'];

            if ($type_evaluation === 'rendu') {
                $id_rendu = $_POST['id_eval'];
                $noteMax = $this->modele->infNoteMaxRendu($id_rendu);
            } else {
                $id_soutenance = $_POST['id_eval'];
                $noteMax = $this->modele->infNoteMaxSoutenance($id_soutenance);
            }

            if (is_numeric($note_groupe) && $note_groupe >= 0 && $note_groupe <= $noteMax) {
                $allMembres = $this->modele->getAllMembreSAE($id_groupe);

                foreach ($allMembres as $membre) {
                    if ($type_evaluation === 'rendu') {
                        $this->modele->sauvegarderNoteRendu($membre['id_utilisateur'], $note_groupe, $id_rendu, $id_groupe, 1);
                    } else {
                        $this->modele->sauvegarderNoteSoutenance($membre['id_utilisateur'], $note_groupe, $id_soutenance, $id_groupe, 1);
                    }
                }
            }
        }
        $this->gestionEvaluationsSAE();
    }



}