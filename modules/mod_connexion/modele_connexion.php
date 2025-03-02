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
        if ($user && password_verify($mdp, $user['password_utilisateur'])) {
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
    public function ajouterUtilisateur ($nom, $prenom, $email, $login, $password_hash) {
        if(!$this->utilisateurExiste($login)){
            $defaultPathAvatar = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'profil_picture' . DIRECTORY_SEPARATOR . 'default_avatar.png';
            $bdd = $this->getBdd();
            $stmt = $bdd->prepare("INSERT INTO Utilisateur (id_utilisateur, nom, prenom, email, type_utilisateur, login_utilisateur, password_utilisateur, profil_picture)
                       VALUES (DEFAULT, ?, ?, ?, 'etudiant', ?, ?, ?)");
            $stmt->execute([$nom, $prenom, $email, $login, $password_hash, $defaultPathAvatar]);

        }
    }

}