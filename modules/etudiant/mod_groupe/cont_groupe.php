<?php
include_once "modules/etudiant/mod_groupe/modele_groupe.php";
include_once  "modules/etudiant/mod_groupe/vue_groupe.php";
Class ContGroupe
{
    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        $this->modele = new ModeleGroupe();
        $this->vue = new VueGroupe();
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
        return $_SESSION["type_utilisateur"] === "etudiant";
    }

    public function membreGroupeSAE(){
        $idGroupe = $_SESSION['id_groupe'];
        $grpSAE = $this->modele->getGroupeSAE($idGroupe);
        $nomGrp = $this->modele->getNomGroupe($idGroupe);
        $this->vue->afficherGroupeSAE($grpSAE, $nomGrp);
    }
}