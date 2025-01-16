<?php
include_once "modules/etudiant/mod_ressourceetud/modele_ressourceetud.php";
include_once "modules/etudiant/mod_ressourceetud/vue_ressourceetud.php";
require_once "ModeleCommun.php";
require_once "ControllerCommun.php";
Class ContRessourceEtud
{
    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        $this->modele = new ModeleRessourceEtud();
        $this->vue = new VueRessourceEtud();
    }

    public function exec()
    {
        $this->action = isset($_GET['action']) ? $_GET['action'] : "afficherAllRessources";
        if (ControllerCommun::estEtudiant()) {
            switch ($this->action) {
                case "afficherAllRessources":
                    $this->afficherAllRessources();
                    break;
            }
        }else{
            echo "Accès interdit. Vous devez être étudiant pour accéder à cette page.";
        }

    }

    public function afficherAllRessources(){
        $idProjet = $_GET["idProjet"];
        $allRessources = $this->modele->getAllRessourceAccesible($idProjet);
        $this->vue->afficherAllSae($allRessources);
    }
}