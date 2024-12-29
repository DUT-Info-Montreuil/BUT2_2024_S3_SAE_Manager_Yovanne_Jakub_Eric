<?php

include_once 'generique/module_generique.php';
include_once 'modules/intervenant/mod_accueil/cont_accueil.php';

Class ModAccueil extends ModuleGenerique{

    public function __construct () {
        parent::__construct();
        $this->controleur=new ContAccueil();
    }
}