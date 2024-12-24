<?php
include_once 'Connexion.php';

Class ModeleDepot extends Connexion {
    public function __construct()
    {
    }

    public function getAllDepotSAE($idSae) {
        $bdd = $this->getBdd();
        $sql = "
        SELECT r.id_rendu, r.titre, r.date_limite
        FROM Rendu r
        WHERE r.id_projet = ?
        ORDER BY r.date_limite ASC
    ";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([$idSae]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}