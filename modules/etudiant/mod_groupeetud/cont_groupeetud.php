<?php
include_once "modules/etudiant/mod_groupeetud/modele_groupeetud.php";
include_once "modules/etudiant/mod_groupeetud/vue_groupeetud.php";
require_once "ModeleCommun.php";
require_once "modules/etudiant/ModeleCommunEtudiant.php";
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
        if ($this->estEtudiant()) {
            switch ($this->action) {
                case "membreGroupeSAE":
                    $this->membreGroupeSAE();
                    break;
                case "updateChamps" :
                    $this->updateChamps();
                    break;
            }
        }else{
            echo "Accès interdit. Vous devez être étudiant pour accéder à cette page.";
        }

    }

    public function estEtudiant(){
        return ModeleCommun::getTypeUtilisateur($_SESSION['id_utilisateur']) === "etudiant";
    }

    public function membreGroupeSAE(){
        $idGroupe = ModeleCommunEtudiant::getGroupeForUser($_SESSION['id_projet'], $_SESSION['id_utilisateur']);
        $grpSAE = $this->modele->getGroupeSAE($idGroupe);
        $nomGrp = $this->modele->getNomGroupe($idGroupe);
        $idSae = $_SESSION['id_projet'];
        $champARemplir = $this->modele->getChampARemplir($idGroupe, $idSae);
        $this->vue->afficherGroupeSAE($grpSAE, $nomGrp, $champARemplir);
    }

    public function updateChamps() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idGroupe = ModeleCommunEtudiant::getGroupeForUser($_SESSION['id_projet'], $_SESSION['id_utilisateur']);
            foreach ($_POST as $key => $value) { //parcours tt les valeurs et name
                if (strpos($key, 'champ_') === 0) { //true si champ_ au début
                    $idChamp = str_replace('champ_', '', $key); //prend l'id
                    $champValeur = htmlspecialchars(trim($value));
                    if (!empty($champValeur)) {
                        $this->modele->updateChampGroupe($idGroupe, $idChamp, $champValeur);
                    }
                }
            }
        }
        $this->membreGroupeSAE();
    }

}