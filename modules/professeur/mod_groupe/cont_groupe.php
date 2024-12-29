<?php
include_once "modules/professeur/mod_groupe/modele_groupe.php";
include_once "modules/professeur/mod_groupe/vue_groupe.php";
require_once "DossierManager.php";
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
        if (!$this->estProf()) {
            echo "Accès interdit. Vous devez être professeur pour accéder à cette page.";
            return;
        }
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
            case "versModifierGroupe" :
                $this->versModifierGroupe();
                break;
            case "ajouterNouveauMembreGrp" :
                $this->ajouterNouveauMembreGrp();
                break;
            case "modifierGroupe" :
                $this->modifierGroupe();
                break;
            case "enregistrerModificationsGroupe" :
                $this->enregistrerModificationsGroupe();
                break;
            case "supprimerGrp" :
                $this->supprimerGrp();
                break;

        }
    }
    public function estProf(){
        return $_SESSION['type_utilisateur']==="professeur";
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
            $etudiants = $this->modele->getEtudiantsSansGroupe($idSae);
            $this->vue->afficherFormulaireAjoutGroupe($etudiants);
        }
    }

    public function creerGroupe() {
        $idSae = $_SESSION['id_projet'];
        if ($idSae) {
            if (isset($_POST['nom_groupe']) && isset($_POST['etudiants'])) {
                $nomGroupe = trim($_POST['nom_groupe']);
                $etudiants = $_POST['etudiants'];
                $idGroupe = $this->modele->ajouterGroupe($nomGroupe, $idSae);
                $this->modele->lieeProjetGrp($idGroupe, $idSae);
                $nomSae = $this->modele->getTitreSae($idSae);
                $nomDossier = $nomGroupe . '_' . $idGroupe;
                DossierManager::creerDossier($idSae, $nomSae, $nomDossier, 'depots');
                foreach ($etudiants as $etudiantId) {
                    $this->modele->ajouterEtudiantAuGroupe($idGroupe, $etudiantId);
                }
            }
        }
        $this->gestionGroupeSAE();
    }

    public function versModifierGroupe() {
        $idSae = $_SESSION['id_projet'];
        if (isset($_GET['idGroupe'])) {
            $idGroupe = $_GET['idGroupe'];
            $tabDetailsGrp = $this->modele->getGroupeById($idGroupe);
            $tabNvEtudiant = $this->modele->getEtudiantsSansGroupe($idSae);
            $this->vue->formulaireModifierGroupe($tabDetailsGrp, $tabNvEtudiant, $idGroupe);
        }
    }
    public function enregistrerModificationsGroupe() {
        if (isset($_POST['id_groupe']) && isset($_POST['nomGroupe']) && isset($_POST['modifiable_par_groupe'])) {
            $idGroupe = $_POST['id_groupe'];
            $nouveauNomGroupe = $_POST['nomGroupe'];
            if (isset($_POST['modifiable_par_groupe']) && $_POST['modifiable_par_groupe'] == "1"){
                $modifiableParGroupe = 1;
            }else{
                $modifiableParGroupe = 0;
            }

            $idSae = $_SESSION['id_projet'];
            $nomSae = $this->modele->getTitreSae($idSae);
            $ancienNom = $this->modele->getNomGroupe($idGroupe) . '_' . $idGroupe;
            $nvNomDossier = $nouveauNomGroupe . '_' . $idGroupe;
            DossierManager::renommerDossier($idSae, $nomSae, $ancienNom, $nvNomDossier, 'depots');

            $this->modele->modifierNomGrp($idGroupe, $nouveauNomGroupe);
            $this->modele->modifierModifiableParGroupe($modifiableParGroupe, $idGroupe);

            if (isset($_POST['membres_a_supprimer'])) {
                foreach ($_POST['membres_a_supprimer'] as $idUtilisateur) {
                    $this->modele->supprimerEtudiantDuGroupe($idGroupe, $idUtilisateur);
                }
            }

            if (isset($_POST['etudiants'])) {
                foreach ($_POST['etudiants'] as $idEtudiant) {
                    $this->modele->ajouterEtudiantAuGroupe($idGroupe, $idEtudiant);
                }
            }
        }

        $this->gestionGroupeSAE();
    }


    public function ajouterNouveauMembreGrp() {
        if (isset($_POST['etudiants']) && isset($_GET['idGroupe'])) {
            $idGroupe = $_GET['idGroupe'];
            $etudiants = $_POST['etudiants'];
            foreach ($etudiants as $etudiantId) {
                $this->modele->ajouterEtudiantAuGroupe($idGroupe, $etudiantId);
            }
        }
    }
    public function modifierGroupe() {
        if (isset($_POST['membres_a_supprimer']) && isset($_POST['id_groupe']) && isset($_GET['nomGroupe'])) {
            $idGroupe = $_POST['id_groupe'];
            $membresASupprimer = $_POST['membres_a_supprimer'];
            $nouveauNomGroupe = $_POST['nomGroupe'];
            foreach ($membresASupprimer as $idUtilisateur) {
                $this->modele->supprimerEtudiantDuGroupe($idGroupe, $idUtilisateur);
            }
            $this->modele->modifierNomGrp($idGroupe, $nouveauNomGroupe);
        }
    }

    public function supprimerGrp(){
        if(isset($_POST['idGroupe'])){
            $idGroupe = $_POST['idGroupe'];
            $idSae = $_SESSION['id_projet'];
            $nomSae = $this->modele->getTitreSae($idSae);
            $nomgrp = $this->modele->getNomGroupe($idGroupe) . '_' . $idGroupe;
            DossierManager::supprimerDossier($idSae, $nomSae, $nomgrp, 'depots');
            $this->modele->supprimerGroupe($idGroupe);
        }
        $this->gestionGroupeSAE();
    }


}
