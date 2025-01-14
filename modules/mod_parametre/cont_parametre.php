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

            // Mettre à jour les informations du compte
            $this->modele->modifierCompte($id_utilisateur, $nom, $prenom, $email, $login_utilisateur, $password_utilisateur);
            echo "Information mis à jour. ";
            // Vérification et traitement de la photo de profil si un fichier a été téléchargé
            if (isset($_FILES['logo'])) {

                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                $fileExtension = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);

                if (in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png']) && $_FILES['logo']['size'] <= 500000) {
                    // Vérifiez si le dossier existe et a les bonnes permissions
                    $uploadDir = 'photo_profil/';

                    // Générer un nom unique pour l'image
                    $imageName = 'photo_de_profil' . '_'. $id_utilisateur . '.' . $fileExtension;
                    $uploadPath = $uploadDir . $imageName;

                    if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadPath)) {
                        $this->modele->modifierPhotoDeProfil($id_utilisateur, $uploadPath);
                        echo "Logo mis à jour avec succès!";
                    } else {
                        echo "Erreur lors du téléchargement de l'image.";
                    }
                } else {
                    echo "Le fichier doit être une image JPG ou PNG et ne pas dépasser 500 Ko.";
                }
            }

            // Afficher les modifications effectuées
            $this->afficherCompte();

        }
    }





}