<?php

include_once 'generique/module_generique.php';
include_once 'modules/etudiant/mod_depot/cont_depot.php';

Class ModDepot extends ModuleGenerique{

    public function __construct () {
        parent::__construct();
        $this->controleur=new ContDepot();
    }
}