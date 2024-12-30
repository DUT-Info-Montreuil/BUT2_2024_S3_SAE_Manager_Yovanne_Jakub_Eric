<?php
include_once 'prof_intervenant/depot/vue_depotbase.php';

class VueDepotProf extends VueDepotBase
{
    public function __construct()
    {
        parent::__construct();
    }

    public function afficheAllDepotSAE($depot){
        $this->afficherDepotBase($depot, "index.php?module=depotprof&action=modifierDepot", "index.php?module=depotprof&action=supprimerDepot", "index.php?module=depotprof&action=creerDepot");
    }

    public function formulaireCreerDepot($depot){
        $this->formulaireCreerDepotBase("index.php?module=depotprof&action=submitDepot", "index.php?module=depotprof&action=gestionDepotSAE");
    }
}

