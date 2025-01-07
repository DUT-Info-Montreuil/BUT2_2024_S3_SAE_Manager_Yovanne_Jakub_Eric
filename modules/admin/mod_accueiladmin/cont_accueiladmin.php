<?php
include_once "modules/admin/mod_accueiladmin/modele_accueiladmin.php";
include_once "modules/admin/mod_accueiladmin/vue_accueiladmin.php";
require_once "ModeleCommun.php";
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
        if (!$this->estAdmin()) {
            echo "Accès interdit. Vous devez être administrateur pour accéder à cette page.";
            return;
        }
        switch ($this->action) {
            case "accueil":
                $this->accueil();
                break;
        }
    }
    public function estAdmin(){
        return ModeleCommun::getTypeUtilisateur($_SESSION['id_utilisateur']) === "admin";
    }
    public function accueil(){
        $this->vue->afficherMenu();
    }

}