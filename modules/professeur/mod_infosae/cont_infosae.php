<?php
include_once "modules/professeur/mod_infosae/modele_infosae.php";
include_once "modules/professeur/mod_infosae/vue_infosae.php";
require_once "DossierManager.php";
require_once "ModeleCommun.php";
require_once "ControllerCommun.php";

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
        $this->action = isset($_GET['action']) ? $_GET['action'] : "gestionSAE";
        if (ControllerCommun::estProf()) {
            switch ($this->action) {
                case "gestionSAE" :
                    $this->gestionSAE();
                    break;
                case "infoGeneralSae" :
                    $this->infoGeneralSae();
                    break;
                case "updateSae";
                    $this->updateSae();
                    break;
                case "supprimerSAE" :
                    $this->supprimerSAE();
                    break;
                case "formAddChamp" :
                    $this->formAddChamp();
                    break;
                case "ajouterChamp" :
                    $this->ajouterChamp();
                    break;
                case "allChamp" :
                    $this->allChamp();
                    break;
                case "modifierChamp" :
                    $this->modifierChamp();
                    break;
                case "supprimerChamp" :
                    $this->supprimerChamp();
                    break;
            }
        } else {
            echo "Accès interdit. Vous devez être professeur pour accéder à cette page.";
        }

    }
    public function gestionSAE()
    {
        $idProjet = $_GET['idProjet'];
        $choix = [
            [
                'title' => 'Information Général',
                'link' => 'index.php?module=infosae&action=infoGeneralSae&idProjet='.$idProjet,
            ],
            [
                'title' => 'Gestion des champs',
                'link' => 'index.php?module=infosae&action=allChamp&idProjet='.$idProjet
            ]
        ];
        $this->vue->afficherChoix($choix);
    }

    public function formAddChamp()
    {
        $idSae = $_GET['idProjet'];
        $this->vue->afficherFormAddChamp($idSae);
    }

    public function supprimerChamp()
    {
        if (isset($_POST['id_champ'])) {
            $id_champ = $_POST['id_champ'];
            $this->modele->supprimerChamp($id_champ);
        }
        $this->gestionSAE();
    }

    public function supprimerSAE()
    {
        $idSae = $_GET['idProjet'];
        DossierManager::supprimerDossiersSAE($idSae, ModeleCommun::getTitreSAE($idSae));
        $this->modele->supprimerSAE($idSae);
        header("Location: index.php?module=accueilprof");
    }

    public function infoGeneralSae()
    {
        $idSae = $_GET['idProjet'];
        if ($idSae) {
            $saeTabDetails = $this->modele->getSaeDetails($idSae);
            $this->vue->afficherSaeInfoGeneral($saeTabDetails, $idSae);
        }
    }

    public function allChamp()
    {
        $idSae = $_GET['idProjet'];
        $allChamp = $this->modele->getAllChamp($idSae);
        $this->vue->afficherAllChamp($allChamp, $idSae);
    }

    public function updateSae()
    {
        $idSae = $_GET['idProjet'];
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
        $this->gestionSAE();
    }

    public function ajouterChamp()
    {
        if (isset($_POST['champ_nom']) && isset($_POST['rempli_par'])) {
            $champNom = trim($_POST['champ_nom']);
            $rempliPar = trim($_POST['rempli_par']);
            $idSae = $_GET['idProjet'];
            $idChamp = $this->modele->addChamp($champNom, $rempliPar, $idSae);
            $allGrp = $this->modele->getAllIdGroupeSAE($idSae);
            foreach ($allGrp as $grp) {
                $this->modele->addChampGrp($idChamp, $grp);
            }
        }
        $this->gestionSAE();
    }

    public function modifierChamp()
    {
        if (isset($_POST['champ_nom']) && isset($_POST['rempli_par']) && isset($_POST['id_champ'])) {
            $champNom = trim($_POST['champ_nom']);
            $rempliPar = trim($_POST['rempli_par']);
            $idChamp = $_POST['id_champ'];
            $this->modele->appliquerModifChamp($idChamp, $champNom, $rempliPar);
        }
        $this->gestionSAE();
    }

}