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
        $this->action = isset($_GET['action']) ? $_GET['action'] : "afficherDepot";
        if (!$this->estEtudiant()) {
            echo "Accès interdit. Vous devez être étudiant pour accéder à cette page.";
            return;
        }
        switch ($this->action) {
            case "afficherDepot":
                $this->afficherDepot();
                break;
        }
    }

    public function estEtudiant(){
        return $_SESSION["type_utilisateur"] === "etudiant";
    }

    public function afficherDepot(){
        $id_groupe = $_SESSION["id_groupe"];
        $id_projet = $_SESSION["id_projet"];
        $tabAllDepot = $this->modele->afficherAllDepot($id_groupe, $id_projet);
        $this->vue->afficherAllDepot($tabAllDepot);
    }
}