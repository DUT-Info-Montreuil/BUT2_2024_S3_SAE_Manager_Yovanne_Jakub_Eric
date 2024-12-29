<?php

include_once 'generique/module_generique.php';
include_once 'modules/intervenant/mod_accueilintervenant/cont_accueilintervenant.php';

Class ModAccueilIntervenant extends ModuleGenerique{

    public function __construct () {
        parent::__construct();
        $this->controleur=new ContAccueilIntervenant();
    }
}