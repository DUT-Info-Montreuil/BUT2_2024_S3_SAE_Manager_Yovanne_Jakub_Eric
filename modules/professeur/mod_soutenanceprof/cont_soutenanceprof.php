<?php

include_once 'modules/professeur/mod_soutenanceprof/modele_soutenanceprof.php';
include_once 'modules/professeur/mod_soutenanceprof/vue_soutenanceprof.php';
require_once "ModeleCommun.php";
require_once "ControllerCommun.php";
require_once "TokenManager.php";
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
        TokenManager::stockerAndGenerateToken();
        $idSae = $_GET['idProjet'];
        $allSoutenance = $this->modele->getAllSoutenance($idSae);
        $this->vue->afficherAllSoutenance($allSoutenance, $idSae);
    }

    private function modifierSoutenance()
    {
        if (isset($_POST['id_soutenance']) && isset($_POST['titre']) && isset($_POST['date_soutenance'])) {
            $idSoutenance = $_POST['id_soutenance'];
            $titre = $_POST['titre'];
            $dateSoutenance = $_POST['date_soutenance'];
            $this->modele->modifierSoutenance($idSoutenance, $titre, $dateSoutenance);
        }
        $this->gestionSoutenancesSAE();
    }

    private function supprimerSoutenance()
    {
        if (!TokenManager::verifierToken()) {
            die("Token invalide ou expiré.");
        }
        if (isset($_POST['id_soutenance'])) {
            $idSoutenance = $_POST['id_soutenance'];
            $etudiants = $this->modele->getEtudiantsParSoutenance($idSoutenance);
            $this->modele->supprimerSoutenance($idSoutenance);
            foreach ($etudiants as $etudiant) {
                $idEtudiant = $etudiant['id_utilisateur'];
                $idGroupe = $etudiant['id_groupe'];
                ModeleCommun::mettreAJourNoteFinale($idEtudiant, $idGroupe);
            }
        }
        $this->gestionSoutenancesSAE();
    }


    private function creerSoutenance()
    {
        $idSae = $_GET['idProjet'];
        $allGroupe = $this->modele->getAllGroupeByIdSae($idSae);
        $this->vue->formulaireCreerSoutenance($idSae, $allGroupe);
    }

    private function submitSoutenance()
    {
        if (!TokenManager::verifierToken()) {
            die("Token invalide ou expiré.");
        }

        $idSae = $_GET['idProjet'];

        if (!isset($_POST['titre']) || empty($_POST['titre'])) {
            die("Titre manquant.");
        }
        $titre = trim($_POST['titre']);

        if (!isset($_POST['date_soutenance']) || empty($_POST['date_soutenance'])) {
            die("Date de soutenance manquante.");
        }
        $dateSoutenanceGenerale = $_POST['date_soutenance'];

        $idSoutenance = $this->modele->ajouterSoutenance($idSae, $titre, $dateSoutenanceGenerale);

        if (isset($_POST['heure_passage'])) {
            $heuresPassage = $_POST['heure_passage'];
            foreach ($heuresPassage as $idGroupe => $heure) {
                if (!empty($heure)) {
                    $this->modele->ajouterSoutenanceGroupe($idSoutenance, $idGroupe, $heure);
                }
            }
        }
        $this->gestionSoutenancesSAE();
    }
}