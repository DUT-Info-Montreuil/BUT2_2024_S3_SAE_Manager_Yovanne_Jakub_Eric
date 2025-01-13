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

        // Si un mot de passe a été fourni, on met à jour aussi le mot de passe
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

}