<?php
include_once "modules/professeur/mod_groupe/modele_groupe.php";
include_once "modules/professeur/mod_groupe/vue_groupe.php";
Class ContGroupe {
    private $modele;
    private $vue;
    private $action;

    public function __construct() {
        $this->modele = new ModeleGroupe();
        $this->vue = new VueGroupe();
    }

    public function exec() {
        $this->action = isset($_GET['action']) ? $_GET['action'] : "gestionGroupeSAE";

        switch ($this->action) {
            case "gestionGroupeSAE":
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
                break;
        }
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
