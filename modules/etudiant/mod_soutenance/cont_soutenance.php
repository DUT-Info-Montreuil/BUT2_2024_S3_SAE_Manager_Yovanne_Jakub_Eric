<?php
include_once "modules/etudiant/mod_soutenance/modele_soutenance.php";
include_once  "modules/etudiant/mod_soutenance/vue_soutenance.php";
Class ContSoutenance
{
    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        $this->modele = new ModeleSoutenance();
        $this->vue = new VueSoutenance();
    }

    public function exec()
    {
        $this->action = isset($_GET['action']) ? $_GET['action'] : "affichageDesSoutenances";
        if (!$this->estEtudiant()) {
            echo "Accès interdit. Vous devez être étudiant pour accéder à cette page.";
            return;
        }
        switch ($this->action) {
            case "affichageDesSoutenances":
                $this->affichageDesSoutenances();
                break;
        }
    }

    public function estEtudiant(){
        return $_SESSION["type_utilisateur"] === "etudiant";
    }

    public function affichageDesSoutenances(){
        $idProjet = $_SESSION["id_projet"];
        $allSoutenance = $this->modele->getAllSoutenances($idProjet);
        $this->vue->afficherAllSoutenances($allSoutenance);
    }
}