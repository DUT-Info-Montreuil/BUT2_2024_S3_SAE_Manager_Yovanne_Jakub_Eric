<?php

class ComposantGenerique {
    protected $controleur;
    public function __construct () {
    }
    public function getAffichage() {
        return $this->controleur->vue->getAffichage();
    }
}
