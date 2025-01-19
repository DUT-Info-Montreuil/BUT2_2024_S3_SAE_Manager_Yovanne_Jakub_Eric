<?php

include_once 'modules/professeur/mod_dashboard/modele_dashboard.php';
include_once 'modules/professeur/mod_dashboard/vue_dashboard.php';
require_once "ControllerCommun.php";

class ContDashboard
{
    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        $this->modele = new ModeleDashboard();
        $this->vue = new VueDashboard();
    }

    public function exec()
    {
        $this->action = isset($_GET['action']) ? $_GET['action'] : "viewDashboard";

        if (ControllerCommun::estProf()) {
            switch ($this->action) {
                case "viewDashboard":
                    $this->viewDashboard();
                    break;
            }
        } else {
            echo "Accès interdit. Vous devez être professeur pour accéder à cette page.";
        }
    }

    public function viewDashboard()
    {
        $idProjet = $_GET['idProjet'];
        $projectData = $this->modele->getProjectData($idProjet);
        $groups = $this->modele->getGroupsForProject($idProjet);

        foreach ($groups as &$group) {
            $group['membres'] = $this->modele->getMembersForGroup($group['id_groupe']);
            $group['notes'] = $this->modele->getNotesForGroup($group['id_groupe']);
        }

        $evaluations = $this->modele->getEvaluationsForProject($idProjet);
        $resources = $this->modele->getResourcesForProject($idProjet);
        $soutenance = $this->modele->getSoutenanceProjet($idProjet);
        $rendu = $this->modele->getRenduProjet($idProjet);

        $this->vue->renderDashboard($projectData, $groups, $evaluations, $resources, $soutenance, $rendu);
    }

}


