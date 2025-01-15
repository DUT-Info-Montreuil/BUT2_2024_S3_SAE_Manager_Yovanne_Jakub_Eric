<?php
include_once "modules/etudiant/mod_notesetud/modele_notesetud.php";
include_once "modules/etudiant/mod_notesetud/vue_notesetud.php";
require_once "ModeleCommun.php";
require_once "modules/etudiant/ModeleCommunEtudiant.php";
require_once "ControllerCommun.php";
Class ContNotesEtud
{
    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        $this->modele = new ModeleNotesetud();
        $this->vue = new VueNotesetud();
    }

    public function exec()
    {
        $this->action = isset($_GET['action']) ? $_GET['action'] : "allNotes";
        if (ControllerCommun::estEtudiant()) {
            switch ($this->action) {
                case "allNotes":
                    $this->allNotes();
                    break;
            }
        } else {
            echo "Accès interdit. Vous devez être étudiant pour accéder à cette page.";
        }

    }

    public function allNotes(){
        $idUtilisateur = $_SESSION['id_utilisateur'];
        $idSae = $_GET['idProjet'];
        $allNotes = $this->modele->getAllNotesSAE($idUtilisateur, $idSae);
        $noteFinal = $this->modele->getNoteFinal($idUtilisateur, $idSae);
        if(empty($allNotes) && empty($noteFinal)){
            $this->vue->afficherAucuneNoteDispo();
        }
        if (!empty($allNotes)) {
            $this->vue->afficherAllNotesSAE($allNotes);
        }
        if (!empty($noteFinal)) {
            $this->vue->afficherNoteFinal($noteFinal);
        }
    }
}