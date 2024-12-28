<?php
include_once "modules/etudiant/mod_accueil/modele_accueil.php";
include_once  "modules/etudiant/mod_accueil/vue_accueil.php";
Class ContAccueil {
    private $modele;
    private $vue;
    private $action;

    public function __construct() {
        $this->modele = new ModeleAccueil();
        $this->vue = new VueAccueil();
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
        return $_SESSION["type_utilisateur"] === "etudiant";
    }

    public function accueil() {
        $saeInscrit = $this->modele->saeInscrit($_SESSION['id_utilisateur']);
        $this->vue->afficherSaeGerer($saeInscrit);
    }

    public function choixSae()
    {
        if (isset($_GET['id'])) {
            $idProjet = $_GET['id'];
            $_SESSION['id_projet'] = $idProjet;
            $titre = $this->modele->getTitreSAE($idProjet);
            $this->vue->afficherSaeDetails($titre);
        } else {
            $this->accueil();
        }
    }

}
