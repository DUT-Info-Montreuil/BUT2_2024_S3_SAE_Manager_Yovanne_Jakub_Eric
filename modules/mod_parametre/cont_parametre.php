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

        echo "afficherCompte";
    }

    public function modifierCompte() {
        // Vérifie si le formulaire est soumis
        if (isset($_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['login_utilisateur'])) {
            $id_utilisateur = isset($_GET['id_utilisateur']) ? $_GET['id_utilisateur'] : null;
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $login_utilisateur = $_POST['login_utilisateur'];
            $password_utilisateur = $_POST['password_utilisateur'];

            // Si le mot de passe a été fourni, on le hache
            if (!empty($password_utilisateur)) {
                $password_utilisateur = password_hash($password_utilisateur, PASSWORD_DEFAULT);
            } else {
                // Si aucun mot de passe n'est fourni, on ne modifie pas le mot de passe
                $password_utilisateur = null;
            }

            // Mettre à jour les informations dans la base de données
            $this->modele->modifierCompte($id_utilisateur, $nom, $prenom, $email, $login_utilisateur, $password_utilisateur);

            // Afficher un message de succès et rediriger ou afficher les nouvelles données
            echo "Informations mises à jour avec succès!";
            $this->afficherCompte();
        }
    }

}