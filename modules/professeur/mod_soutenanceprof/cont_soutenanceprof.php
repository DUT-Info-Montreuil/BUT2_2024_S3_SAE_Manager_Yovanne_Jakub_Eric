<?php

include_once 'modules/professeur/mod_soutenanceprof/modele_soutenanceprof.php';
include_once 'modules/professeur/mod_soutenanceprof/vue_soutenanceprof.php';
require_once "ModeleCommun.php";
require_once "ControllerCommun.php";
class ContSoutenanceProf
{
    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        $this->modele = new ModeleSoutenanceProf();
        $this->vue = new VueSoutenanceProf();
    }

    public function exec()
    {
        $this->action = isset($_GET['action']) ? $_GET['action'] : "gestionSoutenancesSAE";
        if (ControllerCommun::estProfOuIntervenant()) {
            switch ($this->action) {
                case "gestionSoutenancesSAE":
                    $this->gestionSoutenancesSAE();
                    break;
                case "modifierSoutenance" :
                    $this->modifierSoutenance();
                    break;
                case "supprimerSoutenance" :
                    $this->supprimerSoutenance();
                    break;
                case "creerSoutenance" :
                    $this->creerSoutenance();
                    break;
                case "submitSoutenance" :
                    $this->submitSoutenance();
                    break;
            }
        }else{
            echo "Accès interdit. Vous devez être professeur ou intervenant pour accéder à cette page.";
        }

    }
    private function gestionSoutenancesSAE()
    {
        $idSae = $_SESSION['id_projet'];
        $allSoutenance = $this->modele->getAllSoutenance($idSae);
        $this->vue->afficherAllSoutenance($allSoutenance);
    }

    private function modifierSoutenance()
    {
        if (isset($_POST['id_soutenance']) && isset($_POST['titre']) && isset($_POST['date_soutenance'])) {
            $idSae = $_SESSION['id_projet'];
            $idSoutenance = $_POST['id_soutenance'];
            $titre = $_POST['titre'];
            $dateSoutenance = $_POST['date_soutenance'];
            $this->modele->modifierSoutenance($idSoutenance, $titre, $dateSoutenance);
        }
        $this->gestionSoutenancesSAE();
    }

    private function supprimerSoutenance()
    {
        if (isset($_POST['id_soutenance'])) {
            $idSoutenance = $_POST['id_soutenance'];
            $this->modele->supprimerSoutenance($idSoutenance);
        }
        $this->gestionSoutenancesSAE();

    }

    private function creerSoutenance()
    {
        $this->vue->formulaireCreerSoutenance();
    }

    private function submitSoutenance()
    {
        $idSae = $_SESSION['id_projet'];
        if (isset($_POST['titre']) && !empty($_POST['titre']) && isset($_POST['date_soutenance'])) {
            $titre = $_POST['titre'];
            $date_soutenance = $_POST['date_soutenance'];
            $this->modele->ajouterSoutenance($idSae, $titre, $date_soutenance);
        }
        $this->gestionSoutenancesSAE();
    }

}