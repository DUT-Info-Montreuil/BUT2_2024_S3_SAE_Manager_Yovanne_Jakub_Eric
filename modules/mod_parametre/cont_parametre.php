<?php
include_once "modules/mod_parametre/modele_parametre.php";
include_once "modules/mod_parametre/vue_parametre.php";
require_once "ModeleCommun.php";
class ContParametre {

    private $modele;
    private $vue;
    private $action;

    public function __construct() {
        $this->modele = new ModeleParametre();
        $this->vue = new VueParametre();
    }


    public function exec() {
        $this->action = isset($_GET['action']) ? $_GET['action'] : "afficherCompte";
        switch ($this->action) {
            case "afficherCompte":
                $this->afficherCompte();
                break;
            case "modifierCompte" :
                $this->modifierCompte();
                break;
        }

    }

    public function afficherCompte() {

        $compte = $this->modele->afficherCompte($_SESSION['id_utilisateur']);
        $this->vue->afficherCompte($compte);
    }

    public function modifierCompte() {
        if (isset($_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['login_utilisateur'])) {
            $id_utilisateur = $_SESSION['id_utilisateur'];
            $nom = isset($_POST['nom']) ? $_POST['nom'] : null;
            $prenom = isset($_POST['prenom']) ? $_POST['prenom'] : null;
            $email = isset($_POST['email']) ? $_POST['email'] : null;
            $login_utilisateur = isset($_POST['login_utilisateur']) ? $_POST['login_utilisateur'] : null;
            $password_utilisateur = isset($_POST['password_utilisateur']) ? $_POST['password_utilisateur'] : null;

            if (empty($password_utilisateur)) {
                $password_utilisateur = null;
            }

            $this->modele->modifierCompte($id_utilisateur, $nom, $prenom, $email, $login_utilisateur, $password_utilisateur);
            $this->afficherCompte();

        }
    }

}