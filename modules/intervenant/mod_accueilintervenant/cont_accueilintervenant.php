<?php
include_once "modules/intervenant/mod_accueilintervenant/modele_accueilintervenant.php";
include_once "modules/intervenant/mod_accueilintervenant/vue_accueilintervenant.php";
Class ContAccueilIntervenant
{
    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        $this->modele = new ModeleAccueilIntervenant();
        $this->vue = new VueAccueilIntervenant();
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