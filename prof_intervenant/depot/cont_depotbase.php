<?php

include_once "prof_intervenant/depot/cont_depotbase.php";
include_once "prof_intervenant/depot/vue_depotbase.php";
require_once "DossierManager.php";
class ContDepotBase {
    protected $modele;
    protected $vue;
    protected $action;

    public function __construct($modele, $vue) {
        $this->modele = $modele;
        $this->vue = $vue;
    }

    public function exec() {
        $this->action = isset($_GET['action']) ? $_GET['action'] : "gestionDepotSAE";
        if (!$this->estAutorise()) {
            echo "Accès interdit. Vous n'avez pas les autorisations nécessaires pour accéder à cette page.";
            return;
        }

        switch ($this->action) {
            case "gestionDepotSAE":
                $this->gestionDepotSAE();
                break;
            case "creerDepot":
                $this->creerDepot();
                break;
            case "submitDepot":
                $this->submitDepot();
                break;
            case "modifierDepot":
                $this->modifierDepot();
                break;
            case "supprimerDepot":
                $this->supprimerDepot();
                break;
            default:
                echo "Action non reconnue.";
                break;
        }
    }

    protected function estAutorise() {
        return false;
    }

    protected function gestionDepotSAE() {
        $idSae = $_SESSION['id_projet'];
        if ($idSae) {
            $allDepot = $this->modele->getAllDepotSAE($idSae);
            $this->vue->afficheAllDepotSAE($allDepot);
        }
    }

    protected function creerDepot() {
        $this->vue->formulaireCreerDepot();
    }

    protected function submitDepot() {
        if (isset($_POST['titre']) && trim($_POST['titre']) !== '' && isset($_POST['date_limite'])) {
            $titre = trim($_POST['titre']);
            $dateLimite = $_POST['date_limite'];
            $idSae = $_SESSION['id_projet'];

            $idRendu = $this->modele->creerDepot($titre, $dateLimite, $idSae);
            $nomRendu = $this->modele->getNomDepot($idRendu);
            $groupes = $this->modele->getGroupesParSae($idSae);
            $nomSae = $this->modele->getTitreSAE($idSae);

            foreach ($groupes as $groupe) {
                $nomGroupe = $groupe['nom'];
                $idGroupe = $groupe['id_groupe'];
                DossierManager::creerDepotPourGroupe($idSae, $nomSae, $idGroupe, $nomGroupe, $idRendu, $nomRendu);
            }
        }
        $this->gestionDepotSAE();
    }

    protected function modifierDepot() {
        if (isset($_POST['id_rendu']) && isset($_POST['date_limite']) && trim($_POST['titre']) !== '') {
            $nouveauNomDepot = trim($_POST['titre']);
            $dateLimite = $_POST['date_limite'];
            $id_rendu = $_POST['id_rendu'];
            $idSae = $_SESSION['id_projet'];

            $ancienNomDepot = $this->modele->getNomDepot($id_rendu);
            $this->modele->modifierRendu($id_rendu, $nouveauNomDepot, $dateLimite);
            $groupes = $this->modele->getGroupesParSae($idSae);
            $nomSae = $this->modele->getTitreSAE($idSae);

            foreach ($groupes as $groupe) {
                $idGroupe = $groupe['id_groupe'];
                $nomGroupe = $groupe['nom'];
                DossierManager::renommerDepotPourGroupe($idSae, $nomSae, $idGroupe, $nomGroupe, $id_rendu, $ancienNomDepot, $nouveauNomDepot);
            }
        }
        $this->gestionDepotSAE();
    }

    protected function supprimerDepot() {
        if (isset($_POST['id_rendu'])) {
            $id_rendu = $_POST['id_rendu'];
            $nomDepot = $this->modele->getNomDepot($id_rendu);

            $idSae = $_SESSION['id_projet'];
            $groupes = $this->modele->getGroupesParSae($idSae);
            $nomSae = $this->modele->getTitreSAE($idSae);

            foreach ($groupes as $groupe) {
                $idGroupe = $groupe['id_groupe'];
                $nomGroupe = $groupe['nom'];
                DossierManager::supprimerDepotPourGroupe($idSae, $nomSae, $idGroupe, $nomGroupe, $id_rendu, $nomDepot);
            }

            $this->modele->supprimerDepot($id_rendu);
        }
        $this->gestionDepotSAE();
    }
}
