<?php
include_once "modules/intervenant/mod_depotintervenant/modele_depotintervenant.php";
include_once "modules/intervenant/mod_depotintervenant/vue_depotintervenant.php";
Class ContDepotIntervenant
{
    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        $this->modele = new ModeleDepotIntervenant();
        $this->vue = new VueDepotIntervenant();
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