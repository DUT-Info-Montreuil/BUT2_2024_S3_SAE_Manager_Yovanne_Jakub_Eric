<?php

include_once 'generique/module_generique.php';
include_once 'modules/admin/mod_gestuser/cont_gestuser.php';

Class ModGestUser extends ModuleGenerique{

    public function __construct () {
        parent::__construct();
        $this->controleur=new ContGestUser();
    }
}