<?php

include_once 'modules/professeur/mod_ressource/modele_ressource.php';
include_once 'modules/professeur/mod_ressource/vue_ressource.php';

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

    private function gestionRessourceSAE(){
        $idSae = $_SESSION['id_projet'];
        $allRessources = $this->modele->getAllRessourceSAE($idSae);
        $this->vue->afficherAllRessource($allRessources);
    }

    public function creerRessource(){
        $this->vue->formulaireCreerRessource();
    }

    public function submitRessource(){
        if(isset($_POST['titre']) && !empty($_POST['titre'])){
            $titre = $_POST['titre'];
            $mise_en_avant = isset($_POST['mise_en_avant']) ? 1 : 0;
            $idSae = $_SESSION['id_projet'];

            // Verif si aucun problème n'est survenu lors de l'upload
            if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/ressources/'; // Répertoire de destination pour les fichiers uploadés
                $filename = basename($_FILES['fichier']['name']); // Extraire le nom du fichier téléchargé
                $targetFile = $uploadDir . uniqid() . '-' . $filename; // Créer un nom de fichier unique
                $allowedExtensions = ['pdf', 'docx', 'png', 'jpg']; // Liste d'extensions autorisées

                // strtolower = met en minuscule
                $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); // Extraire l'extension du fichier téléchargé
                $maxFileSize = 10 * 1024 * 1024; // Taille maximale autorisée pour le fichier

                // Extension dans la liste et taille autorisé
                if (in_array($fileExtension, $allowedExtensions) && $_FILES['fichier']['size'] <= $maxFileSize) {

                    // Déplace le fichier dans le répertoire 'uploads/ressources/' et retourn true si il est déplacé
                    if (move_uploaded_file($_FILES['fichier']['tmp_name'], $targetFile)) {
                        $this->modele->creerRessource($titre, $mise_en_avant, $idSae, $targetFile);
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