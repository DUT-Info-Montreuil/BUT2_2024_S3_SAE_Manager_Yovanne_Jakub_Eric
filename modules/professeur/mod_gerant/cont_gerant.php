<?php

include_once 'modules/professeur/mod_gerant/modele_gerant.php';
include_once 'modules/professeur/mod_gerant/vue_gerant.php';

class ContGerant
{
    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        $this->modele = new ModeleGerant();
        $this->vue = new VueGerant();
    }

    public function exec()
    {
        $this->action = isset($_GET['action']) ? $_GET['action'] : "gestionGerantSAE";

        switch ($this->action) {
            case "gestionGerantSAE":
                $this->gestionGerantSAE();
                break;
            case "versModifierGerant":
                $this->versModifierGerant();
                break;
            case "ajouterGerantFormulaire" :
                $this->ajouterGerantFormulaire();
                break;
        }
    }

    public function gestionGerantSAE(){
        $idSae = $_SESSION['id_projet'];
        if ($idSae) {
            $gerantSAE = $this->modele->getGerantSAE($idSae);
            $this->vue->afficherGerantSAE($gerantSAE);
        }
    }

    public function versModifierGerant(){
        echo "versModifierGerant";
    }

    public function ajouterGerantFormulaire() {
        echo "ajouterGerantFormulaire";
    }
}

