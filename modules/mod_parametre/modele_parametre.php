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


}