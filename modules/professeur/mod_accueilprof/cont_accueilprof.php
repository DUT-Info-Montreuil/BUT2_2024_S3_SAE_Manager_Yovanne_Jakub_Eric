<?php
include_once "modules/professeur/mod_accueilprof/modele_accueilprof.php";
include_once "modules/professeur/mod_accueilprof/vue_accueilprof.php";
require_once "DossierManager.php";
require_once "ModeleCommun.php";

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
        if (!$this->estProfOuIntervenant()) {
            echo "Accès interdit. Vous devez être professeur ou intervenant pour accéder à cette page.";
            return;
        }
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


    public function estProfOuIntervenant(){
        $typeUser =  ModeleCommun::getTypeUtilisateur($_SESSION['id_utilisateur']);
        return $typeUser==="professeur" || $typeUser==="intervenant";
    }

    public function choixSae()
    {
        if (isset($_GET['id'])) {
            $sections = [
                "Responsable" => [
                    ["href" => "index.php?module=infosae", "title" => "Gestion de la SAE"],
                    ["href" => "index.php?module=groupeprof&action=gestionGroupeSAE", "title" => "Groupe"],
                    ["href" => "index.php?module=gerantprof", "title" => "Gérant"],
                    ["href" => "index.php?module=depotprof", "title" => "Dépôt"],
                    ["href" => "index.php?module=ressourceprof", "title" => "Ressource"],
                    ["href" => "index.php?module=soutenanceprof", "title" => "Soutenance"],
                    ["href" => "index.php?module=evaluationprof", "title" => "Évaluation"]
                ],
                "Co-Responsable" => [
                    ["href" => "index.php?module=groupeprof&action=gestionGroupeSAE", "title" => "Groupe"],
                    ["href" => "index.php?module=gerantprof", "title" => "Gérant"],
                    ["href" => "index.php?module=depotprof", "title" => "Dépôt"],
                    ["href" => "index.php?module=ressourceprof", "title" => "Ressource"],
                    ["href" => "index.php?module=soutenanceprof", "title" => "Soutenance"],
                    ["href" => "index.php?module=evaluationprof", "title" => "Évaluation"]
                ],
                "Intervenant" => [
                    ["href" => "index.php?module=depotprof", "title" => "Dépôt"],
                    ["href" => "index.php?module=soutenanceprof", "title" => "Soutenance"],
                    ["href" => "index.php?module=ressourceprof", "title" => "Ressource"],
                    ["href" => "index.php?module=evaluationprof", "title" => "Évaluation"]
                ]
            ];
            $idProjet = $_GET['id'];
            $_SESSION['id_projet'] = $idProjet;
            $titre = ModeleCommun::getTitreSAE($idProjet);
            $desc = ModeleCommun::getDescriptionSAE($idProjet);
            $idUtilisateur = $_SESSION['id_utilisateur'];
            $role = ModeleCommun::getRoleSAE($idProjet, $idUtilisateur);
            $this->vue->afficherSaeDetails($titre, $role, $desc, $sections);
        } else {
            $this->accueil();
        }
    }

}