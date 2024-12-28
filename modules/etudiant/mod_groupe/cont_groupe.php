<?php
include_once "modules/etudiant/mod_groupe/modele_groupe.php";
include_once  "modules/etudiant/mod_groupe/vue_groupe.php";
Class ContGroupe
{
    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        $this->modele = new ModeleGroupe();
        $this->vue = new VueGroupe();
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