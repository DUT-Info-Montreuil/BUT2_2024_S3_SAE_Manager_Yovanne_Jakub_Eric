<?php

include_once 'generique/module_generique.php';
include_once 'modules/professeur/mod_depotprof/cont_depotprof.php';

Class ModDepotProf extends ModuleGenerique{

    public function __construct () {
        parent::__construct();
        $this->controleur=new ContDepotProf();
    }
}