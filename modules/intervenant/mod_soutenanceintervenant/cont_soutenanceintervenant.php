<?php
include_once "modules/intervenant/mod_soutenanceintervenant/modele_soutenanceintervenant.php";
include_once "modules/intervenant/mod_soutenanceintervenant/vue_soutenanceintervenant.php";
Class ContSoutenanceIntervenant
{
    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        $this->modele = new ModeleSoutenanceIntervenant();
        $this->vue = new VueSoutenanceIntervenant();
    }

    public function exec()
    {
        $this->action = isset($_GET['action']) ? $_GET['action'] : "accueil";
        if (!$this->estIntervenant()) {
            echo "Accès interdit. Vous devez être intervenant pour accéder à cette page.";
            return;
        }
        switch ($this->action) {
            case "accueil":
                $this->accueil();
                break;
        }
    }

    public function estIntervenant(){
        return $_SESSION['type_utilisateur'] === "intervenant";
    }

    public function accueil(){
        echo "accueil";
    }

}