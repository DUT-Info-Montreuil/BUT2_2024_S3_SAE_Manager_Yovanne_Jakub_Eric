<?php

include_once 'Connexion.php';
Class ModeleCompMenu extends Connexion
{
    public function __construct()
    {
    }

    public function getProfilPictureById($idUtilisateur){
        $bdd = $this->getBdd();
        $query = $bdd->prepare("SELECT profil_picture FROM Utilisateur WHERE id_utilisateur = ?");
        $query->execute([$idUtilisateur]);

        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result && isset($result['profil_picture'])) {
            $profilPictureName = basename($result['profil_picture']);
            return $profilPictureName;
        }

        return null;
    }

    public function getLoginById($idUtilisateur){
        $bdd = $this->getBdd();
        $query = "SELECT login_utilisateur FROM Utilisateur WHERE id_utilisateur = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idUtilisateur]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result && isset($result['login_utilisateur'])) {
            $loginName = basename($result['login_utilisateur']);
        }
        return $loginName;
    }

}