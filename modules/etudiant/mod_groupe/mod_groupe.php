<?php

include_once 'generique/module_generique.php';
include_once 'modules/etudiant/mod_groupe/cont_groupe.php';

Class ModGroupe extends ModuleGenerique{

    public function __construct () {
        parent::__construct();
        $this->controleur=new ContGroupe();
    }
}