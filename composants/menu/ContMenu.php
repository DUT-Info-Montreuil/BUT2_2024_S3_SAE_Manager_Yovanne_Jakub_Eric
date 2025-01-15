<?php

require_once "composants/menu/VueMenu.php";

class ControleurCompMenu {
    private $vue;
    public function __construct() {
        $this->vue = new VueCompMenu();
    }
    public function getVue() {
        return $this->vue;
    }


}