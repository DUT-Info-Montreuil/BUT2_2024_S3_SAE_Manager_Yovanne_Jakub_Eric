<?php
include_once "modules/admin/mod_gestuser/modele_gestuser.php";
include_once "modules/admin/mod_gestuser/vue_gestuser.php";
require_once "ModeleCommun.php";
require_once "ControllerCommun.php";

class ContGestUser
{
    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        $this->modele = new ModeleGestUser();
        $this->vue = new VueGestUser();
    }

    public function exec()
    {
        $this->action = isset($_GET['action']) ? $_GET['action'] : "menuGestUser";
        if (ControllerCommun::estAdmin()) {
            switch ($this->action) {
                case "menuGestUser":
                    $this->menuGestUser();
                    break;
                case "versModifierDesUsers":
                    $this->versModifierDesUsers();
                    break;
                case "modifierUser" :
                    $this->modifierUser();
                    break;
                case "enregistrerModifications" :
                    $this->enregistrerModifications();
                    break;
                case "addUser" :
                    $this->addUser();
                    break;
                case "ajouterUser" :
                    $this->ajouterUser();
                    break;
                case "modifierUserCSV" :
                    $this->modifierUserCSV();
                    break;
                case 'ajouterUserCSV' :
                    $this->ajouterUserCSV();
            }
        }else{
            echo "Accès interdit. Vous devez être administrateur pour accéder à cette page.";
        }

    }

    public function menuGestUser()
    {
        $this->vue->afficherMenuGestionuser();
    }

    public function versModifierDesUsers()
    {
        $tabUser = $this->modele->getAllUser();
        $this->vue->afficherTableauAllUser($tabUser);
    }

    public function modifierUser()
    {
        if (isset($_POST['id_utilisateur'])) {
            $id_utilisateur = $_POST['id_utilisateur'];
            $user = $this->modele->getUserById($id_utilisateur);
            $this->vue->afficherInfoUser($user);
        }
    }

    public function enregistrerModifications()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_utilisateur = $_POST['id_utilisateur'];
            $nom = htmlspecialchars($_POST['nom']);
            $prenom = htmlspecialchars($_POST['prenom']);
            $email = htmlspecialchars($_POST['email']);
            $login = htmlspecialchars($_POST['login']);
//            $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;
            $password = htmlspecialchars($_POST['password']);
            $type = htmlspecialchars($_POST['type']);
            $this->modele->updateUser($id_utilisateur, $nom, $prenom, $email, $login, $password, $type);
        }
        $this->versModifierDesUsers();
    }

    public function addUser()
    {
        $this->vue->formulaireAjoutUser();
    }

    public function ajouterUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = htmlspecialchars($_POST['nom']);
            $prenom = htmlspecialchars($_POST['prenom']);
            $email = htmlspecialchars($_POST['email']);
            $login = htmlspecialchars($_POST['login']);
//            $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;
            $password = htmlspecialchars($_POST['password']);
            $type = htmlspecialchars($_POST['type']);
            $this->modele->addUser($nom, $prenom, $email, $login, $password, $type);
        }
        $this->menuGestUser();
    }

    public function modifierUserCSV() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST'&& isset($_FILES['csv_file'])) {
            $csvFile = $_FILES['csv_file'];

            if ($csvFile['error'] === UPLOAD_ERR_OK) {
                $filePath = $csvFile['tmp_name'];

                // Appeler la méthode du modèle pour traiter le fichier CSV
                $this->modele->updateUserCSV($filePath);

                // Retourner à la liste des utilisateurs avec un message de succès
                echo "Mise à jour réussie depuis le fichier CSV.";
                $this->versModifierDesUsers();
            } else {
                echo "Erreur lors du téléchargement du fichier CSV.";
            }
        } else {
            echo "Aucun fichier CSV fourni.";
        }
    }

    public function ajouterUserCSV() {
        // Vérification de la méthode de requête pour s'assurer que c'est une requête POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérification de l'existence du fichier CSV
            $csvFile = $_FILES['csv_file'];

            // Si le fichier a été téléchargé sans erreur
            if ($csvFile['error'] === UPLOAD_ERR_OK) {
                // Récupérer le chemin temporaire du fichier
                $filePath = $csvFile['tmp_name'];

                // Appeler la méthode du modèle pour ajouter les utilisateurs depuis le CSV
                $this->modele->addUserCSV($filePath);

                // Message de succès
                echo "Ajout des utilisateurs réussi depuis le fichier CSV.";

                // Rediriger ou afficher la liste des utilisateurs
                $this->versModifierDesUsers();  // Redirige vers la liste des utilisateurs ou une autre page
            } else {
                // Message d'erreur si le fichier n'est pas téléchargé correctement
                echo "Erreur lors du téléchargement du fichier CSV.";
            }
        }
    }


}