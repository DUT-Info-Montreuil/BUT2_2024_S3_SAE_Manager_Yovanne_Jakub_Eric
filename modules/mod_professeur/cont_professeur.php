<?php
include_once "modules/mod_professeur/modele_professeur.php";
include_once  "modules/mod_professeur/vue_professeur.php";
include "modules/mod_professeur/Sae.php";

Class ContProfesseur {
    private $modele;
    private $vue;
    private $action;

    public function __construct() {
        $this->modele = new ModeleProfesseur();
        $this->vue = new VueProfesseur();
    }

    public function exec() {
        $this->action = isset($_GET['action']) ? $_GET['action'] : "accueil";

        switch ($this->action) {
            case "accueil":
                $this->accueil();
                break;
            case "creerSAE":
                $this->creerSAE();
                break;
            case "choixSae" :
                $this->choixSae();
            case "infoGeneralSae" :
                $this->infoGeneralSae();
                break;
            case "updateSae";
                $this->updateSae();
                break;
            case "gestionGroupeSAE" :
                $this->gestionGroupeSAE();
            case "ajouterGroupe" :
                $this->ajouterGroupe();
        }
    }

    public function accueil() {
        $saeGerer = $this->modele->saeGerer($_SESSION['id_utilisateur']);
        $this->vue->afficherSaeGerer($saeGerer);
    }

    public function creerSAE() {
        $this->vue->creerUneSAE();
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
    }
    public function choixSae() {
        if (isset($_GET['id'])) {
            $idProjet = $_GET['id'];
            $this->vue->afficherSaeDetails($idProjet);
        } else {
            $this->accueil();
        }
    }

    public function infoGeneralSae() {
        if(isset($_GET['saeId'])) {
            $idProjet = $_GET['saeId'];
            $saeTabDetails = $this->modele->getSaeDetails($idProjet);
            $this->vue->afficherSaeInfoGeneral($saeTabDetails);

        }
    }

    public function updateSae() {
        if(isset($_GET['saeId'])) {
            $idSae = $_GET['saeId'];
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

    public function gestionGroupeSAE() {
        if(isset($_GET['saeId'])) {
            $idSae = $_GET['saeId'];
            $groupe = $this->modele->getSaeGroupe($idSae);
            $this->vue->afficherGroupeSAE($groupe, $idSae);
        }
    }

    public function ajouterGroupe() {
        if(isset($_GET['saeId'])) {
            $idSae = $_GET['saeId'];
            $this->vue->afficherFormulaireAjoutGroupe();
        }
    }
}