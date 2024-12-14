<?php
include_once 'modules/mod_connexion/modele_connexion.php';
include_once 'modules/mod_connexion/vue_connexion.php';
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
            default:
                $this->connexion();
                break;
        }
    }
    public function connexion(){
        if (isset($_SESSION['id_utilisateur'])) {
            echo "<p>Vous êtes déjà connecté sous l'identifiant.</p>";
        } else if (isset($_POST['login']) && isset($_POST['password'])) {
            $login = $_POST['login'];
            $password = $_POST['password'];
            $this->testConnexion($login, $password);
        }
        else {
            $this->vue->formConnexion();
        }
    }

    public function testConnexion($identifiant,$mdp){
        $utilisateur = $this->modele->verifierUtilisateur($identifiant, $mdp);
        if ($utilisateur) {
            $_SESSION['id_utilisateur'] = $utilisateur['id_utilisateur'];
            $_SESSION['mdp'] = $utilisateur['mdp'];
            $this->vue->connecter();
        }
    }

    public function inscription(){}
    public function deconnexion(){
        $this->vue->deconnexion();
        session_destroy();
    }

}