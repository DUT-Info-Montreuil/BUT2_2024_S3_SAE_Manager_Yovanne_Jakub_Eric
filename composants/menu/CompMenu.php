<?php
require_once "ContMenu.php";
require_once 'generique/composant_generique.php';

class ComposantMenu extends ComposantGenerique {
    public function __construct () {
        parent::__construct();
        $this->controleur = new ControleurCompMenu();
    }


}
