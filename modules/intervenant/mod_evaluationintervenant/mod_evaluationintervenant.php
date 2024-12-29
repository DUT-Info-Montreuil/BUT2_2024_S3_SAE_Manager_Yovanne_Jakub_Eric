<?php

include_once 'generique/module_generique.php';
include_once 'modules/intervenant/mod_evaluationintervenant/cont_evaluationintervenant.php';

Class ModEvaluationIntervenant extends ModuleGenerique{

    public function __construct () {
        parent::__construct();
        $this->controleur=new ContEvaluationIntervenant();
    }
}