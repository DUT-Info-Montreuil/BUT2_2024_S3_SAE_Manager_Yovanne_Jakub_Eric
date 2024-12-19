<?php
include_once 'generique/module_generique.php';
include_once 'modules/mod_professeur/cont_professeur.php';

Class ModProfesseur extends ModuleGenerique{

    public function __construct () {
        parent::__construct();
        $this->controleur=new ContProfesseur();
    }
}