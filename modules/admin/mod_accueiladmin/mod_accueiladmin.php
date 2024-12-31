<?php

include_once 'generique/module_generique.php';
include_once 'modules/admin/mod_accueiladmin/cont_accueiladmin.php';

Class ModAccueilAdmin extends ModuleGenerique{

    public function __construct () {
        parent::__construct();
        $this->controleur=new ContAccueilAdmin();
    }
}