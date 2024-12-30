<?php
include_once 'prof_intervenant/ressource/vue_ressourcebase.php';

class VueRessourceProf extends VueRessourceBase
{
    public function __construct()
    {
        parent::__construct();
    }
    public function afficherAllRessource($allRessources)
    {
        $this->afficherAllRessourceBase($allRessources, "index.php?module=ressourceprof&action=modifierRessource",
            "index.php?module=ressourceprof&action=supprimerRessource", "index.php?module=ressourceprof&action=creerRessource");
    }
    public function formulaireCreerRessource()
    {
        $this->formulaireCreerRessourceBase("index.php?module=ressourceprof&action=submitRessource", "index.php?module=ressourceprof&action=gestionRessourceSAE");
    }
}