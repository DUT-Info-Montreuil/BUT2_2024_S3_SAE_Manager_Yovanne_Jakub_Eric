<?php

include_once 'Connexion.php';
Class ModeleParametre extends Connexion
{
    public function __construct()
    {
    }

    public function afficherCompte($id_utilisateur){
        $bdd = $this->getBdd();
        $query = "
        SELECT nom, prenom, email, login_utilisateur, password_utilisateur 
        FROM utilisateur 
        WHERE id_utilisateur = ?";

        $stmt = $bdd->prepare($query);
        $stmt->execute([$id_utilisateur]);
        $compte = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $compte;
    }

    public function modifierCompte($id_utilisateur, $nom, $prenom, $email, $login_utilisateur, $password_utilisateur = null){
        $bdd = $this->getBdd();

        if ($password_utilisateur) {
            $query = "
            UPDATE utilisateur 
            SET nom = ?, prenom = ?, email = ?, login_utilisateur = ?, password_utilisateur = ?
            WHERE id_utilisateur = ?";
            $stmt = $bdd->prepare($query);
            $stmt->execute([$nom, $prenom, $email, $login_utilisateur, $password_utilisateur, $id_utilisateur]);
        } else {
            $query = "
            UPDATE utilisateur 
            SET nom = ?, prenom = ?, email = ?, login_utilisateur = ?
            WHERE id_utilisateur = ?";
            $stmt = $bdd->prepare($query);
            $stmt->execute([$nom, $prenom, $email, $login_utilisateur, $id_utilisateur]);
        }
    }

    public function modifierPhotoDeProfil($id_utilisateur, $logo){

        $bdd = $this->getBdd();
        $query = "
        UPDATE utilisateur
        SET logo = ?
        WHERE id_utilisateur = ?";

        $stmt = $bdd->prepare($query);
        $success =$stmt->execute([$logo, $id_utilisateur]);

        if (!$success) {
            // Si la requête échoue, affiche une erreur
            echo 'Erreur lors de la mise à jour de la base de données';
        }
    }
}