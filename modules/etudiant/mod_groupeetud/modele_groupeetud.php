<?php

include_once 'Connexion.php';
Class ModeleGroupeEtud extends Connexion
{
    public function __construct()
    {
    }

    public function getGroupeSAE($idGroupe) {
        $bdd = $this->getBdd();
        $query = "
        SELECT u.id_utilisateur, u.nom, u.prenom, u.email
        FROM Utilisateur u
        INNER JOIN Groupe_Etudiant ge ON u.id_utilisateur = ge.id_utilisateur
        WHERE ge.id_groupe = ?
    ";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idGroupe]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNomGroupe($idGroupe){
        $bdd = $this->getBdd();
        $query = "SELECT nom FROM Groupe WHERE id_groupe = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idGroupe]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['nom'];
    }


}