<?php
include_once 'Connexion.php';
class ModeleConnexion extends Connexion {
    public function __construct() {
    }

    public function verifierUtilisateur($identifiant, $mdp) {
        $bdd = $this->getBdd();
        $stmt = $bdd->prepare("SELECT * FROM Utilisateur WHERE login = ?");
        $stmt->execute([$identifiant]);
        $user = $stmt->fetch();
        if ($user && password_verify($mdp, $user['mdp'])) {
            return $user;
        }
        return false;
    }

    public function utilisateurExiste($login){
        $bdd = $this->getBdd();
        $stmt = $bdd->prepare("SELECT * FROM Utilisateur WHERE login = ?");
        $stmt->execute([$login]);
        $user = $stmt->fetch();
        if($user){
            return true;
        }
        return false;
    }

    public function typeUtilisateur($identifiant){
        $bdd = $this->getBdd();
        $stmt = $bdd->prepare("SELECT type_utilisateur FROM Utilisateur WHERE login = ?");
        $stmt->execute([$identifiant]);
        $type = $stmt->fetch();
        return $type[0];

    }


    public function ajouterUtilisateur ($nom, $prenom, $email, $login, $password_hash) {
        if(!$this->utilisateurExiste($login)){
            $bdd = $this->getBdd();
            $stmt = $bdd->prepare("INSERT INTO Utilisateur (id_utilisateur, nom, prenom, email, type_utilisateur, login, mdp)
                                    VALUES (DEFAULT, ?, ?, ?, 'etudiant', ?, ?)");
            $stmt->execute([$nom,$prenom,$email,$login,$password_hash]);
        }
    }

}