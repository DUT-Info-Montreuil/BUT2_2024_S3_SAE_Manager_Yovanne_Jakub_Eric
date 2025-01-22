<?php

include_once 'generique/module_generique.php';
include_once 'modules/professeur/mod_evaluationprof/cont_evaluationprof.php';

Class ModEvaluationProf extends ModuleGenerique{

    public function __construct () {
        parent::__construct();
        $this->controleur=new ContEvaluationProf();
    }
}