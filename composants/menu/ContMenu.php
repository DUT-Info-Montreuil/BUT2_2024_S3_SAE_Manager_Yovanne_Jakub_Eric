<?php

require_once "composants/menu/VueMenu.php";


class ControleurCompMenu {

    public function __construct() {
        $this->vue = new VueCompMenu();
    }


    public function exec () {
        $this->vue->VueMenu();
    }


}
