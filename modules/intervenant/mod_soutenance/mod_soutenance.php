<?php

include_once 'generique/module_generique.php';
include_once 'modules/intervenant/mod_soutenance/cont_soutenance.php';

Class ModSoutenance extends ModuleGenerique{

    public function __construct () {
        parent::__construct();
        $this->controleur=new ContSoutenance();
    }
}