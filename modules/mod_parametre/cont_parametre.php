<?php
include_once "modules/mod_parametre/modele_parametre.php";
include_once "modules/mod_parametre/vue_parametre.php";
require_once "ModeleCommun.php";
require_once "TokenManager.php";
require_once "DossierManager.php";

class ContParametre
{

    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        $this->modele = new ModeleParametre();
        $this->vue = new VueParametre();
    }


    public function exec()
    {
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

    public function afficherCompte()
    {
        $idUtilisateur = $_SESSION['id_utilisateur'];
        $compte = $this->modele->getCompteById($idUtilisateur);
        $pictureName = $this->modele->getProfilPictureById($idUtilisateur);
        $imagePath = null;
        if($pictureName){
            $imagePath = glob("photo_profil/" . $pictureName);
        }
        $this->vue->afficherCompte($compte, $imagePath);

    }

    public function modifierCompte()
    {

        if (isset($_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['login_utilisateur'])) {
            $idUtilisateur = $_SESSION['id_utilisateur'];
            $nom = isset($_POST['nom']) ? $_POST['nom'] : null;
            $prenom = isset($_POST['prenom']) ? $_POST['prenom'] : null;
            $email = isset($_POST['email']) ? $_POST['email'] : null;
            $login_utilisateur = isset($_POST['login_utilisateur']) ? $_POST['login_utilisateur'] : null;
            $password_utilisateur = isset($_POST['password_utilisateur']) ? $_POST['password_utilisateur'] : null;
            $this->modele->modifierCompte($idUtilisateur, $nom, $prenom, $email, $login_utilisateur, $password_utilisateur);

            if (isset($_FILES['logoFile'])) {
                try {
                    $uploadPath = DossierManager::uploadPhotoProfil($_FILES['logoFile'], $idUtilisateur);
                    $this->modele->modifierPhotoDeProfil($idUtilisateur, $uploadPath);
                    $this->modele->modifierCheminProfilPicture($idUtilisateur, $uploadPath);
                } catch (Exception $e) {
                    echo "Erreur lors de l'upload de la photo de profil : " . $e->getMessage();
                }

                $this->afficherCompte();
            }
        }

        /*
         *
         * TOKEN A FAIRE ICI
         */

    }
}