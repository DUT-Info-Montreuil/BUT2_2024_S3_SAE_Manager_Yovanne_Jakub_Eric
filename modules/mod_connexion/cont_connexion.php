<?php
include_once 'modules/mod_connexion/modele_connexion.php';
include_once 'modules/mod_connexion/vue_connexion.php';
include_once 'ModeleCommun.php';
class ContConnexion {
    private $modele;
    private $vue;
    private $action;

    public function __construct() {
        $this->modele = new ModeleConnexion();
        $this->vue = new VueConnexion();
    }

    public function exec() {
        $this->action = isset($_GET['action']) ? $_GET['action'] : "connexion";
        if(!$this->userDejaConnecter()){
            switch ($this->action) {
                case "inscription":
                    $this->inscription();
                    break;
                case "connexion":
                    $this->connexion();
                    break;
                case "deconnexion":
                    $this->deconnexion();
                    break;
            }
        }else{
            $this->redirectionAccueil();
        }

    }

    public function userDejaConnecter(){
        if (isset($_SESSION['id_utilisateur'])) {
            if ($this->action == 'connexion' || $this->action == 'inscription') {
                return true;
            }
        }

        return false;
    }
    public function connexion() {
       if (!isset($_SESSION['id_utilisateur']) && isset($_POST['login']) && isset($_POST['password'])) {
            $login = trim($_POST['login']);
            $password = trim($_POST['password']);

            if (!empty($login) && !empty($password)) {
                $this->testConnexion($login, $password);
            } else {
                $this->vue->formConnexion();
            }
        } else {
            $this->vue->formConnexion();
        }
    }

    public function testConnexion($login, $mdp) {
        $utilisateur = $this->modele->verifierUtilisateur($login, $mdp);
        if ($utilisateur) {
            $_SESSION['id_utilisateur'] = $utilisateur['id_utilisateur'];
            $_SESSION['token_connexion'] = bin2hex(random_bytes(32));
            setcookie('token_connexion', $_SESSION['token_connexion'], time() + 1800, '/', null, true, true); // token dans le cookie
            /*
             * $_SESSION['token_connexion'],temps de vie,
             *                              chemin pour lequel le cookie est valide,
             *                              domaine actuel du site (ici c'est tout),
             *                              uniquement https ?,
             *                              accessible via http ?);
             */
            $_SESSION['timestamp'] = time();
            $this->redirectionAccueil();
        } else {
            $this->vue->formConnexion();
        }
    }

    public function redirectionAccueil(){
        $typeUtilisateur = ModeleCommun::getTypeUtilisateur($_SESSION['id_utilisateur']);
        if($typeUtilisateur=="etudiant"){
            header('Location: index.php?module=accueiletud');
        }else if ($typeUtilisateur=="professeur" ||$typeUtilisateur=="intervenant"){
            header('Location: index.php?module=accueilprof');
        }else if ($typeUtilisateur=="admin"){
            header('Location: index.php?module=accueiladmin');
        }
    }


    public function inscription() {
        if (
            isset($_POST['nom']) && !empty(trim($_POST['nom'])) &&
            isset($_POST['prenom']) && !empty(trim($_POST['prenom'])) &&
            isset($_POST['email']) && !empty(trim($_POST['email'])) &&
            isset($_POST['login']) && !empty(trim($_POST['login'])) &&
            isset($_POST['password']) && !empty(trim($_POST['password']))
        ) {
            $nom = trim($_POST['nom']);
            $prenom = trim($_POST['prenom']);
            $email = trim($_POST['email']);
            $login = trim($_POST['login']);
            $password = trim($_POST['password']);
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $this->modele->ajouterUtilisateur($nom, $prenom, $email, $login, $password_hash);
        }
        $this->vue->formInscription();
    }
    public function deconnexion() {
        session_destroy();
        header('Location: index.php?action=connexion');
    }


}