<?php

include_once 'generique/module_generique.php';
include_once 'modules/etudiant/mod_groupeetud/cont_groupeetud.php';

Class ModGroupeEtud extends ModuleGenerique{

    public function __construct () {
        parent::__construct();
        $this->controleur=new ContGroupeEtud();
    }
}