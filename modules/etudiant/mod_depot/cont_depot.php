<?php
include_once "modules/etudiant/mod_depot/modele_depot.php";
include_once "modules/etudiant/mod_depot/vue_depot.php";
require_once "DossierManager.php";

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
            case "supprimerTravailRemis" :
                $this->supprimerTravailRemis();
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
        if (isset($_FILES['uploaded_file']) && isset($_POST['id_rendu'])) {
            $idSae = $_SESSION["id_projet"];
            $idGroupe = $_SESSION['id_groupe'];
            $idRendu = $_POST['id_rendu'];

            $nomSae = $this->modele->getTitreSAE($idSae);
            $nomGroupe = $this->modele->getNomGroupe($idGroupe);
            $nomRendu = $this->modele->getNomRendu($idRendu);

            $uploadDossier = DossierManager::getDossierPathDepot($nomSae, $idSae, $nomGroupe, $idGroupe, $nomRendu, $idRendu);

            try {
                $cheminFichier = DossierManager::uploadFichier($_FILES['uploaded_file'], $uploadDossier);
                $this->modele->rendreDepot($idRendu, $cheminFichier, $idGroupe);

            } catch (Exception $e) {
                die("Erreur lors de l'upload : " . $e->getMessage());
            }
        }

        $this->afficherDepot();
    }


    public function supprimerTravailRemis()
    {
        if (isset($_POST['id_rendu'])) {
            $idRendu = $_POST['id_rendu'];
            $idGroupe = $_SESSION['id_groupe'];

            $cheminFichier = $this->modele->getCheminFichierRemis($idRendu, $idGroupe);

            try {
                DossierManager::supprimerFichier($cheminFichier);
                $this->modele->supprimerTravailRemis($idRendu, $idGroupe);
            } catch (Exception $e) {
                echo "Erreur lors de la suppression de fichier : " . $e->getMessage();
                return;
            }
        }
        $this->afficherDepot();
    }

}