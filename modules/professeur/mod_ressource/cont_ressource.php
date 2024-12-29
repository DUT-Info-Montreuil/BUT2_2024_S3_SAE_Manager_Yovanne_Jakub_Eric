<?php

include_once 'modules/professeur/mod_ressource/modele_ressource.php';
include_once 'modules/professeur/mod_ressource/vue_ressource.php';
require_once "DossierHelper.php";

class ContRessource{
    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        $this->modele = new ModeleRessource();
        $this->vue = new VueRessource();
    }

    public function exec(){
        $this->action = isset($_GET['action']) ? $_GET['action'] : "gestionRessourceSAE";
        if (!$this->estProf()) {
            echo "Accès interdit. Vous devez être professeur pour accéder à cette page.";
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
        }
    }
    public function estProf(){
        return $_SESSION['type_utilisateur']==="professeur";
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

            $nomSae = $this->modele->getTitreSAE($idSae);
            $uploadDossier = DossierHelper::getBaseDossierSAE($idSae, $nomSae) . DIRECTORY_SEPARATOR . 'ressources' . DIRECTORY_SEPARATOR;

            if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] === UPLOAD_ERR_OK) {
                $filename = basename($_FILES['fichier']['name']);
                $fichier = $uploadDossier . uniqid() . '-' . $filename;
                $extensionAutorises = ['pdf', 'docx', 'png', 'jpg'];

                // Convertir l'extension en minuscules
                $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); // Extraire l'extension du fichier téléchargé
                $maxFileSize = 10 * 1024 * 1024; // Taille maximale autorisée pour le fichier

                // Vérifier l'extension et la taille du fichier
                if (in_array($fileExtension, $extensionAutorises) && $_FILES['fichier']['size'] <= $maxFileSize) {
                    // Déplacer le fichier dans le dossier de destination
                    if (move_uploaded_file($_FILES['fichier']['tmp_name'], $fichier)) {
                        // Appeler la méthode pour créer la ressource dans la base de données
                        $this->modele->creerRessource($titre, $mise_en_avant, $idSae, $fichier);
                    }
                }
            }
        }
        $this->gestionRessourceSAE();
    }

    public function supprimerRessource(){
        if(isset($_POST['id_ressource'])){
            $idRessource = $_POST['id_ressource'];
            $this->modele->supprimerRessource($idRessource);
        }
        $this->gestionRessourceSAE();
    }


}