<?php
include_once "modules/professeur/mod_accueilprof/modele_accueilprof.php";
include_once "modules/professeur/mod_accueilprof/vue_accueilprof.php";
require_once "DossierManager.php";
require_once "ModeleCommun.php";
require_once "ControllerCommun.php";

class ContAccueilProf
{
    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        $this->modele = new ModeleAccueilProf();
        $this->vue = new VueAccueilProf();
    }

    public function exec()
    {
        $this->action = isset($_GET['action']) ? $_GET['action'] : "accueil";
        if (ControllerCommun::estProfOuIntervenant()) {
            switch ($this->action) {
                case "accueil":
                    $this->accueil();
                    break;
                case "creerSAEForm":
                    $this->creerSAEForm();
                    break;
                case "choixSae" :
                    $this->choixSae();
                    break;
                case "creerSAE":
                    $this->creerSAE();
                    break;
            }
        }else{
            echo "Accès interdit. Vous devez être professeur ou intervenant pour accéder à cette page.";
        }

    }

    public function accueil()
    {
        $saeGerer = $this->modele->saeGerer($_SESSION['id_utilisateur']);
        $typeUser =  ModeleCommun::getTypeUtilisateur($_SESSION['id_utilisateur']);
        $this->vue->afficherSaeGerer($saeGerer, $typeUser);
    }

    public function creerSAEForm()
    {
        $this->vue->creerUneSAEForm();
    }

    public function creerSAE()
    {
        if (
            isset($_POST['titre']) && !empty(trim($_POST['titre'])) &&
            isset($_POST['annee']) && !empty(trim($_POST['annee'])) &&
            isset($_POST['semestre']) && !empty(trim($_POST['semestre'])) &&
            isset($_POST['description']) && !empty(trim($_POST['description']))
        ) {
            $titre = trim($_POST['titre']);
            $annee = trim($_POST['annee']);
            $semestre = trim($_POST['semestre']);
            $description = trim($_POST['description']);

            $idSae = $this->modele->ajouterProjet($titre, $annee, $description, $semestre);

            $nomSae = ModeleCommun::getTitreSAE($idSae);
            DossierManager::creerDossiersSAE($idSae, $nomSae);
        }
        $this->accueil();
    }

    public function choixSae()
    {
        if (isset($_GET['id'])) {
            $idProjet = $_GET['id'];
            $sections = [
                "Responsable" => [
                    ["href" => "index.php?module=infosae&idProjet=$idProjet", "title" => "Gestion de la SAE"],
                    ["href" => "index.php?module=groupeprof&action=gestionGroupeSAE&idProjet=$idProjet", "title" => "Groupe"],
                    ["href" => "index.php?module=gerantprof&idProjet=$idProjet", "title" => "Gérant"],
                    ["href" => "index.php?module=depotprof&idProjet=$idProjet", "title" => "Dépôt"],
                    ["href" => "index.php?module=ressourceprof&idProjet=$idProjet", "title" => "Ressource"],
                    ["href" => "index.php?module=soutenanceprof&idProjet=$idProjet", "title" => "Soutenance"],
                    ["href" => "index.php?module=evaluationprof&idProjet=$idProjet", "title" => "Évaluation"]
                ],
                "Co-Responsable" => [
                    ["href" => "index.php?module=groupeprof&action=gestionGroupeSAE&idProjet=$idProjet", "title" => "Groupe"],
                    ["href" => "index.php?module=gerantprof&idProjet=$idProjet", "title" => "Gérant"],
                    ["href" => "index.php?module=depotprof&idProjet=$idProjet", "title" => "Dépôt"],
                    ["href" => "index.php?module=ressourceprof&idProjet=$idProjet", "title" => "Ressource"],
                    ["href" => "index.php?module=soutenanceprof&idProjet=$idProjet", "title" => "Soutenance"],
                    ["href" => "index.php?module=evaluationprof&idProjet=$idProjet", "title" => "Évaluation"]
                ],
                "Intervenant" => [
                    ["href" => "index.php?module=depotprof&idProjet=$idProjet", "title" => "Dépôt"],
                    ["href" => "index.php?module=soutenanceprof&idProjet=$idProjet", "title" => "Soutenance"],
                    ["href" => "index.php?module=ressourceprof&idProjet=$idProjet", "title" => "Ressource"],
                    ["href" => "index.php?module=evaluationprof&idProjet=$idProjet", "title" => "Évaluation"]
                ]
            ];

            $titre = ModeleCommun::getTitreSAE($idProjet);
            $desc = ModeleCommun::getDescriptionSAE($idProjet);
            $idUtilisateur = $_SESSION['id_utilisateur'];
            $role = ModeleCommun::getRoleSAE($idProjet, $idUtilisateur);
            $availableSections = $sections[$role];
            $this->vue->afficherSaeDetails($titre, $desc, $availableSections);
        } else {
            $this->accueil();
        }
    }


}