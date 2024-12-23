<?php

class ModuleName {

    private $module_name;
    private $module;

    public function __construct() {
        $this->module_name = isset($_GET['module']) ? $_GET['module'] : "connexion";

        if (strpos($this->module_name, 'professeur') !== false || in_array($this->module_name, ['groupe', 'gerant', 'depot', 'ressource', 'soutenance'])) {
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
        // Nom de la classe basé sur le nom du module
        $module_class = "Mod" . ucfirst($this->module_name);

        // Vérifier l'existence de la classe avant de l'instancier
        if (class_exists($module_class)) {
            $this->module = new $module_class();
            $this->module->exec();  // Exécution du module
        } else {
            die("Classe '{$module_class}' introuvable.");
        }
    }

    public function get_module() {
        return $this->module;
    }
}


