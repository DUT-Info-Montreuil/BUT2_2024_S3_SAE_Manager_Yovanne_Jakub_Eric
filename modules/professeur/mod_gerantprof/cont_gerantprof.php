<?php

include_once 'modules/professeur/mod_gerantprof/modele_gerantprof.php';
include_once 'modules/professeur/mod_gerantprof/vue_gerantprof.php';
require_once "ModeleCommun.php";
require_once "ControllerCommun.php";
class ContGerantProf
{
    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        $this->modele = new ModeleGerantProf();
        $this->vue = new VueGerantProf();
    }

    public function exec()
    {
        $this->action = isset($_GET['action']) ? $_GET['action'] : "gestionGerantSAE";
        if (ControllerCommun::estProf()) {
            switch ($this->action) {
                case "gestionGerantSAE":
                    $this->gestionGerantSAE();
                    break;
                case "versModifierGerant":
                    $this->versModifierGerant();
                    break;
                case "ajouterGerantFormulaire" :
                    $this->ajouterGerantFormulaire();
                    break;
                case "ajouterGerants" :
                    $this->ajouterGerants();
                    break;
                case "enregistrerModificationsGerant" :
                    $this->enregistrerModificationsGerant();
                    break;
                case "supprimerGerant" :
                    $this->supprimerGerant();
                    break;
            }
        }else{
            echo "Accès interdit. Vous devez être professeur pour accéder à cette page.";
        }

    }
    public function gestionGerantSAE(){
        $idSae = $_GET['idProjet'];
        if ($idSae) {
            $gerantSAE = $this->modele->getGerantSAE($idSae);
            $this->vue->afficherGerantSAE($gerantSAE, $idSae);
        }
    }

    public function versModifierGerant()
    {
        $idSae = $_GET['idProjet'];
        if (isset($_GET['idGerant'])) {
            $idGerant = intval($_GET['idGerant']);
            $tabDetailsGerant = $this->modele->getGerantById($idSae, $idGerant);
            if ($tabDetailsGerant) {
                $this->vue->formulaireModifierGerant($tabDetailsGerant, $idGerant, $idSae);
            }
        }
    }


    public function ajouterGerantFormulaire() {
        $idSae = $_GET['idProjet'];
        if($idSae) {
            $professeurs = $this->modele->getProfesseurNonGerant($idSae);
            $this->vue->afficherFormulaireAjoutGerant($professeurs, $idSae);
        }
    }

    public function ajouterGerants() {
        $idSae = $_GET['idProjet'];
        if ($idSae) {
            if (isset($_POST['role_gerant']) && isset($_POST['gerants'])) {
                $roleGerant = $_POST['role_gerant'];
                $gerantsId = $_POST['gerants'];
                foreach ($gerantsId as $gerantId) {
                    $this->modele->ajouterGerantSAE($gerantId, $roleGerant, $idSae);
                }
            }
        }
        $this->gestionGerantSAE();
    }

    public function enregistrerModificationsGerant(){
        $idSae = $_GET['idProjet'];
        if ($idSae) {
            if (isset($_POST['id_utilisateur']) && isset($_POST['role_gerant'])) {
                $roleGerant = $_POST['role_gerant'];
                $id_utilisateur = $_POST['id_utilisateur'];
                $this->modele->modifierRoleGerant($idSae, $id_utilisateur, $roleGerant);
            }
        }
        $this->gestionGerantSAE();
    }

    public function supprimerGerant(){
        $idSae = $_GET['idProjet'];
        if ($idSae) {
            if(isset($_POST['idGerant'])) {
                $idGerant = intval($_POST['idGerant']);
                $this->modele->supprimerGerantSAE($idSae, $idGerant);
            }
        }
        $this->gestionGerantSAE();
    }
}

