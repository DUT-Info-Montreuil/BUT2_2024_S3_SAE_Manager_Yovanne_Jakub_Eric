<?php

include_once 'modules/professeur/mod_gerant/modele_gerant.php';
include_once 'modules/professeur/mod_gerant/vue_gerant.php';

class ContGerant
{
    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        $this->modele = new ModeleGerant();
        $this->vue = new VueGerant();
    }

    public function exec()
    {
        $this->action = isset($_GET['action']) ? $_GET['action'] : "gestionGerantSAE";

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
    }

    public function gestionGerantSAE(){
        $idSae = $_SESSION['id_projet'];
        if ($idSae) {
            $gerantSAE = $this->modele->getGerantSAE($idSae);
            $this->vue->afficherGerantSAE($gerantSAE);
        }
    }

    public function versModifierGerant()
    {
        $idSae = $_SESSION['id_projet'];
        if (isset($_GET['idGerant'])) {
            $idGerant = intval($_GET['idGerant']);
            $tabDetailsGerant = $this->modele->getGerantById($idSae, $idGerant);
            if ($tabDetailsGerant) {
                $this->vue->formulaireModifierGerant($tabDetailsGerant, $idGerant);
            }
        }
    }


    public function ajouterGerantFormulaire() {
        $idSae = $_SESSION['id_projet'];
        if($idSae) {
            $professeurs = $this->modele->getProfesseurNonGerant($idSae);
            $this->vue->afficherFormulaireAjoutGerant($professeurs);
        }
    }

    public function ajouterGerants() {
        $idSae = $_SESSION['id_projet'];
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
        $idSae = $_SESSION['id_projet'];
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
        $idSae = $_SESSION['id_projet'];
        if ($idSae) {
            if(isset($_POST['idGerant'])) {
                $idGerant = intval($_POST['idGerant']);
                echo $idGerant;
                $this->modele->supprimerGerantSAE($idSae, $idGerant);
            }
        }
        $this->gestionGerantSAE();
    }
}

