<?php
include_once "modules/etudiant/mod_ressourceetud/modele_ressourceetud.php";
include_once "modules/etudiant/mod_ressourceetud/vue_ressourceetud.php";
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
        $this->action = isset($_GET['action']) ? $_GET['action'] : "afficherAllRessources";
        if (!$this->estEtudiant()) {
            echo "Accès interdit. Vous devez être étudiant pour accéder à cette page.";
            return;
        }
        switch ($this->action) {
            case "afficherAllRessources":
                $this->afficherAllRessources();
                break;
        }
    }

    public function estEtudiant(){
        return $_SESSION["type_utilisateur"] === "etudiant";
    }

    public function afficherAllRessources(){
        $idProjet = $_SESSION["id_projet"];
        $allRessources = $this->modele->getAllRessourceAccesible($idProjet);
        $this->vue->afficherAllSae($allRessources);
    }
}