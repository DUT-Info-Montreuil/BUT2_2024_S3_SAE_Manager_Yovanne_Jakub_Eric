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

            case "modifierPhotoDeProfil" :
                $this->modifierPhotoDeProfil();
                break;
        }

    }

    public function afficherCompte() {

        $compte = $this->modele->afficherCompte($_SESSION['id_utilisateur']);
        $this->vue->afficherCompte($compte);

    }

    public function modifierCompte() {
        // Vérifie si le formulaire est soumis
        if (isset($_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['login_utilisateur'])) {
            $id_utilisateur = $_SESSION['id_utilisateur'];

            // Vérifie quel champ a été modifié
            $nom = isset($_POST['nom']) ? $_POST['nom'] : null;
            $prenom = isset($_POST['prenom']) ? $_POST['prenom'] : null;
            $email = isset($_POST['email']) ? $_POST['email'] : null;
            $login_utilisateur = isset($_POST['login_utilisateur']) ? $_POST['login_utilisateur'] : null;
            $password_utilisateur = isset($_POST['password_utilisateur']) ? $_POST['password_utilisateur'] : null;

            if (empty($password_utilisateur)) {
                $password_utilisateur = null;  // Si aucun mot de passe n'est fourni, ne pas le modifier
            }

            // Mettre à jour uniquement les champs qui sont définis
            $this->modele->modifierCompte($id_utilisateur, $nom, $prenom, $email, $login_utilisateur, $password_utilisateur);

            echo "Modifications réussis !";
            $this->afficherCompte();

        }
    }

    public function modifierPhotoDeProfil() {
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
            $id_utilisateur = $_SESSION['id_utilisateur'];

            // Vérification du type et de la taille de l'image
            $allowedTypes = ['image/jpeg', 'image/png'];
            if (in_array($_FILES['logo']['type'], $allowedTypes) && $_FILES['logo']['size'] <= 500000){

                echo 'Type du fichier: ' . $_FILES['logo']['type']; // Afficher le type du fichier
                echo 'Taille du fichier: ' . $_FILES['logo']['size']; // Afficher la taille du fichier


                // Générer un nom unique pour l'image
                $imageName = 'profil_photo_' . $id_utilisateur . '.' . pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
                $uploadDir = 'photo_profil/'; // Répertoire où les images seront stockées
                $uploadPath = $uploadDir . $imageName;

                // Déplacer le fichier téléchargé vers le dossier photo_profil
                if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadPath)) {
                    // Si l'upload est réussi, mettre à jour la base de données avec le nom du fichier
                    $this->modele->modifierLogo($id_utilisateur, $imageName);

                    echo "Logo mis à jour avec succès!";
                } else {
                    echo "Erreur lors du téléchargement de l'image.";
                }
            } else {
                echo "Le fichier doit être une image JPG ou PNG et ne pas dépasser 500 Ko.";
            }
        } else {
            echo "Aucun fichier n'a été téléchargé.";
        }

        echo "Reussi";
        $this->afficherCompte();
    }



}