<?php

include_once 'generique/module_generique.php';
include_once 'modules/mod_etudiant/cont_etudiant.php';

Class ModEtudiant extends ModuleGenerique{

    public function __construct () {
        parent::__construct();
        $this->controleur=new ContEtudiant();
    }
}