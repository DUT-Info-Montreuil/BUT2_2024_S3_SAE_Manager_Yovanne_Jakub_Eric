<?php

class ModuleName {

    private $module_name;
    private $module;
    public function __construct() {
        $this->module_name = isset($_GET['module']) ? $_GET['module'] : "connexion";

        switch ($this->module_name) {
            case "connexion" :
                require_once "modules/mod_".$this->module_name."/mod_".$this->module_name.".php";
                break;
        }
    }

    public function exec_module() {
        $module_class = "Mod".$this->module_name;
        $this->module = new $module_class();
        $this->module->exec();
    }

    public function get_module() {
        return $this->module;
    }

}
