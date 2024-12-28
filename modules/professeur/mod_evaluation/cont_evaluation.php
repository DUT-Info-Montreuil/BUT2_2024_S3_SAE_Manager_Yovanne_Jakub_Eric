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
            case "modifierEvaluation" :
                $this->modifierEvaluation();
                break;
            case "traitementModificationNote" :
                $this->traitementModificationNote();
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
            $evaluation_id = $this->modele->checkEvaluationSoutenanceExist($id_soutenance);
            if ($evaluation_id) {
                $this->vue->formulaireModificationEvaluation($evaluation_id);
            } else {
                $this->vue->formulaireCreationEvaluation($id_soutenance, 'soutenance');
            }
        } else if (isset($_POST['id_rendu'])) {
            $id_rendu = $_POST['id_rendu'];
            $evaluation_id = $this->modele->checkEvaluationRenduExist($id_rendu);
            if ($evaluation_id) {
                $this->vue->formulaireModificationEvaluation($evaluation_id);
            } else {
                $this->vue->formulaireCreationEvaluation($id_rendu, 'rendu');
            }
        }
    }

    public function modifierEvaluation()
    {
        if (isset($_POST['id']) && isset($_POST['note_max']) && isset($_POST['coefficient'])) {
            $id = $_POST['id'];
            $note_max = $_POST['note_max'];
            $coefficient = $_POST['coefficient'];
            $this->modele->modifierEvaluation($id, $note_max, $coefficient);
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

            if ($type_evaluation === 'rendu') {
                $this->modele->creerEvaluationPourRendu($id, $coefficient, $note_max);
            } elseif ($type_evaluation === 'soutenance') {
                $this->modele->creerEvaluationPourSoutenance($id, $coefficient, $note_max);
            }
        }
        $this->gestionEvaluationsSAE();
    }

    public function creationEvaluation()
    {
        $idSae = $_SESSION['id_projet'];
        $allRendue = $this->modele->getAllRenduSAE($idSae);
        $allSoutenance = $this->modele->getAllSoutenanceSAE($idSae);
        $this->vue->afficherTableauAllRendu($allRendue, $allSoutenance);
    }

    public function gestionEvaluationsRendu()
    {
        $idSae = $_SESSION['id_projet'];
        $rendueEvaluations = $this->modele->getRenduEvaluation($idSae);
        $this->vue->afficherTableauRendu($rendueEvaluations);
    }

    public function gestionEvaluationsSoutenance()
    {
        $idSae = $_SESSION['id_projet'];
        $soutenanceEvaluations = $this->modele->getSoutenanceEvaluation($idSae);
        $this->vue->afficherTableauSoutenance($soutenanceEvaluations);
    }

    public function choixNotation()
    {
        if (isset($_POST['id_groupe']) && isset($_POST['type_evaluation'])) {
            $type_evaluation = $_POST['type_evaluation'];
            $id_groupe = $_POST['id_groupe'];
            $allMembres = $this->modele->getAllMembreSAE($id_groupe);

            if ($type_evaluation === 'rendu') {
                $id = $_POST['id_rendu'];
            } else {
                $id = $_POST['id_soutenance'];
            }

            if (!isset($_POST['id_evaluation'])) {
                $this->vue->afficherFormulaireNotation($allMembres, $id_groupe, $id, $type_evaluation);
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

            foreach ($notes as $id_etudiant => $note) {
                if ($this->isValidNote($note, $noteMax)) {
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

    private function isValidNote($note, $noteMax)
    {
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
                    } else {
                        $this->modele->sauvegarderNoteSoutenance((int)$idUtilisateur, (float)$note, $id_soutenance, $id_groupe, 0);
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