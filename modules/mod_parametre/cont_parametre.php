<?php


class ContParametre {

    private $modele;
    private $vue;
    private $action;

    public function __construct() {
        $this->modele = new ModeleParametre();
        $this->vue = new VueParametre();
    }


    public function exec() {
        $this->action = isset($_GET['action']) ? $_GET['action'] : "parametre utilisateur";
        switch ($this->action) {

        }

    }

    public function afficherParametreUtilisateur() {

    }

}