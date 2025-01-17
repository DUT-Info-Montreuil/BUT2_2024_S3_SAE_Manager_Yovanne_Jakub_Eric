<?php

include_once 'modules/mod_parametre/cont_parametre.php';
include_once 'generique/module_generique.php';

class ModParametre extends ModuleGenerique{
    public function __construct () {
        parent::__construct();
        $this->controleur=new ContParametre();
    }
}
