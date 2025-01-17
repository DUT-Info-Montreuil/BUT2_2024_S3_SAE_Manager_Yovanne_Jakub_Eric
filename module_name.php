<?php

class ModuleName
{

    private $module_name;
    private $module;

    public function __construct()
    {
        $this->module_name = isset($_GET['module']) ? $_GET['module'] : "connexion";
        $this->tokenConnexionValide();

        if (in_array($this->module_name, ['groupeprof', 'gerantprof', 'depotprof', 'ressourceprof', 'soutenanceprof', "accueilprof", "evaluationprof", "infosae", "notefinalprof"])) {
            $module_path = "modules/professeur/mod_{$this->module_name}/mod_{$this->module_name}.php";
        } else if (in_array($this->module_name, ['accueiletud', 'groupeetud', 'soutenanceetud', 'ressourceetud', 'depotetud', 'notesetud'])) {
            $module_path = "modules/etudiant/mod_{$this->module_name}/mod_{$this->module_name}.php";
        } else if (in_array($this->module_name, ['accueiladmin', 'gestuser'])) {
            $module_path = "modules/admin/mod_{$this->module_name}/mod_{$this->module_name}.php";
        } else {
            $module_path = "modules/mod_{$this->module_name}/mod_{$this->module_name}.php";
        }

        if (file_exists($module_path)) {
            require_once $module_path;
        } else {
            echo $module_path;
            die("Module '{$this->module_name}' introuvable.");
        }
    }
    public function tokenConnexionValide()
    {
        if (isset($_SESSION['id_utilisateur'], $_SESSION['timestamp'], $_SESSION['token_connexion'])) {

            // temps de session est toujours valide
            if (time() - $_SESSION['timestamp'] > 1800) { // 1800 secondes = 30 minutes
                $this->deconnexion();
                $this->redirigeVersConnexion();
            }

            // token de connexion est valide
            if ($_SESSION['token_connexion'] !== $_COOKIE['token_connexion'] ?? '') {
                $this->deconnexion();
                $this->redirigeVersConnexion();
            }

            //actualiser le time
            $_SESSION['timestamp'] = time();
            return true;
        }

        return false;
    }

    private function redirigeVersConnexion(){
        header("Location: index.php?action=connexion");
    }

    public function deconnexion()
    {
        session_destroy();
    }

    public function exec_module()
    {
        $module_class = "Mod" . ucfirst($this->module_name);
        $module_class = str_replace('prof', 'Prof', $module_class);
        $module_class = str_replace('etud', 'Etud', $module_class);
        $module_class = str_replace('intervenant', 'Intervenant', $module_class);
        $module_class = str_replace('infosae', 'InfoSae', $module_class);

        if (class_exists($module_class)) {
            $this->module = new $module_class();
            $this->module->exec();
        } else {
            die("Classe '{$module_class}' introuvable.");
        }
    }

    public function get_module()
    {
        return $this->module;
    }
}
