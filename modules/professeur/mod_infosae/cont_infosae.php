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
        $this->action = isset($_GET['action']) ? $_GET['action'] : "gestionSAE";
        if (!$this->estProf()) {
            echo "Accès interdit. Vous devez être professeur pour accéder à cette page.";
            return;
        }
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
    }

    public function estProf()
    {
        return ModeleCommun::getTypeUtilisateur($_SESSION['id_utilisateur']) === "professeur";
    }

    public function gestionSAE(){
        $choix = [
            [
                'title' => 'Information Général',
                'link' => 'index.php?module=infosae&action=infoGeneralSae'
            ],
            [
                'title' => 'Gestion des champs',
                'link' => 'index.php?module=infosae&action=allChamp'
            ]
        ];
        $this->vue->afficherChoix($choix);
    }

    public function formAddChamp(){
        $this->vue->afficherFormAddChamp();
    }

    public function supprimerChamp(){
        if(isset($_POST['id_champ'])){
            $id_champ = $_POST['id_champ'];
            $this->modele->supprimerChamp($id_champ);
        }
        $this->gestionSAE();
    }

    public function supprimerSAE()
    {
        $idSae = $_SESSION['id_projet'];
        DossierManager::supprimerDossiersSAE($idSae, ModeleCommun::getTitreSAE($idSae));
        $this->modele->supprimerSAE($idSae);
        $this->gestionSAE();
    }

    public function infoGeneralSae()
    {
        $idProjet = $_SESSION['id_projet'];
        if ($idProjet) {
            $saeTabDetails = $this->modele->getSaeDetails($idProjet);
            $this->vue->afficherSaeInfoGeneral($saeTabDetails);
        }
    }

    public function allChamp(){
        $idSae = $_SESSION['id_projet'];
        $allChamp = $this->modele->getAllChamp($idSae);
        $this->vue->afficherAllChamp($allChamp);
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
        $this->gestionSAE();
    }
    public function ajouterChamp(){
        if(isset($_POST['champ_nom']) && isset($_POST['rempli_par'])){
            $champNom = trim($_POST['champ_nom']);
            $rempliPar = trim($_POST['rempli_par']);
            $idSae = $_SESSION['id_projet'];
            $idChamp = $this->modele->addChamp($champNom, $rempliPar, $idSae);
            $allGrp = $this->modele->getAllIdGroupeSAE($idSae);
            foreach ($allGrp as $grp) {
                $this->modele->addChampGrp($idChamp, $grp);
            }
        }
        $this->gestionSAE();
    }

    public function modifierChamp(){
        if(isset($_POST['champ_nom']) && isset($_POST['rempli_par']) && isset($_POST['id_champ'])){
            $champNom = trim($_POST['champ_nom']);
            $rempliPar = trim($_POST['rempli_par']);
            $idChamp = $_POST['id_champ'];
            $this->modele->appliquerModifChamp($idChamp, $champNom, $rempliPar);
        }
        $this->gestionSAE();
    }

}