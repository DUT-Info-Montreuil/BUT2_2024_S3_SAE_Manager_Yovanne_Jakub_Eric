<?php

include_once 'generique/module_generique.php';
include_once 'modules/etudiant/mod_ressourceetud/cont_ressourceetud.php';

Class ModRessourceEtud extends ModuleGenerique{

    public function __construct () {
        parent::__construct();
        $this->controleur=new ContRessourceEtud();
    }
}