<?php

include_once 'modules/mod_connexion/cont_connexion.php';
include_once 'generique/module_generique.php';

class ModConnexion extends ModuleGenerique{
    public function __construct () {
        parent::__construct();
        $this->controleur=new ContConnexion();
    }
}




