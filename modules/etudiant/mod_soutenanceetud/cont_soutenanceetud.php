<?php
include_once "modules/etudiant/mod_soutenanceetud/modele_soutenanceetud.php";
include_once "modules/etudiant/mod_soutenanceetud/vue_soutenanceetud.php";
require_once "ModeleCommun.php";
require_once "modules/etudiant/ModeleCommunEtudiant.php";
Class ContSoutenanceEtud
{
    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        $this->modele = new ModeleSoutenanceEtud();
        $this->vue = new VueSoutenanceEtud();
    }

    public function exec()
    {
        $this->action = isset($_GET['action']) ? $_GET['action'] : "affichageDesSoutenances";
        if (!$this->estEtudiant()) {
            echo "Accès interdit. Vous devez être étudiant pour accéder à cette page.";
            return;
        }
        switch ($this->action) {
            case "affichageDesSoutenances":
                $this->affichageDesSoutenances();
                break;
        }
    }

    public function estEtudiant(){
        return ModeleCommun::getTypeUtilisateur($_SESSION['id_utilisateur']) === "etudiant";
    }

    public function affichageDesSoutenances()
    {
        $id_groupe = ModeleCommunEtudiant::getGroupeForUser($_SESSION['id_projet'], $_SESSION['id_utilisateur']);
        $idProjet = $_SESSION["id_projet"];
        $allSoutenance = $this->modele->getAllSoutenances($idProjet);

        foreach ($allSoutenance as &$soutenance) {
            $evaluation = $this->modele->getNoteEtCommentaire($soutenance['id_soutenance'], $id_groupe);
            $soutenance['note'] = isset($evaluation['note']) ? $evaluation['note'] : null;
            $soutenance['commentaire'] = isset($evaluation['commentaire']) ? $evaluation['commentaire'] : null;
        }
        $this->vue->afficherAllSoutenances($allSoutenance);
    }

}