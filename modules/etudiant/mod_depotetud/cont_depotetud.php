<?php
include_once "modules/etudiant/mod_depotetud/modele_depotetud.php";
include_once "modules/etudiant/mod_depotetud/vue_depotetud.php";
require_once "DossierManager.php";
require_once "ModeleCommun.php";

class ContDepotEtud
{
    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        $this->modele = new ModeleDepotEtud();
        $this->vue = new VueDepotEtud();
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
        return ModeleCommun::getTypeUtilisateur($_SESSION['id_utilisateur']) === "etudiant";
    }


    public function afficherDepot()
    {
        $id_groupe = $_SESSION["id_groupe"];
        $id_projet = $_SESSION["id_projet"];
        $tabAllDepot = $this->modele->getAllDepot($id_groupe, $id_projet);
        $this->vue->afficherAllDepot($tabAllDepot);
    }

    public function upload()
    {
        if (isset($_FILES['uploaded_files']) && isset($_POST['id_rendu'])) {
            $idSae = $_SESSION["id_projet"];
            $idGroupe = $_SESSION['id_groupe'];
            $idRendu = $_POST['id_rendu'];

            $nomSae = ModeleCommun::getTitreSAE($idSae);
            $nomGroupe = $this->modele->getNomGroupe($idGroupe);
            $nomRendu = $this->modele->getNomRendu($idRendu);

            $uploadDossier = DossierManager::getDossierPathDepot($nomSae, $idSae, $nomGroupe, $idGroupe, $nomRendu, $idRendu);

            foreach ($_FILES['uploaded_files']['name'] as $index => $fileName) {
                try {
                    $fichierSource = [
                        'name' => $_FILES['uploaded_files']['name'][$index],
                        'type' => $_FILES['uploaded_files']['type'][$index],
                        'tmp_name' => $_FILES['uploaded_files']['tmp_name'][$index],
                        'error' => $_FILES['uploaded_files']['error'][$index],
                        'size' => $_FILES['uploaded_files']['size'][$index]
                    ];

                    $cheminFichier = DossierManager::uploadFichier($fichierSource, $uploadDossier);
                    $this->modele->enregistrerFichierRendu($idRendu, $idGroupe, $fichierSource['name'], $cheminFichier);
                } catch (Exception $e) {
                    die("Erreur lors de l'upload du fichier : " . $e->getMessage());
                }
            }
            $this->modele->setRenduStatut($idRendu, $idGroupe,'Remis');
        }

        $this->afficherDepot();
    }
    public function supprimerTravailRemis()
    {
        if (isset($_POST['id_rendu'])) {
            $idRendu = $_POST['id_rendu'];
            $idGroupe = $_SESSION['id_groupe'];

            $fichiers = $this->modele->getFichiersRemis($idRendu, $idGroupe);

            try {
                foreach ($fichiers as $fichier) {
                    if (file_exists($fichier['chemin_fichier'])) {
                        DossierManager::supprimerFichier($fichier['chemin_fichier']);
                    }
                }
                $this->modele->supprimerTousLesFichiersRendu($idRendu, $idGroupe);
            } catch (Exception $e) {
                echo "Erreur lors de la suppression des fichiers : " . $e->getMessage();
                return;
            }
            $this->modele->setRenduStatut($idRendu, $idGroupe, "En attente");
        }

        $this->afficherDepot();
    }




}