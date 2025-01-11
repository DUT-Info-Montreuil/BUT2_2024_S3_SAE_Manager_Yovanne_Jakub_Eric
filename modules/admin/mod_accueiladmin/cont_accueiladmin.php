<?php
include_once "modules/admin/mod_accueiladmin/modele_accueiladmin.php";
include_once "modules/admin/mod_accueiladmin/vue_accueiladmin.php";
require_once "ModeleCommun.php";
require_once "ControllerCommun.php";
class ContAccueilAdmin
{
    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        $this->modele = new ModeleAccueilAdmin();
        $this->vue = new VueAccueilAdmin();
    }
    public function exec()
    {
        $this->action = isset($_GET['action']) ? $_GET['action'] : "accueil";
        if (ControllerCommun::estAdmin()) {
            switch ($this->action) {
                case "accueil":
                    $this->accueil();
                    break;
            }
        }else{
            echo "Accès interdit. Vous devez être administrateur pour accéder à cette page.";
        }

    }
    public function accueil(){
        $this->vue->afficherMenu();
    }

}