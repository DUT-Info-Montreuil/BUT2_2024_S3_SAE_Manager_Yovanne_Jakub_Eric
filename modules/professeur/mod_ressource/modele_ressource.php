<?php
include_once 'Connexion.php';

Class ModeleRessource extends Connexion
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

    public function creerRessource($titre, $mise_en_avant, $id_projet, $targetFile){
        $bdd = $this->getBdd();
        $query = "INSERT INTO Ressource (id_ressource, titre, lien, mise_en_avant, id_projet) 
                          VALUES (DEFAULT, ?, ?, ?, ?)";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$titre, $targetFile, $mise_en_avant, $id_projet]);
    }

    public function supprimerRessource($idRessource){
        $bdd = $this->getBdd();
        $sql = "DELETE FROM Ressource WHERE id_ressource = ?";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([$idRessource]);
    }
}