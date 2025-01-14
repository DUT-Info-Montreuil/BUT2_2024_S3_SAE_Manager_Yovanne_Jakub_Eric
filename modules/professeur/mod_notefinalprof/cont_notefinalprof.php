<?php
include_once "modules/professeur/mod_notefinalprof/modele_notefinalprof.php";
include_once "modules/professeur/mod_notefinalprof/vue_notefinalprof.php";
require_once "DossierManager.php";
require_once "ModeleCommun.php";
require_once "ControllerCommun.php";

class ContNoteFinalProf
{
    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        $this->modele = new ModeleNoteFinalProf();
        $this->vue = new VueNoteFinalProf();
    }

    public function exec()
    {
        $this->action = isset($_GET['action']) ? $_GET['action'] : "allNotesFinal";
        if (ControllerCommun::estProf()) {
            switch ($this->action) {
                case "allNotesFinal" :
                    $this->allNotesFinal();
                    break;
            }
        } else {
            echo "Accès interdit. Vous devez être professeur pour accéder à cette page.";
        }

    }

    public function allNotesFinal(){
        $idSae = $_SESSION['id_projet'];
        $allNoteFinalAndEtudiant = $this->modele->getAllNoteFinalAndEtudiant($idSae);
        $this->vue->afficherAllNoteAndEtudiant($allNoteFinalAndEtudiant);
    }
}