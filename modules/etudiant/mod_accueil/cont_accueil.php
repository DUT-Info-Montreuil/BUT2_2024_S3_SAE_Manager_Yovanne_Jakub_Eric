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
        $this->action = isset($_GET['action']) ? $_GET['action'] : "accueil";

        switch ($this->action) {
            case "accueil":
                $this->accueil();
                break;
        }
    }

    public function accueil() {
        $saeInscrit = $this->modele->saeInscrit($_SESSION['id_utilisateur']);
        $this->vue->afficherSaeGerer($saeInscrit);
    }

}
