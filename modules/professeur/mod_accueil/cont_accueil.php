<?php
include_once "modules/professeur/mod_accueil/modele_accueil.php";
include_once "modules/professeur/mod_accueil/vue_accueil.php";
Class ContAccueil {
    private $modele;
    private $vue;
    private $action;
    public function __construct() {
        $this->modele = new ModeleAccueil();
        $this->vue = new VueAccueil();
    }
    public function exec() {
        $this->action = isset($_GET['action']) ? $_GET['action'] : "accueil";

        switch ($this->action) {
            case "accueil":
                $this->accueil();
                break;
            case "creerSAEForm":
                $this->creerSAEForm();
                break;
            case "choixSae" :
                $this->choixSae();
                break;
            case "infoGeneralSae" :
                $this->infoGeneralSae();
                break;
            case "updateSae";
                $this->updateSae();
                break;
            case "creerSAE":
                $this->creerSAE();
                break;
            case "supprimerSAE" :
                $this->supprimerSAE();
                break;
        }
    }

    public function accueil() {
        $saeGerer = $this->modele->saeGerer($_SESSION['id_utilisateur']);
        $this->vue->afficherSaeGerer($saeGerer);
    }

    public function creerSAEForm() {
        $this->vue->creerUneSAEForm();
    }

    public function creerSAE(){
        if (
            isset($_POST['titre']) && !empty(trim($_POST['titre'])) &&
            isset($_POST['annee']) && !empty(trim($_POST['annee'])) &&
            isset($_POST['semestre']) && !empty(trim($_POST['semestre'])) &&
            isset($_POST['description']) && !empty(trim($_POST['description']))
        ) {
            $titre = trim($_POST['titre']);
            $annee = trim($_POST['annee']);
            $semestre = trim($_POST['semestre']);
            $description = trim($_POST['description']);
            $this->modele->ajouterProjet($titre, $annee, $description, $semestre);
        }
        $this->accueil();
    }
    public function choixSae() {
        if (isset($_GET['id'])) {
            $idProjet = $_GET['id'];
            $_SESSION['id_projet'] = $idProjet;
            $titre = $this->modele->getTitreSAE($idProjet);
            $this->vue->afficherSaeDetails($titre);
        } else {
            $this->accueil();
        }
    }


    public function infoGeneralSae() {
        $idProjet = $_SESSION['id_projet'];
        if ($idProjet) {
            $saeTabDetails = $this->modele->getSaeDetails($idProjet);
            $this->vue->afficherSaeInfoGeneral($saeTabDetails);
        } else {
            $this->accueil();
        }
    }

    public function updateSae() {
        $idSae = $_SESSION['id_projet'];
        if($idSae) {
            if(isset($_POST['titre']) && isset($_POST['annee_universitaire']) && isset($_POST['semestre']) && isset($_POST['description_projet'])) {
                $titre = trim($_POST['titre']);
                $annee = trim($_POST['annee_universitaire']);
                $semestre = trim($_POST['semestre']);
                $description = trim($_POST['description_projet']);
                $this->modele->modifierInfoGeneralSae($idSae, $titre, $annee, $semestre, $description);
            }
        }
        $this->accueil();
    }

    public function supprimerSAE(){
        $idSae = $_SESSION['id_projet'];
        $this->modele->supprimerSAE($idSae);
        $this->accueil();
    }
}