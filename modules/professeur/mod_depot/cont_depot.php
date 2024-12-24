<?php

include_once 'modules/professeur/mod_depot/modele_depot.php';
include_once 'modules/professeur/mod_depot/vue_depot.php';

class ContDepot{
    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        $this->modele = new ModeleDepot();
        $this->vue = new VueDepot();
    }

    public function exec()
    {
        $this->action = isset($_GET['action']) ? $_GET['action'] : "gestionDepotSAE";

        switch ($this->action) {
            case "gestionDepotSAE":
                $this->gestionDepotSAE();
                break;
        }
    }

    public function gestionDepotSAE(){
        echo "gestionDepotSAE";
    }
}