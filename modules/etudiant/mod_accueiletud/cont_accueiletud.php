<?php
include_once "modules/etudiant/mod_accueiletud/modele_accueiletud.php";
include_once "modules/etudiant/mod_accueiletud/vue_accueiletud.php";
require_once "ModeleCommun.php";
require_once "modules/etudiant/ModeleCommunEtudiant.php";
require_once "ControllerCommun.php";
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
        if (ControllerCommun::estEtudiant()) {
            switch ($this->action) {
                case "accueil":
                    $this->accueil();
                    break;
                case "choixSae" :
                    $this->choixSae();
                    break;
            }
        }else{
            echo "Accès interdit. Vous devez être étudiant pour accéder à cette page.";
        }
    }

    public function accueil() {
        $saeInscrit = $this->modele->saeInscrit($_SESSION['id_utilisateur']);
        $this->vue->afficherSaeGerer($saeInscrit);
    }

    public function choixSae()
    {
        if (isset($_GET['id'])) {
            $idProjet = $_GET['id'];
            $sections = [
                ["href" => "index.php?module=groupeetud&idProjet=" . $idProjet, "title" => "Groupe"],
                ["href" => "index.php?module=depotetud&idProjet=" . $idProjet, "title" => "Dépôt"],
                ["href" => "index.php?module=ressourceetud&idProjet=" . $idProjet, "title" => "Ressource"],
                ["href" => "index.php?module=soutenanceetud&idProjet=" . $idProjet, "title" => "Soutenance"],
                ["href" => "index.php?module=notesetud&idProjet=" . $idProjet, "title" => "Notes"]
            ];
            $idGroupe = ModeleCommunEtudiant::getGroupeForUser($idProjet, $_SESSION['id_utilisateur']);
            $titre = ModeleCommun::getTitreSAE($idProjet);
            $desc = ModeleCommun::getDescriptionSAE($idProjet);
            $allChamp = $this->modele->getAllChamp($idProjet, $idGroupe);
            $this->vue->afficherSaeDetails($titre, $desc, $sections, $allChamp);
        } else {
            $this->accueil();
        }
    }

}
