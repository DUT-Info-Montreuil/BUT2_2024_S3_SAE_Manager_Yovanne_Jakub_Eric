<?php
include_once "modules/etudiant/mod_depotetud/modele_depotetud.php";
include_once "modules/etudiant/mod_depotetud/vue_depotetud.php";
require_once "DossierManager.php";
require_once "ModeleCommun.php";
require_once "modules/etudiant/ModeleCommunEtudiant.php";
require_once "ControllerCommun.php";
class ContDepotEtud
{
    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        $this->modele = new ModeleDepotEtud();
        $this->vue = new VueDepotEtud();
    }

    public function exec()
    {
        $this->action = isset($_GET['action']) ? $_GET['action'] : "afficherDepot";
        if (ControllerCommun::estEtudiant()) {
            switch ($this->action) {
                case "afficherDepot":
                    $this->afficherDepot();
                    break;
                case "upload" :
                    $this->upload();
                    break;
                case "supprimerTravailRemis" :
                    $this->supprimerTravailRemis();
                    break;
            }
        }else{
            echo "Accès interdit. Vous devez être étudiant pour accéder à cette page.";
        }

    }

    public function afficherDepot()
    {
        $idSae = $_GET['idProjet'];
        $id_groupe = ModeleCommunEtudiant::getGroupeForUser($idSae, $_SESSION['id_utilisateur']);
        $tabAllDepot = $this->modele->getAllDepot($id_groupe, $idSae);

        if (!empty($tabAllDepot)){
            foreach ($tabAllDepot as &$depot) {
                $evaluation = $this->modele->getNoteEtCommentaire($depot['id_rendu'], $id_groupe);
                $depot['note'] = isset($evaluation['note']) ? $evaluation['note'] : null;
                $depot['commentaire'] = isset($evaluation['commentaire']) ? $evaluation['commentaire'] : null;

                $auteurEtDate = $this->modele->getAuteurEtDateRemise($depot['id_rendu'], $id_groupe);
                $depot['auteur'] = isset($auteurEtDate['nom']) && isset($auteurEtDate['prenom']) ? $auteurEtDate['nom'] . ' ' . $auteurEtDate['prenom'] : null;
                $depot['date_remise'] = isset($auteurEtDate['date_remise']) ? $auteurEtDate['date_remise'] : null;
            }
        }
        if(empty($tabAllDepot)){
            $this->vue->afficherMessageAucunDepot();
        }else{
            $this->vue->afficherAllDepot($tabAllDepot, $idSae);
        }

    }


    public function supprimerTravailRemis()
    {
        // Vérification que 'id_rendu' est bien passé en POST
        if (!isset($_POST['id_rendu']) || empty($_POST['id_rendu'])) {
            echo "Erreur : ID rendu non défini.";
            return;
        }
        // Récupération des variables
        $idRendu = $_POST['id_rendu'];
        $idSae = isset($_GET['idProjet']) ? $_GET['idProjet'] : null;
        if ($idSae === null) {
            echo "Erreur : ID projet manquant.";
            return;
        }
        $idUser = isset($_SESSION["id_utilisateur"]) ? $_SESSION["id_utilisateur"] : null;
        if ($idUser === null) {
            echo "Erreur : ID utilisateur non défini.";
            return;
        }
        // Récupération du groupe de l'utilisateur
        $idGroupe = ModeleCommunEtudiant::getGroupeForUser($idSae, $idUser);
        // Vérification que le groupe a bien été récupéré
        if ($idGroupe === null) {
            echo "Erreur : Groupe non trouvé pour cet utilisateur.";
            return;
        }
        // Récupération des fichiers associés au rendu
        $fichiers = $this->modele->getFichiersRemis($idRendu, $idGroupe);

        try {
            foreach ($fichiers as $fichier) {
                if (file_exists($fichier['chemin_fichier'])) {
                    DossierManager::supprimerFichier($fichier['chemin_fichier']);
                }
            }
            $this->modele->supprimerTousLesFichiersRendu($idRendu, $idGroupe);
        } catch (Exception $e) {
            echo "Erreur lors de la suppression des fichiers : " . $e->getMessage();
            return;
        }
        // Mise à jour du statut du rendu
        $this->modele->setRenduStatut($idRendu, $idGroupe, "En attente");
        $this->modele->setInfoRendu($idRendu, $idGroupe, null);
        // Retour à l'affichage du dépôt
        $this->afficherDepot();
    }







}