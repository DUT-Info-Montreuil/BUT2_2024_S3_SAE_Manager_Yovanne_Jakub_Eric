<?php
include_once "modules/etudiant/mod_ressource/modele_ressource.php";
include_once  "modules/etudiant/mod_ressource/vue_ressource.php";
Class ContRessource
{
    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        $this->modele = new ModeleRessource();
        $this->vue = new VueRessource();
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