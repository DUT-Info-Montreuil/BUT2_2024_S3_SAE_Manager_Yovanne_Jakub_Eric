<?php

include_once 'modules/mod_connexion/cont_connexion.php';
include_once 'modules/mod_connexion/vue_connexion.php';
include_once 'Connexion.php';
class ModeleConnexion extends Connexion {
    public function __construct() {
    }

    public function verifierUtilisateur($identifiant, $mdp) {
        $bdd = $this->getBdd();
        $stmt = $bdd->prepare("SELECT * FROM utilisateur WHERE id_utilisateur = ?");
        $stmt->execute([$identifiant]);
        $user = $stmt->fetch();
        if ($user) {
            return $user;
        }
        return false;
    }

}