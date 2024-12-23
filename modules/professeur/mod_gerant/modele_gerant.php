<?php
include_once 'Connexion.php';

Class ModeleGerant extends Connexion {
    public function __construct()
    {
    }

    public function getGerantSAE($idSae){
        $bdd = $this->getBdd();
        $query = "SELECT 
                  CONCAT(Utilisateur.prenom, ' ', Utilisateur.nom) AS nom_complet, 
                  Gerant.role_utilisateur, 
                  Gerant.id_utilisateur 
              FROM Utilisateur 
              INNER JOIN Gerant ON Utilisateur.id_utilisateur = Gerant.id_utilisateur
              WHERE Gerant.id_projet = ?";
        $requete = $bdd->prepare($query);
        $requete->execute([$idSae]);
        $gerants = $requete->fetchAll(PDO::FETCH_ASSOC);
        return $gerants;
    }






}