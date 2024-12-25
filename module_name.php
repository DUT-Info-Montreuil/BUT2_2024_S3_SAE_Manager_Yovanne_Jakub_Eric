<?php

class ModuleName {

    private $module_name;
    private $module;

    public function __construct() {
        $this->module_name = isset($_GET['module']) ? $_GET['module'] : "connexion";

        if (in_array($this->module_name, ['groupeprof', 'gerantprof', 'depotprof', 'ressourceprof', 'soutenanceprof', "accueilprof"])) {
            if (substr($this->module_name, -4) === 'prof') {
                $this->module_name = substr($this->module_name, 0, -4);
            }
            $module_path = "modules/professeur/mod_{$this->module_name}/mod_{$this->module_name}.php";
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


    public function exec_module() {
        $module_class = "Mod" . ucfirst($this->module_name);
        if (class_exists($module_class)) {
            $this->module = new $module_class();
            $this->module->exec();
        } else {
            die("Classe '{$module_class}' introuvable.");
        }
    }

    public function get_module() {
        return $this->module;
    }
}


