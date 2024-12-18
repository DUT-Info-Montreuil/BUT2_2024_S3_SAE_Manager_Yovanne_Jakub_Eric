<?php

include_once 'generique/module_generique.php';
include "cont_acceuil_etudiant.php";

Class Modacceuil_etudiant extends ModuleGenerique{

    public function __construct () {
        parent::__construct();
        $this->controleur=new ContAcceuilEtudiant();
    }
}