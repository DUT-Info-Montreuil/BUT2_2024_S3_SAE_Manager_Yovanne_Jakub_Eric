<?php

include_once 'generique/module_generique.php';
include_once 'modules/professeur/mod_ressource/cont_ressource.php';

Class ModRessource extends ModuleGenerique{

    public function __construct () {
        parent::__construct();
        $this->controleur=new ContRessource();
    }
}