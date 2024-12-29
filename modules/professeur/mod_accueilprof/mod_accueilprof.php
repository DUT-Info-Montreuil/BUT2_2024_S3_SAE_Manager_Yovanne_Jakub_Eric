<?php
include_once 'generique/module_generique.php';
include_once 'modules/professeur/mod_accueilprof/cont_accueilprof.php';

Class ModAccueilProf extends ModuleGenerique{

    public function __construct () {
        parent::__construct();
        $this->controleur=new ContAccueilProf();
    }
}