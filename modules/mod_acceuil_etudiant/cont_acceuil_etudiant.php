<?php
include_once "modules/mod_acceuil_etudiant/modele_acceuil_etudiant.php";
include_once  "modules/mod_acceuil_etudiant/vue_acceuil_etudiant.php";
Class ContAcceuilEtudiant {
    private $modele;
    private $vue;
    private $action;

    public function __construct() {
        $this->modele = new ModeleAcceuilEtudiant();
        $this->vue = new VueAcceuilEtudiant();
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
        echo "sae";
    }

}
