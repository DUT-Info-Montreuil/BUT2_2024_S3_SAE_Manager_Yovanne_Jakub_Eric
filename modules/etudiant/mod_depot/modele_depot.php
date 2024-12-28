<?php

include_once 'Connexion.php';
Class ModeleDepot extends Connexion
{
    public function __construct()
    {
    }

    public function afficherAllDepot($id_groupe, $id_projet) {
        $bdd = $this->getBdd();

        $query = "
        SELECT r.id_rendu, r.titre, r.date_limite, rg.statut
        FROM Rendu r
        INNER JOIN Rendu_Groupe rg ON r.id_rendu = rg.id_rendu
        INNER JOIN Projet_Groupe pg ON rg.id_groupe = pg.id_groupe
        WHERE pg.id_projet = ? AND rg.id_groupe = ?
    ";

        $stmt = $bdd->prepare($query);
        $stmt->execute([$id_projet, $id_groupe]);

        $depotDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$depotDetails) {
            return "Aucun dépôt trouvé pour ce groupe et projet.";
        }

        return $depotDetails;
    }


}