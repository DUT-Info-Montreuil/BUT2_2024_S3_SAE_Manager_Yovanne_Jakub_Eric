<?php

include_once "prof_intervenant/ressource/cont_ressourcebase.php";
include_once "modules/professeur/mod_ressourceprof/modele_ressourceprof.php";
include_once "modules/professeur/mod_ressourceprof/vue_ressourceprof.php";

class ContRessourceProf extends ContRessourceBase {
    public function __construct() {
        parent::__construct(new ModeleRessourceProf(), new VueRessourceProf());
    }

    protected function estAutorise() {
        return $_SESSION['type_utilisateur'] === "professeur";
    }
}