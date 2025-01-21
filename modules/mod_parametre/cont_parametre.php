<?php
include_once "modules/mod_parametre/modele_parametre.php";
include_once "modules/mod_parametre/vue_parametre.php";
require_once "ModeleCommun.php";
require_once "DossierManager.php";
require_once "TokenManager.php";

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
        TokenManager::stockerAndGenerateToken();
        $idUtilisateur = $_SESSION['id_utilisateur'];
        $compte = $this->modele->getCompteById($idUtilisateur);
        $pictureName = $this->modele->getProfilPictureById($idUtilisateur);
        $imagePath = null;
        if ($pictureName) {
            $imagePath = glob("photo_profil/" . $pictureName);
        }
        $anneeScolaire = null;
        if(ModeleCommun::getTypeUtilisateur($idUtilisateur)==='Etudiant'){
            $anneeScolaire = $this->modele->getAnneeScolaireByEtudiant($idUtilisateur);
        }


        $this->vue->afficherCompte($compte, $imagePath, $anneeScolaire);
    }


    public function modifierCompte()
    {
        if (!TokenManager::verifierToken()) {
            die("Token invalide ou expiré.");
        }
        if (isset($_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['login_utilisateur'], $_POST['annee_debut'], $_POST['annee_fin'], $_POST['semestre'])) {
            $idUtilisateur = $_SESSION['id_utilisateur'];
            $nom = isset($_POST['nom']) ? $_POST['nom'] : null;
            $prenom = isset($_POST['prenom']) ? $_POST['prenom'] : null;
            $email = isset($_POST['email']) ? $_POST['email'] : null;
            $login_utilisateur = isset($_POST['login_utilisateur']) ? $_POST['login_utilisateur'] : null;
            $password_utilisateur = isset($_POST['password_utilisateur']) ? $_POST['password_utilisateur'] : null;
            $annee_debut = isset($_POST['annee_debut']) ? $_POST['annee_debut'] : null;
            $annee_fin = isset($_POST['annee_fin']) ? $_POST['annee_fin'] : null;
            $semestre = isset($_POST['semestre']) ? $_POST['semestre'] : null;
            $this->modele->modifierCompte($idUtilisateur, $nom, $prenom, $email, $login_utilisateur, $password_utilisateur);
            $this->modele->modifierAnneeScolaire($idUtilisateur, $annee_debut, $annee_fin, $semestre);

            if (isset($_FILES['logoFile']) && $_FILES['logoFile']['error'] == 0 && !empty($_FILES['logoFile']['tmp_name'])) {
                try {
                    $uploadPath = DossierManager::uploadPhotoProfil($_FILES['logoFile'], $idUtilisateur);
                    $this->modele->modifierPhotoDeProfil($idUtilisateur, $uploadPath);
                    $this->modele->modifierCheminProfilPicture($idUtilisateur, $uploadPath);
                } catch (Exception $e) {
                    echo "Erreur lors de l'upload de la photo de profil : " . $e->getMessage();
                }
            }
            $this->afficherCompte();
        }
    }
}