<?php

include_once 'generique/module_generique.php';
include_once 'modules/intervenant/mod_evaluation/cont_evaluation.php';

Class ModEvaluation extends ModuleGenerique{

    public function __construct () {
        parent::__construct();
        $this->controleur=new ContEvaluation();
    }
}