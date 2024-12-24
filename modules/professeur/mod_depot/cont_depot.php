<?php

include_once 'modules/professeur/mod_depot/modele_depot.php';
include_once 'modules/professeur/mod_depot/vue_depot.php';

class ContDepot{
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
        $this->action = isset($_GET['action']) ? $_GET['action'] : "gestionDepotSAE";

        switch ($this->action) {
            case "gestionDepotSAE":
                $this->gestionDepotSAE();
                break;
            case "creerDepot" :
                $this->creerDepot();
                break;
            case "submitDepot" :
                $this->submitDepot();
                break;
        }
    }

    public function gestionDepotSAE(){
        $idSae = $_SESSION['id_projet'];
        if($idSae){
            $allDepot = $this->modele->getAllDepotSAE($idSae);
            $this->vue->afficheAllDepotSAE($allDepot);
        }
    }

    public function creerDepot(){
        $this->vue->formulaireCreerDepot();
    }

    public function submitDepot(){
        if (isset($_POST['titre']) && trim($_POST['titre']) !== '' && isset($_POST['date_limite'])) {
            $titre = trim($_POST['titre']);
            $dateLimite = $_POST['date_limite'];
            $idSae = $_SESSION['id_projet'];
            $this->modele->creerDepot($titre, $dateLimite, $idSae);
        }
        $this->gestionDepotSAE();
    }
}