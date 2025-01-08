<?php
include_once "modules/professeur/mod_infosae/modele_infosae.php";
include_once "modules/professeur/mod_infosae/vue_infosae.php";
require_once "DossierManager.php";
require_once "ModeleCommun.php";

class ContInfoSae
{
    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        $this->modele = new ModeleInfoSae();
        $this->vue = new VueInfoSae();
    }

    public function exec()
    {
        $this->action = isset($_GET['action']) ? $_GET['action'] : "infoGeneralSae";
        if (!$this->estProf()) {
            echo "Accès interdit. Vous devez être professeur pour accéder à cette page.";
            return;
        }
        switch ($this->action) {
            case "infoGeneralSae" :
                $this->infoGeneralSae();
                break;
            case "updateSae";
                $this->updateSae();
                break;
            case "supprimerSAE" :
                $this->supprimerSAE();
                break;
        }
    }

    public function estProf()
    {
        return ModeleCommun::getTypeUtilisateur($_SESSION['id_utilisateur']) === "professeur";
    }

    public function supprimerSAE()
    {
        $idSae = $_SESSION['id_projet'];
        DossierManager::supprimerDossiersSAE($idSae, ModeleCommun::getTitreSAE($idSae));
        $this->modele->supprimerSAE($idSae);
        $this->redirigeVersHome();
    }

    public function infoGeneralSae()
    {
        $idProjet = $_SESSION['id_projet'];
        if ($idProjet) {
            $saeTabDetails = $this->modele->getSaeDetails($idProjet);
            $this->vue->afficherSaeInfoGeneral($saeTabDetails);
        }
    }

    public function updateSae()
    {
        $idSae = $_SESSION['id_projet'];
        if ($idSae) {
            if (isset($_POST['titre']) && isset($_POST['annee_universitaire']) && isset($_POST['semestre']) && isset($_POST['description_projet'])) {
                $nouveauTitre = trim($_POST['titre']);
                $annee = trim($_POST['annee_universitaire']);
                $semestre = trim($_POST['semestre']);
                $description = trim($_POST['description_projet']);
                $nomSae = ModeleCommun::getTitreSAE($idSae);

                $dossierRenomme = DossierManager::renomerBaseDossier($idSae, $nomSae, $nouveauTitre);

                if ($dossierRenomme) {
                    $this->modele->modifierInfoGeneralSae($idSae, $nouveauTitre, $annee, $semestre, $description);
                } else {
                    error_log("Erreur : Impossible de renommer le dossier de la SAE.");
                }
            }
        }
        $this->redirigeVersHome();
    }

    public function redirigeVersHome(){
        header("Location: index.php?module=accueilprof");
    }

}