<?php
include_once "modules/intervenant/mod_ressourceintervenant/modele_ressourceintervenant.php";
include_once "modules/intervenant/mod_ressourceintervenant/vue_ressourceintervenant.php";
Class ContRessourceIntervenant
{
    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        $this->modele = new ModeleRessourceIntervenant();
        $this->vue = new VueRessourceIntervenant();
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