<?php

include_once 'generique/module_generique.php';
include_once 'modules/etudiant/mod_soutenanceetud/cont_soutenanceetud.php';

Class ModSoutenanceEtud extends ModuleGenerique{

    public function __construct () {
        parent::__construct();
        $this->controleur=new ContSoutenanceEtud();
    }
}