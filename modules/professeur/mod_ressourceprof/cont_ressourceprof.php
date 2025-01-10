<?php

include_once 'modules/professeur/mod_ressourceprof/modele_ressourceprof.php';
include_once 'modules/professeur/mod_ressourceprof/vue_ressourceprof.php';
require_once "DossierManager.php";
require_once "ModeleCommun.php";
class ContRessourceProf{
    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        $this->modele = new ModeleRessourceProf();
        $this->vue = new VueRessourceProf();
    }

    public function exec(){
        $this->action = isset($_GET['action']) ? $_GET['action'] : "gestionRessourceSAE";
        if (!$this->estProfOuIntervenant()) {
            echo "Accès interdit. Vous devez être professeur ou intervenant pour accéder à cette page.";
            return;
        }
        switch ($this->action) {
            case "gestionRessourceSAE":
                $this->gestionRessourceSAE();
                break;
            case "creerRessource" :
                $this->creerRessource();
                break;
            case "submitRessource" :
                $this->submitRessource();
                break;
            case "supprimerRessource" :
                $this->supprimerRessource();
                break;
            case "modifierRessource" :
                $this->modifierRessource();
                break;
        }
    }
    public function estProfOuIntervenant(){
        $typeUser =  ModeleCommun::getTypeUtilisateur($_SESSION['id_utilisateur']);
        return $typeUser==="professeur" || $typeUser==="intervenant";
    }

    private function gestionRessourceSAE(){
        $idSae = $_SESSION['id_projet'];
        $allRessources = $this->modele->getAllRessourceSAE($idSae);
        $this->vue->afficherAllRessource($allRessources);
    }

    public function creerRessource(){
        $this->vue->formulaireCreerRessource();
    }

    public function submitRessource() {
        if (isset($_POST['titre']) && !empty($_POST['titre'])) {
            $titre = $_POST['titre'];
            $mise_en_avant = isset($_POST['mise_en_avant']) ? 1 : 0;
            $idSae = $_SESSION['id_projet'];

            $nomSae = ModeleCommun::getTitreSAE($idSae);

            if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] === UPLOAD_ERR_OK) {
                try {
                    $cheminFichier = DossierManager::uploadRessource($_FILES['fichier'], $idSae, $nomSae);
                    $this->modele->creerRessource($titre, $mise_en_avant, $idSae, $cheminFichier);

                } catch (Exception $e) {
                    echo "Erreur : " . $e->getMessage();
                }
            }
        }
        $this->gestionRessourceSAE();
    }


    public function supprimerRessource(){
        if(isset($_POST['id_ressource'])){
            $idRessource = $_POST['id_ressource'];
            $cheminFichier = $this->modele->getRessourceLien($idRessource);
            DossierManager::supprimerFichier($cheminFichier);
            $this->modele->supprimerRessource($idRessource);
        }
        $this->gestionRessourceSAE();
    }

    public function modifierRessource(){
        if(isset($_POST['id_ressource']) && isset($_POST['titre']) && !empty($_POST['titre'])){
            $idRessource = $_POST['id_ressource'];
            $titre = $_POST['titre'];
            $mise_en_avant = isset($_POST['mise_en_avant']) ? 1 : 0;
            $idSae = $_SESSION['id_projet'];

            $ancienCheminFichier = $this->modele->getRessourceLien($idRessource);

            if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] === UPLOAD_ERR_OK) {
                if ($ancienCheminFichier && file_exists($ancienCheminFichier)) {
                    DossierManager::supprimerFichier($ancienCheminFichier);
                }

                $nomSae = ModeleCommun::getTitreSAE($idSae);
                $nouveauChemin = DossierManager::uploadRessource($_FILES['fichier'], $idSae, $nomSae);
                $this->modele->mettreAJoursRessource($nouveauChemin, $mise_en_avant, $titre, $idRessource);
            } else {
                $this->modele->mettreAJoursRessourceSansFichier($titre, $mise_en_avant, $idRessource);
            }
        }
        $this->gestionRessourceSAE();
    }



}