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
        SELECT r.id_rendu, r.titre, r.date_limite, rg.statut, rg.contenu_rendu
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

    public function getTitreSAE($idProjet){
        $bdd = $this->getBdd();
        $query = "SELECT titre FROM Projet WHERE id_projet = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idProjet]);
        $sae = $stmt->fetch(PDO::FETCH_ASSOC);
        return $sae['titre'];
    }

    public function rendreDepot($idRendu, $fichier, $idGroupe) {
        $bdd = $this->getBdd();
        $query = "UPDATE Rendu_Groupe SET contenu_rendu = ?, statut = 'Remis' WHERE id_rendu = ? AND id_groupe = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$fichier, $idRendu, $idGroupe]);
    }

    public function getCheminFichierRemis($idRendu, $idGroupe){
        $bdd =$this->getBdd();
        $query = "SELECT contenu_rendu FROM Rendu_Groupe WHERE id_rendu = ? AND id_groupe = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idRendu, $idGroupe]);
        $cheminFichier = $stmt->fetch(PDO::FETCH_ASSOC);
        return $cheminFichier['contenu_rendu'];
    }

    public function getNomGroupe($idGroupe){
        $bdd = $this->getBdd();
        $query = "SELECT nom FROM Groupe WHERE id_groupe = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idGroupe]);
        $groupe = $stmt->fetch(PDO::FETCH_ASSOC);
        return $groupe['nom'];
    }

    public function getNomRendu($idRendu){
        $bdd = $this->getBdd();
        $query = "SELECT titre FROM rendu WHERE id_rendu = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idRendu]);
        $rendu = $stmt->fetch(PDO::FETCH_ASSOC);
        return $rendu['titre'];
    }

    public function supprimerTravailRemis($idRendu, $idgroupe){
        $bdd = $this->getBdd();
        $query = "UPDATE Rendu_Groupe SET statut = 'En attente', contenu_rendu = NULL WHERE id_rendu = ? and id_groupe = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idRendu, $idgroupe]);
    }

}