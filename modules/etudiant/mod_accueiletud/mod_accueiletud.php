<?php

include_once 'generique/module_generique.php';
include_once 'modules/etudiant/mod_accueiletud/cont_accueiletud.php';

Class ModAccueilEtud extends ModuleGenerique{

    public function __construct () {
        parent::__construct();
        $this->controleur=new ContAccueilEtud();
    }
}