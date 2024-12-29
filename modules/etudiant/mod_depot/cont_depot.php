<?php
include_once "modules/etudiant/mod_depot/modele_depot.php";
include_once "modules/etudiant/mod_depot/vue_depot.php";
require_once "DossierHelper.php";

class ContDepot
{
    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        $this->modele = new ModeleDepot();
        $this->vue = new VueDepot();
    }

    public function exec()
    {
        $this->action = isset($_GET['action']) ? $_GET['action'] : "afficherDepot";
        if (!$this->estEtudiant()) {
            echo "Accès interdit. Vous devez être étudiant pour accéder à cette page.";
            return;
        }
        switch ($this->action) {
            case "afficherDepot":
                $this->afficherDepot();
                break;
            case "upload" :
                $this->upload();
                break;
        }
    }

    public function estEtudiant()
    {
        return $_SESSION["type_utilisateur"] === "etudiant";
    }

    public function afficherDepot()
    {
        $id_groupe = $_SESSION["id_groupe"];
        $id_projet = $_SESSION["id_projet"];
        $tabAllDepot = $this->modele->afficherAllDepot($id_groupe, $id_projet);
        $this->vue->afficherAllDepot($tabAllDepot);
    }

    public function upload()
    {
        if (isset($_FILES['uploaded_file']) && isset($_POST['id_rendu']) && $_FILES['uploaded_file']['error'] === UPLOAD_ERR_OK) {
            $idSae = $_SESSION["id_projet"];
            $idGroupe = $_SESSION['id_groupe'];

            $idRendu = $_POST['id_rendu'];

            $nomSae = $this->modele->getTitreSAE($idSae);
            $nomGroupe = $this->modele->getNomGroupe($idGroupe);
            $nomRendu = $this->modele->getNomRendu($idRendu);

            $uploadDossier = DossierHelper::getDossierPathDepot($nomSae, $idSae, $nomGroupe, $idGroupe, $nomRendu, $idRendu);

            // Extraire le nom et l'extension du fichier téléchargé
            $filename = basename($_FILES['uploaded_file']['name']);
            $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); // Extraire l'extension du fichier téléchargé
            $fichier = $uploadDossier . DIRECTORY_SEPARATOR . uniqid() . '-' . $filename;

            // Extensions autorisées et taille maximale du fichier
            $extensionAutorises = ['pdf', 'docx', 'png', 'jpg'];
            $maxFileSize = 10 * 1024 * 1024; // Taille maximale autorisée pour le fichier (10 Mo)

            // Vérification de l'extension et de la taille du fichier
            if (in_array($fileExtension, $extensionAutorises) && $_FILES['uploaded_file']['size'] <= $maxFileSize) {
                // Créer le dossier si nécessaire
                if (!is_dir($uploadDossier)) {
                    if (!mkdir($uploadDossier, 0777, true)) {
                        die("Impossible de créer le dossier de téléchargement.");
                    }
                }

                // Déplacer le fichier vers son emplacement final
                if (move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $fichier)) {
                    // Enregistrer le chemin du fichier dans la base de données
                    $this->modele->rendreDepot($idRendu, $fichier, $idGroupe);
                } else {
                    die("Erreur lors de l'upload du fichier.");
                }
            }
        }
        $this->afficherDepot();
    }
}