<?php

include_once 'generique/module_generique.php';
include_once 'modules/etudiant/mod_notesetud/cont_notesetud.php';

Class ModNotesEtud extends ModuleGenerique{

    public function __construct () {
        parent::__construct();
        $this->controleur=new ContNotesEtud();
    }
}