<?php

include_once 'modules/mod_administrateur/cont_administrateur.php';
include_once 'generique/module_generique.php';

Class ModAdministrateur extends ModuleGenerique{

    public function __construct () {
        parent::__construct();
        $this->controleur=new ContAdministrateur();
    }
}