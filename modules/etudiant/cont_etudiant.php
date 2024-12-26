<?php
include_once "modules/mod_etudiant/modele_etudiant.php";
include_once  "modules/mod_etudiant/vue_etudiant.php";
Class ContEtudiant {
    private $modele;
    private $vue;
    private $action;

    public function __construct() {
        $this->modele = new ModeleEtudiant();
        $this->vue = new VueEtudiant();
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
