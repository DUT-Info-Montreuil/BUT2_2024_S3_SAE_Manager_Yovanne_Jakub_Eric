<?php

include_once 'generique/module_generique.php';
include_once 'modules/professeur/mod_dashboard/cont_dashboard.php';

Class ModDashboard extends ModuleGenerique{

    public function __construct () {
        parent::__construct();
        $this->controleur=new ContDashboard();
    }
}