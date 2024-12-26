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
            case "traiterNote" :
                $this->traiterNote();
                break;
        }
    }
    public function gestionEvaluationsSAE()
    {
        $this->gestionEvaluationsRendu();
    }

    public function gestionEvaluationsRendu(){
        $idSae = $_SESSION['id_projet'];
        $rendueEvaluations = $this->modele->getRenduEvaluation($idSae);
        $this->vue->afficherTableauRendu($rendueEvaluations);
    }

    public function traiterNote(){

    }

}