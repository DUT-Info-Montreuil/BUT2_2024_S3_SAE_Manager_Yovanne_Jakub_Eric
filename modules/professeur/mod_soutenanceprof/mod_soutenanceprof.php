<?php

include_once 'generique/module_generique.php';
include_once 'modules/professeur/mod_soutenanceprof/cont_soutenanceprof.php';

Class ModSoutenanceProf extends ModuleGenerique{

    public function __construct () {
        parent::__construct();
        $this->controleur=new ContSoutenanceProf();
    }
}