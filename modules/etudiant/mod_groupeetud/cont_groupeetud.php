<?php
include_once "modules/etudiant/mod_groupeetud/modele_groupeetud.php";
include_once "modules/etudiant/mod_groupeetud/vue_groupeetud.php";
require_once "ModeleCommun.php";
Class ContGroupeEtud
{
    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        $this->modele = new ModeleGroupeEtud();
        $this->vue = new VueGroupeEtud();
    }

    public function exec()
    {
        $this->action = isset($_GET['action']) ? $_GET['action'] : "membreGroupeSAE";
        if (!$this->estEtudiant()) {
            echo "Accès interdit. Vous devez être étudiant pour accéder à cette page.";
            return;
        }
        switch ($this->action) {
            case "membreGroupeSAE":
                $this->membreGroupeSAE();
                break;
        }
    }

    public function estEtudiant(){
        return ModeleCommun::getTypeUtilisateur($_SESSION['id_utilisateur']) === "etudiant";
    }

    public function membreGroupeSAE(){
        $idGroupe = $_SESSION['id_groupe'];
        $grpSAE = $this->modele->getGroupeSAE($idGroupe);
        $nomGrp = $this->modele->getNomGroupe($idGroupe);
        $this->vue->afficherGroupeSAE($grpSAE, $nomGrp);
    }
}