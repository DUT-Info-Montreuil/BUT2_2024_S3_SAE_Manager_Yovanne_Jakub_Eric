<?php

include_once 'modules/mod_connexion/cont_connexion.php';
include_once 'modules/mod_connexion/vue_connexion.php';
include_once 'Connexion.php';
class ModeleConnexion extends Connexion {
    public function __construct() {
    }

    public function verifierUtilisateur($identifiant, $mdp) {
        $bdd = $this->getBdd();
        $stmt = $bdd->prepare("SELECT * FROM utilisateur WHERE login = ?");
        $stmt->execute([$identifiant]);
        $user = $stmt->fetch();
        if ($user && password_verify($mdp, $user['mdp'])) {
            return $user;
        }
        return false;
    }

    public function utilisateurExiste($login){
        $bdd = $this->getBdd();
        $stmt = $bdd->prepare("SELECT * FROM utilisateur WHERE login = ?");
        $stmt->execute([$login]);
        $user = $stmt->fetch();
        if($user){
            return true;
        }
        return false;
    }

    public function ajouterUtilisateur ($nom, $prenom, $email, $login, $password_hash) {
        if(!$this->utilisateurExiste($login)){
            $bdd = $this->getBdd();
            $stmt = $bdd->prepare("INSERT INTO utilisateur (id_utilisateur, nom, prenom, email, type_utilisateur, login, mdp) 
                                    VALUES (DEFAULT, ?, ?, ?, 'etudiant', ?, ?)");
            $stmt->execute([$nom,$prenom,$email,$login,$password_hash]);
        }
    }

}