<?php

include_once "prof_intervenant/depot/cont_depotbase.php";
include_once "modules/professeur/mod_depotprof/modele_depotprof.php";
include_once "modules/professeur/mod_depotprof/vue_depotprof.php";

class ContDepotProf extends ContDepotBase {
    public function __construct() {
        parent::__construct(new ModeleDepotProf(), new VueDepotProf());
    }

    protected function estAutorise() {
        return $_SESSION['type_utilisateur'] === "professeur";
    }
}
