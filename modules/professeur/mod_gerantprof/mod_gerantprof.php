<?php

include_once 'generique/module_generique.php';
include_once 'modules/professeur/mod_gerantprof/cont_gerantprof.php';

Class ModGerantProf extends ModuleGenerique{

    public function __construct () {
        parent::__construct();
        $this->controleur=new ContGerantProf();
    }
}