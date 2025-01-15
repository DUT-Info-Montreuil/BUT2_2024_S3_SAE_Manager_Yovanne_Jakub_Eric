<?php

include_once 'Connexion.php';
Class ModeleMenu extends Connexion
{
    public function __construct()
    {
    }

    public function afficherLogo($id_utilisateur){
        $bdd = $this->getBdd();

        $query = "
        SELECT logo
        FROM utilisateur
        WHERE id_utilisateur = ?";

        $stmt = $bdd->prepare($query);
        $stmt->execute([$id_utilisateur]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    }

}