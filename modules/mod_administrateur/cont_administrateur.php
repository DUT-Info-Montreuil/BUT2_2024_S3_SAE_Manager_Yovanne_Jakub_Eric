<?php
include_once "modules/mod_administrateur/modele_administrateur.php";
include_once  "modules/mod_administrateur/vue_administrateur.php";
Class ContAdministrateur {
    private $modele;
    private $vue;
    private $action;

    public function __construct() {
        $this->modele = new ModeleAdministrateur();
        $this->vue = new VueAdministrateur();
    }

    public function exec() {
        $this->action = isset($_GET['action']) ? $_GET['action'] : "sae";

        switch ($this->action) {
            case "sae":
                $this->sae();
                break;
            default:
                $this->sae();
                break;
        }
    }

    public function sae() {
        echo "administrateur";
    }

}
