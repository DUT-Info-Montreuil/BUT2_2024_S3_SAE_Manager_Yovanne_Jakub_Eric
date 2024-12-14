<?php
include_once 'modules/mod_connexion/modele_connexion.php';
include_once 'modules/mod_connexion/vue_connexion.php';

include_once 'Connexion.php';

class ContConnexion extends Connexion{
    private $modele;
    private $vue;
    private $action;

    public function __construct() {
        $this->modele = new ModeleConnexion();
        $this->vue = new VueConnexion();
    }

    public function exec(){
        $this->action = isset($_POST['action']) ? $_POST['action'] : "default";
        switch ($this->action) {
            case "inscription":
                $this->inscription();
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
//        if($_SERVER['REQUEST_METHOD'] === 'POST'){
//
//        }
        $this->vue->formConnexion();
    }
    public function inscription(){}
    public function deconnexion(){}

}