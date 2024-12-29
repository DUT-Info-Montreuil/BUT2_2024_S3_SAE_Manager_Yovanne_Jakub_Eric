<?php

include_once 'Connexion.php';
Class ModeleRessourceEtud extends Connexion
{
    public function __construct()
    {
    }

    public function getAllRessourceAccesible($idProjet){
        $bdd = $this->getBdd();

        $query = "SELECT * FROM Ressource WHERE id_projet = ? AND mise_en_avant = 1";

        $stmt = $bdd->prepare($query);
        $stmt->execute([$idProjet]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}