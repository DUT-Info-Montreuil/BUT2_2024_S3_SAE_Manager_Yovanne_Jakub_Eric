<?php
include_once 'Connexion.php';
Class ModeleProfesseur extends Connexion{
    public function __construct() {
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
    public function saeGerer($id_utilisateur) {
        $bdd = $this->getBdd();
        $stmt = $bdd->prepare("SELECT titre FROM Projet INNER JOIN Gerant ON Projet.id_projet = Gerant.id_projet WHERE id_utilisateur = ?");
        $stmt->execute([$id_utilisateur]);
        $titres = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $titres;
    }
}