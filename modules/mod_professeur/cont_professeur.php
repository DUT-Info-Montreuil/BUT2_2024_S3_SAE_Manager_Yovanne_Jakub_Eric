<?php
include_once "modules/mod_professeur/modele_professeur.php";
include_once  "modules/mod_professeur/vue_professeur.php";

Class ContProfesseur {
    private $modele;
    private $vue;
    private $action;

    public function __construct() {
        $this->modele = new ModeleProfesseur();
        $this->vue = new VueProfesseur();
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
        echo "professeur";
    }
}