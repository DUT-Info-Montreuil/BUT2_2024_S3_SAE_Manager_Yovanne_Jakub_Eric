<?php

include_once 'generique/module_generique.php';
include_once 'modules/professeur/mod_ressourceprof/cont_ressourceprof.php';

Class ModRessourceProf extends ModuleGenerique{

    public function __construct () {
        parent::__construct();
        $this->controleur=new ContRessourceProf();
    }
}