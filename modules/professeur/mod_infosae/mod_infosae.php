<?php
include_once 'generique/module_generique.php';
include_once 'modules/professeur/mod_infosae/cont_infosae.php';

Class ModInfoSae extends ModuleGenerique{

    public function __construct () {
        parent::__construct();
        $this->controleur=new ContInfoSae();
    }
}