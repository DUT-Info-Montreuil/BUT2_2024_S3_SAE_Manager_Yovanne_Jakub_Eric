<?php
include_once 'Connexion.php';
class ModeleConnexion extends Connexion {
    public function __construct() {
    }

    public function verifierUtilisateur($login, $mdp) {
        $bdd = $this->getBdd();
        $stmt = $bdd->prepare("SELECT * FROM Utilisateur WHERE login_utilisateur = ?");
        $stmt->execute([$login]);
        $user = $stmt->fetch();
        // A REMETTRE A LA FIN (JUSTE PR LES TEST)
//        if ($user && password_verify($mdp, $user['password_utilisateur'])) {
//            return $user;
//        }
        if($user && $user['password_utilisateur']==$mdp){
            return $user;
        }
        return false;
    }

    public function utilisateurExiste($login){
        $bdd = $this->getBdd();
        $stmt = $bdd->prepare("SELECT * FROM Utilisateur WHERE login_utilisateur = ?");
        $stmt->execute([$login]);
        $user = $stmt->fetch();
        if($user){
            return true;
        }
        return false;
    }

    public function typeUtilisateur($identifiant){
        $bdd = $this->getBdd();
        $stmt = $bdd->prepare("SELECT type_utilisateur FROM Utilisateur WHERE login_utilisateur = ?");
        $stmt->execute([$identifiant]);
        $type = $stmt->fetch();
        return $type[0];

    }


    public function ajouterUtilisateur ($nom, $prenom, $email, $login, $password_hash) {
        if(!$this->utilisateurExiste($login)){
            $bdd = $this->getBdd();
            $stmt = $bdd->prepare("INSERT INTO Utilisateur (id_utilisateur, nom, prenom, email, type_utilisateur, login_utilisateur, password_utilisateur)
                                    VALUES (DEFAULT, ?, ?, ?, 'etudiant', ?, ?)");
            $stmt->execute([$nom,$prenom,$email,$login,$password_hash]);
        }
    }

}