<?php
include_once "modules/admin/mod_gestuser/modele_gestuser.php";
include_once "modules/admin/mod_gestuser/vue_gestuser.php";
require_once "ModeleCommun.php";

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
        if ($this->estAdmin()) {
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
            }
        }else{
            echo "Accès interdit. Vous devez être administrateur pour accéder à cette page.";
        }

    }

    public function estAdmin()
    {
        return ModeleCommun::getTypeUtilisateur($_SESSION['id_utilisateur']) === "admin";
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


}