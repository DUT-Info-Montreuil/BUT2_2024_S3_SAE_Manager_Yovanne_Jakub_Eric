<?php

include_once 'generique/module_generique.php';
include_once 'modules/intervenant/mod_depotintervenant/cont_depotintervenant.php';

Class ModDepotIntervenant extends ModuleGenerique{

    public function __construct () {
        parent::__construct();
        $this->controleur=new ContDepotIntervenant();
    }
}