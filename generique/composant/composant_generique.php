<?php

class ComposantGenerique {
    protected $controleur;

    public function __construct() {
        $this->controleur = new ControleurCompMenu();
    }

    public function getAffichage() {
        return $this->controleur->getVue()->getAffichage();
    }
}