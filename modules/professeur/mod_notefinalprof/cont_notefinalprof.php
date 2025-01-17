<?php
include_once "modules/professeur/mod_notefinalprof/modele_notefinalprof.php";
include_once "modules/professeur/mod_notefinalprof/vue_notefinalprof.php";
require_once "DossierManager.php";
require_once "ModeleCommun.php";
require_once "ControllerCommun.php";
require_once "TokenManager.php";

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
                case "modifierNoteFinal" :
                    $this->modifierNoteFinal();
                    break;
                case "reinitialisernoteFinal" :
                    $this->reinitialisernoteFinal();
                    break;
            }
        } else {
            echo "Accès interdit. Vous devez être professeur pour accéder à cette page.";
        }

    }

    public function allNotesFinal(){
        TokenManager::stockerAndGenerateToken();
        $idSae = $_GET['idProjet'];
        $allNoteFinalAndEtudiant = $this->modele->getAllNoteFinalAndEtudiant($idSae);
        $this->vue->afficherAllNoteAndEtudiant($allNoteFinalAndEtudiant, $idSae);
    }

    public function modifierNoteFinal(){
        if (!TokenManager::verifierToken()) {
            die("Token invalide ou expiré.");
        }
        if(isset($_POST['id_utilisateur']) && isset($_POST['id_groupe']) && isset($_POST['note_finale'])){
            $id_utilisateur = $_POST['id_utilisateur'];
            $id_groupe = $_POST['id_groupe'];
            $note_finale = str_replace(',', '.', $_POST['note_finale']);
            if(is_numeric($note_finale)) {
                $this->modele->modifierNote($note_finale, $id_utilisateur, $id_groupe);
            } else {
                echo "Erreur : la note finale doit être un nombre.";
            }
        }
        $this->allNotesFinal();
    }


    public function reinitialisernoteFinal(){
        if (!TokenManager::verifierToken()) {
            die("Token invalide ou expiré.");
        }
        if(isset($_POST['id_utilisateur']) && isset($_POST['id_groupe'])){
            $id_utilisateur = $_POST['id_utilisateur'];
            $id_groupe = $_POST['id_groupe'];
            ModeleCommun::mettreAJourNoteFinale($id_utilisateur, $id_groupe);
        }
        $this->allNotesFinal();
    }
}