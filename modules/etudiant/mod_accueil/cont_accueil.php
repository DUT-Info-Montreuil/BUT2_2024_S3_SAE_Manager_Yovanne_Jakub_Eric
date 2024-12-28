<?php
include_once "modules/etudiant/mod_accueil/modele_accueil.php";
include_once  "modules/etudiant/mod_accueil/vue_accueil.php";
Class ContAccueil {
    private $modele;
    private $vue;
    private $action;

    public function __construct() {
        $this->modele = new ModeleAccueil();
        $this->vue = new VueAccueil();
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
        echo "etudiant";
    }

}
