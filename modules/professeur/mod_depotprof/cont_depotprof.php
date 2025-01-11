<?php

include_once 'modules/professeur/mod_depotprof/modele_depotprof.php';
include_once 'modules/professeur/mod_depotprof/vue_depotprof.php';
require_once "DossierManager.php";
require_once "ModeleCommun.php";
require_once "ControllerCommun.php";
class ContDepotProf{
    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        $this->modele = new ModeleDepotProf();
        $this->vue = new VueDepotProf();
    }

    public function exec()
    {
        $this->action = isset($_GET['action']) ? $_GET['action'] : "gestionDepotSAE";
        if (ControllerCommun::estProfOuIntervenant()) {
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
                    break;
                case "supprimerDepot" :
                    $this->supprimerDepot();
                    break;
                case "ajouterTemps" :
                    $this->ajouterTempsSupplementaire();
                    break;
            }
        }else{
            echo "Accès interdit. Vous devez être professeur ou intervenant pour accéder à cette page.";
        }

    }
    public function gestionDepotSAE(){
        $idSae = $_SESSION['id_projet'];
        if($idSae){
            $allDepot = $this->modele->getAllDepotSAE($idSae);
            $allGroupe = $this->modele->getGroupesParSae($idSae);
            $this->vue->afficheAllDepotSAE($allDepot, $allGroupe);
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

            $idRendu = $this->modele->creerDepot($titre, $dateLimite, $idSae);
            $nomRendu = $this->modele->getNomDepot($idRendu);
            $groupes = $this->modele->getGroupesParSae($idSae);
            $nomSae = ModeleCommun::getTitreSAE($idSae);

            foreach ($groupes as $groupe) {
                $nomGroupe = $groupe['nom'];
                $idGroupe = $groupe['id_groupe'];
                DossierManager::creerDepotPourGroupe($idSae, $nomSae, $idGroupe, $nomGroupe, $idRendu, $nomRendu);
            }
        }
        $this->gestionDepotSAE();
    }

    public function modifierDepot(){
        if(isset($_POST['id_rendu']) && isset($_POST['date_limite']) && trim($_POST['titre']) !== ''){
            $nouveauNomDepot = trim($_POST['titre']);
            $dateLimite = $_POST['date_limite'];
            $id_rendu = $_POST['id_rendu'];
            $idSae = $_SESSION['id_projet'];

            $ancienNomDepot = $this->modele->getNomDepot($id_rendu);
            $this->modele->modifierRendu($id_rendu, $nouveauNomDepot, $dateLimite);
            $groupes = $this->modele->getGroupesParSae($idSae);
            $nomSae = ModeleCommun::getTitreSAE($idSae);

            foreach ($groupes as $groupe) {
                $idGroupe = $groupe['id_groupe'];
                $nomGroupe = $groupe['nom'];
                DossierManager::renommerDepotPourGroupe($idSae, $nomSae, $idGroupe, $nomGroupe, $id_rendu, $ancienNomDepot, $nouveauNomDepot);
            }

        }
        $this->gestionDepotSAE();
    }
    public function supprimerDepot(){
        if (isset($_POST['id_rendu'])) {
            $id_rendu = $_POST['id_rendu'];
            $nomDepot = $this->modele->getNomDepot($id_rendu);

            $idSae = $_SESSION['id_projet'];
            $groupes = $this->modele->getGroupesParSae($idSae);

            $nomSae = ModeleCommun::getTitreSAE($idSae);

            foreach ($groupes as $groupe) {
                $idGroupe = $groupe['id_groupe'];
                $nomGroupe = $groupe['nom'];

                DossierManager::supprimerDepotPourGroupe($idSae, $nomSae, $idGroupe, $nomGroupe, $id_rendu, $nomDepot);
            }

            $this->modele->supprimerDepot($id_rendu);
        }
        $this->gestionDepotSAE();
    }

    public function ajouterTempsSupplementaire()
    {
        if (isset($_POST['id_rendu'], $_POST['new_date_limite'], $_POST['groupes'])) {
            $idRendu = $_POST['id_rendu'];
            $newDateLimite = $_POST['new_date_limite'];
            $groupes = $_POST['groupes'];

            foreach ($groupes as $idGroupe) {
                $this->modele->ajouterTempsSupplementairePourGroupe($idRendu, $idGroupe, $newDateLimite);
            }
        }
        $this->gestionDepotSAE();
    }



}