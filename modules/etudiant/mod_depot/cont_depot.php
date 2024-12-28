<?php
include_once "modules/etudiant/mod_depot/modele_depot.php";
include_once  "modules/etudiant/mod_depot/vue_depot.php";
Class ContDepot
{
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
        $this->action = isset($_GET['action']) ? $_GET['action'] : "accueil";
        if (!$this->estEtudiant()) {
            echo "Accès interdit. Vous devez être étudiant pour accéder à cette page.";
            return;
        }
        switch ($this->action) {
            case "accueil":
                $this->accueil();
                break;
        }
    }

    public function estEtudiant(){
        return $_SESSION["type_utilisateur"] === "etudiant";
    }

    public function accueil(){
        echo "accueil";
    }
}