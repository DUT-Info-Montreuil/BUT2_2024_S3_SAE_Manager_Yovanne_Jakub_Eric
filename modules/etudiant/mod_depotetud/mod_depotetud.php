<?php

include_once 'generique/module_generique.php';
include_once 'modules/etudiant/mod_depotetud/cont_depotetud.php';

Class ModDepotEtud extends ModuleGenerique{

    public function __construct () {
        parent::__construct();
        $this->controleur=new ContDepotEtud();
    }
}