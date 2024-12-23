<?php

include_once 'generique/module_generique.php';
include_once 'modules/professeur/mod_gerant/cont_gerant.php';

Class ModGerant extends ModuleGenerique{

    public function __construct () {
        parent::__construct();
        $this->controleur=new ContGerant();
    }
}