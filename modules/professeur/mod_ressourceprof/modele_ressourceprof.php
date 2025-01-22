<?php
include_once 'Connexion.php';

Class ModeleRessourceProf extends Connexion
{
    public function __construct()
    {
    }

    public function getAllRessourceSAE($idSae){
        $bdd = $this->getBdd();
        $sql = "SELECT * FROM Ressource WHERE id_projet = ?";
        $req = $bdd->prepare($sql);
        $req->execute([$idSae]);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function creerRessource($titre, $mise_en_avant, $id_projet, $fichier){
        $bdd = $this->getBdd();
        $query = "INSERT INTO Ressource (id_ressource, titre, lien, mise_en_avant, id_projet) 
                          VALUES (DEFAULT, ?, ?, ?, ?)";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$titre, $fichier, $mise_en_avant, $id_projet]);
    }

    public function supprimerRessource($idRessource){
        $bdd = $this->getBdd();
        $sql = "DELETE FROM Ressource WHERE id_ressource = ?";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([$idRessource]);
    }

    public function getRessourceLien($idRessource){
        $bdd = $this->getBdd();
        $sql = "SELECT lien FROM Ressource WHERE id_ressource = ?";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([$idRessource]);
        $ressource = $stmt->fetch(PDO::FETCH_ASSOC);
        return $ressource['lien'];
    }

    public function mettreAJoursRessource($nouveauChemin, $mise_en_avant, $titre, $idRessource){
        $bdd = $this->getBdd();
        $sql = "UPDATE Ressource SET lien = ?, mise_en_avant = ?, titre = ? WHERE id_ressource = ?";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([$nouveauChemin, $mise_en_avant, $titre, $idRessource]);
    }

    public function mettreAJoursRessourceSansFichier($titre, $mise_en_avant, $idRessource){
        $bdd = $this->getBdd();
        $query = "UPDATE Ressource SET titre = ?, mise_en_avant = ? WHERE id_ressource = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$titre, $mise_en_avant, $idRessource]);
    }

}