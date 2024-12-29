<?php

include_once 'generique/module_generique.php';
include_once 'modules/intervenant/mod_soutenanceintervenant/cont_soutenanceintervenant.php';

Class ModSoutenanceIntervenant extends ModuleGenerique{

    public function __construct () {
        parent::__construct();
        $this->controleur=new ContSoutenanceIntervenant();
    }
}