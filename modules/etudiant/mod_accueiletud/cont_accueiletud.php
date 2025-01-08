<?php
include_once "modules/etudiant/mod_accueiletud/modele_accueiletud.php";
include_once "modules/etudiant/mod_accueiletud/vue_accueiletud.php";
require_once "ModeleCommun.php";
Class ContAccueilEtud {
    private $modele;
    private $vue;
    private $action;

    public function __construct() {
        $this->modele = new ModeleAccueilEtud();
        $this->vue = new VueAccueilEtud();
    }

    public function exec() {
        $this->action = isset($_GET['action']) ? $_GET['action'] : "accueil";
        if (!$this->estEtudiant()) {
            echo "Accès interdit. Vous devez être étudiant pour accéder à cette page.";
            return;
        }
        switch ($this->action) {
            case "accueil":
                $this->accueil();
                break;
            case "choixSae" :
                $this->choixSae();
                break;
        }
    }

    public function estEtudiant(){
        return ModeleCommun::getTypeUtilisateur($_SESSION['id_utilisateur']) === "etudiant";
    }
    public function accueil() {
        $saeInscrit = $this->modele->saeInscrit($_SESSION['id_utilisateur']);
        $this->vue->afficherSaeGerer($saeInscrit);
    }

    public function choixSae()
    {
        if (isset($_GET['id'])) {
            $idProjet = $_GET['id'];
            $_SESSION['id_groupe'] = $this->modele->getGroupeForUser($idProjet, $_SESSION['id_utilisateur']);
            $_SESSION['id_projet'] = $idProjet;
            $titre = ModeleCommun::getTitreSAE($idProjet);
            $this->vue->afficherSaeDetails($titre);
        } else {
            $this->accueil();
        }
    }

}
