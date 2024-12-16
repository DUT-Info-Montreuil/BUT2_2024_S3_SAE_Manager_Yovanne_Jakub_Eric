<?php

require_once 'generique/composant/vue_composant_generique.php';

class VueCompMenu extends VueCompGenerique {
    public function __construct() {

        if (isset($_SESSION['id_utilisateur'])) {
            $this->affichage = ' <a href="index.php?module=connexion&action=deconnexion">Déconnexion</a>';
        }
    }
}