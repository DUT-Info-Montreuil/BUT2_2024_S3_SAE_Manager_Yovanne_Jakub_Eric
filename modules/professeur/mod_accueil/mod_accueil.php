<?php
include_once 'generique/module_generique.php';
include_once 'modules/professeur/mod_accueil/cont_accueil.php';

Class ModAccueil extends ModuleGenerique{

    public function __construct () {
        parent::__construct();
        $this->controleur=new ContAccueil();
    }
}