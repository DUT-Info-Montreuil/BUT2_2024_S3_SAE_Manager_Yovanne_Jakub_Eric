<?php

include_once 'Connexion.php';
Class ModeleParametre extends Connexion
{
    public function __construct()
    {
    }

    public function getCompteById($idUtilisateur){
        $bdd = $this->getBdd();
        $query = "
        SELECT *
        FROM Utilisateur 
        WHERE id_utilisateur = ?";

        $stmt = $bdd->prepare($query);
        $stmt->execute([$idUtilisateur]);
        $compte = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $compte;
    }

    public function modifierCompte($idUtilisateur, $nom, $prenom, $email, $login_utilisateur, $password_utilisateur){
        $bdd = $this->getBdd();

        if ($password_utilisateur) {
            $query = "
            UPDATE Utilisateur 
            SET nom = ?, prenom = ?, email = ?, login_utilisateur = ?, password_utilisateur = ?
            WHERE id_utilisateur = ?";
            $stmt = $bdd->prepare($query);
            $stmt->execute([$nom, $prenom, $email, $login_utilisateur, $password_utilisateur, $idUtilisateur]);

        } else {
            $query = "
            UPDATE Utilisateur 
            SET nom = ?, prenom = ?, email = ?, login_utilisateur = ?
            WHERE id_utilisateur = ?";
            $stmt = $bdd->prepare($query);
            $stmt->execute([$nom, $prenom, $email, $login_utilisateur, $idUtilisateur]);

        }
    }

    public function modifierPhotoDeProfil($id_utilisateur, $logo){

        $bdd = $this->getBdd();

        $query = "
        UPDATE Utilisateur
        SET profil_picture = ?
        WHERE id_utilisateur = ?";

        $stmt = $bdd->prepare($query);
        $success =$stmt->execute([$logo, $id_utilisateur]);
    }

    public function modifierCheminProfilPicture($idUtilisateur, $uploadPath)
    {
        $bdd =$this->getBdd();
        $sql = "UPDATE Utilisateur SET profil_picture = ? WHERE id_utilisateur = ?";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([$uploadPath, $idUtilisateur]);
    }

    public function getProfilPictureById($idUtilisateur){
        $bdd = $this->getBdd();
        $query = "SELECT profil_picture FROM Utilisateur WHERE id_utilisateur = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idUtilisateur]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && isset($result['profil_picture'])) {
            $profilPictureName = basename($result['profil_picture']);
            return $profilPictureName;
        }

        return null;
    }


}