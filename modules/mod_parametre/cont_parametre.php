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

        }

    }

    public function afficherCompte() {

        $compte = $this->modele->afficherCompte($_SESSION['id_utilisateur']);
        $this->vue->afficherCompte($compte);

        echo "afficherCompte";
    }

}