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
            case "modifierDepot" :
                $this->modifierDepot();
            case "supprimerDepot" :
                $this->supprimerDepot();
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

    public function modifierDepot(){
        if(isset($_POST['id_rendu']) && isset($_POST['date_limite']) && trim($_POST['titre']) !== ''){
            $titre = trim($_POST['titre']);
            $dateLimite = $_POST['date_limite'];
            $id_rendu = $_POST['id_rendu'];
            $this->modele->modifierRendu($id_rendu, $titre, $dateLimite);
        }
        $this->gestionDepotSAE();
    }

    public function supprimerDepot(){
        if(isset($_POST['id_rendu'])){
            $id_rendu = $_POST['id_rendu'];
            $this->modele->supprimerDepot($id_rendu);
            header("Location: index.php?module=depotprof&action=gestionDepotSAE"); // si  je met $this->gestionDepotSAE(); = 2x affichage
        }
    }
}