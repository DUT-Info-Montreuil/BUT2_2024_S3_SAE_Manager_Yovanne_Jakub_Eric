<?php
include_once "modules/professeur/mod_accueilprof/modele_accueilprof.php";
include_once "modules/professeur/mod_accueilprof/vue_accueilprof.php";
require_once "DossierManager.php";
require_once "ModeleCommun.php";

class ContAccueilProf
{
    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        $this->modele = new ModeleAccueilProf();
        $this->vue = new VueAccueilProf();
    }

    public function exec()
    {
        $this->action = isset($_GET['action']) ? $_GET['action'] : "accueil";
        if (!$this->estProfOuIntervenant()) {
            echo "Accès interdit. Vous devez être professeur ou intervenant pour accéder à cette page.";
            return;
        }
        switch ($this->action) {
            case "accueil":
                $this->accueil();
                break;
            case "creerSAEForm":
                $this->creerSAEForm();
                break;
            case "choixSae" :
                $this->choixSae();
                break;
            case "infoGeneralSae" :
                $this->infoGeneralSae();
                break;
            case "updateSae";
                $this->updateSae();
                break;
            case "creerSAE":
                $this->creerSAE();
                break;
            case "supprimerSAE" :
                $this->supprimerSAE();
                break;
        }
    }

    public function accueil()
    {
        $saeGerer = $this->modele->saeGerer($_SESSION['id_utilisateur']);
        $typeUser =  ModeleCommun::getTypeUtilisateur($_SESSION['id_utilisateur']);
        $this->vue->afficherSaeGerer($saeGerer, $typeUser);
    }

    public function creerSAEForm()
    {
        $this->vue->creerUneSAEForm();
    }

    public function creerSAE()
    {
        if (
            isset($_POST['titre']) && !empty(trim($_POST['titre'])) &&
            isset($_POST['annee']) && !empty(trim($_POST['annee'])) &&
            isset($_POST['semestre']) && !empty(trim($_POST['semestre'])) &&
            isset($_POST['description']) && !empty(trim($_POST['description']))
        ) {
            $titre = trim($_POST['titre']);
            $annee = trim($_POST['annee']);
            $semestre = trim($_POST['semestre']);
            $description = trim($_POST['description']);

            $idSae = $this->modele->ajouterProjet($titre, $annee, $description, $semestre);

            $nomSae = $this->modele->getTitreSAE($idSae);
            DossierManager::creerDossiersSAE($idSae, $nomSae);
        }
        $this->accueil();
    }


    public function estProfOuIntervenant(){
        $typeUser =  ModeleCommun::getTypeUtilisateur($_SESSION['id_utilisateur']);
        return $typeUser==="professeur" || $typeUser==="intervenant";
    }

    public function choixSae()
    {
        if (isset($_GET['id'])) {
            $idProjet = $_GET['id'];
            $_SESSION['id_projet'] = $idProjet;
            $titre = $this->modele->getTitreSAE($idProjet);
            $idUtilisateur = $_SESSION['id_utilisateur'];
            $role = ModeleCommun::getRoleSAE($idProjet, $idUtilisateur);
            $this->vue->afficherSaeDetails($titre, $role);
        } else {
            $this->accueil();
        }
    }



    public function infoGeneralSae()
    {
        $idProjet = $_SESSION['id_projet'];
        if ($idProjet) {
            $saeTabDetails = $this->modele->getSaeDetails($idProjet);
            $this->vue->afficherSaeInfoGeneral($saeTabDetails);
        } else {
            $this->accueil();
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
                $nomSae = $this->modele->getTitreSAE($idSae);

                $dossierRenomme = DossierManager::renomerBaseDossier($idSae, $nomSae, $nouveauTitre);

                if ($dossierRenomme) {
                    $this->modele->modifierInfoGeneralSae($idSae, $nouveauTitre, $annee, $semestre, $description);
                } else {
                    error_log("Erreur : Impossible de renommer le dossier de la SAE.");
                }
            }
        }
        $this->accueil();
    }


    public function supprimerSAE()
    {
        $idSae = $_SESSION['id_projet'];
        DossierManager::supprimerDossiersSAE($idSae, $this->modele->getTitreSAE($idSae));
        $this->modele->supprimerSAE($idSae);
        $this->accueil();
    }

}