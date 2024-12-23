<?php
include_once "modules/mod_professeur/modele_professeur.php";
include_once  "modules/mod_professeur/vue_professeur.php";
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
                break;
            case "infoGeneralSae" :
                $this->infoGeneralSae();
                break;
            case "updateSae";
                $this->updateSae();
                break;
            case "gestionGroupeSAE" :
                $this->gestionGroupeSAE();
                break;
            case "ajouterGroupeFormulaire" :
                $this->ajouterGroupeFormulaire();
                break;
            case "creerGroupe" :
                $this->creerGroupe();
                break;
            case "modifierGroupe" :
                $this->modifierGroupe();
                break;
            case "ajouterNouveauMembreGrp" :
                $this->ajouterNouveauMembreGrp();
                break;
            case "supprimerMembresGroupe" :
                $this->supprimerMembresGroupe();
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
            $_SESSION['id_projet'] = $idProjet;
            $this->vue->afficherSaeDetails();
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

    public function gestionGroupeSAE() {
        $idSae = $_SESSION['id_projet'];
        if($idSae) {
            $groupe = $this->modele->getSaeGroupe($idSae);
            $this->vue->afficherGroupeSAE($groupe);
        }
    }

    public function ajouterGroupeFormulaire() {
        $idSae = $_SESSION['id_projet'];
        if($idSae) {
            $etudiants = $this->modele->getEtudiants();
            $this->vue->afficherFormulaireAjoutGroupe($etudiants);
        }
    }

    public function creerGroupe() {
        $idSae = $_SESSION['id_projet'];
        if ($idSae) {
            if (isset($_POST['nom_groupe']) && isset($_POST['etudiants'])) {
                $nomGroupe = trim($_POST['nom_groupe']);
                $etudiants = $_POST['etudiants'];
                $idGroupe = $this->modele->ajouterGroupe($nomGroupe);
                $this->modele->lieeProjetGrp($idGroupe, $idSae);
                foreach ($etudiants as $etudiantId) {
                    $this->modele->ajouterEtudiantAuGroupe($idGroupe, $etudiantId);
                }
            }
            $etudiants = $this->modele->getEtudiants();
            $this->vue->afficherFormulaireAjoutGroupe($etudiants);
        }
    }

    public function modifierGroupe() {
        if (isset($_GET['idGroupe'])) {
            $idGroupe = $_GET['idGroupe'];
            $tabDetailsGrp = $this->modele->getGroupeById($idGroupe);
            $this->vue->formulaireModifierGroupe($tabDetailsGrp);
            $tabNvEtudiant = $this->modele->ajouterNouveauMembre($idGroupe);
            $this->vue->ajouterEtudiantGrp($tabNvEtudiant, $idGroupe);
        }
    }

    public function ajouterNouveauMembreGrp() {
        if (isset($_POST['etudiants']) && isset($_GET['idGroupe'])) {
            $idGroupe = $_GET['idGroupe'];
            $etudiants = $_POST['etudiants'];
            foreach ($etudiants as $etudiantId) {
                $this->modele->ajouterEtudiantAuGroupe($idGroupe, $etudiantId);
            }
        }
        $this->gestionGroupeSAE();
    }
    public function supprimerMembresGroupe() {
        if (isset($_POST['membres_a_supprimer']) && isset($_POST['id_groupe'])) {
            $idGroupe = $_POST['id_groupe'];
            $membresASupprimer = $_POST['membres_a_supprimer'];

            foreach ($membresASupprimer as $idUtilisateur) {
                $this->modele->supprimerEtudiantDuGroupe($idGroupe, $idUtilisateur);
            }
        }
        $this->gestionGroupeSAE();
    }

}