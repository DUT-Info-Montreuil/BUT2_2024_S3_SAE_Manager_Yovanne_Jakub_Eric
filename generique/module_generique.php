<?php

class ModuleGenerique {
    private $affichage;
    protected $controleur;

    public function __construct () {
        $this->affichage = "";
    }

    public function exec () {
        $this->controleur->exec();
        $this->affichage = ob_get_clean();
    }


    public function getAffichage() {
        return $this->affichage;
    }


}
