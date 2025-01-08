<?php

include_once 'Connexion.php';

class ModeleDepotEtud extends Connexion
{
    public function __construct()
    {
    }


    public function getAllDepot($id_groupe, $id_projet)
    {
        $depotDetails = $this->getDepotDetails($id_groupe, $id_projet);
        return $this->transformDepotFiles($depotDetails);
    }

    public function getDepotDetails($id_groupe, $id_projet)
    {
        $bdd = $this->getBdd();

        $query = "
        SELECT 
            r.id_rendu, 
            r.titre, 
            r.date_limite, 
            rg.statut,
            GROUP_CONCAT(rf.id_fichier ORDER BY rf.date_remise) AS fichiers_ids,
            GROUP_CONCAT(rf.nom_fichier ORDER BY rf.date_remise) AS fichiers_noms,
            GROUP_CONCAT(rf.chemin_fichier ORDER BY rf.date_remise) AS fichiers_chemins
        FROM Rendu r
        INNER JOIN Rendu_Groupe rg ON r.id_rendu = rg.id_rendu
        INNER JOIN Projet_Groupe pg ON rg.id_groupe = pg.id_groupe
        LEFT JOIN Rendu_Fichier rf ON r.id_rendu = rf.id_rendu
        WHERE pg.id_projet = ? AND rg.id_groupe = ?
        GROUP BY r.id_rendu, r.titre, r.date_limite, rg.statut
    ";

        $stmt = $bdd->prepare($query);
        $stmt->execute([$id_projet, $id_groupe]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function transformDepotFiles($depotDetails)
    {
        if (!$depotDetails) {
            return null;
        }

        foreach ($depotDetails as &$depot) {  // & = élément de la liste et non une copie de la liste pr pvr la modifier
            $fichiers_ids = explode(',', $depot['fichiers_ids']);
            $fichiers_noms = explode(',', $depot['fichiers_noms']);
            $fichiers_chemins = explode(',', $depot['fichiers_chemins']);

            $fichiers = [];
            for ($i = 0; $i < count($fichiers_ids); $i++) {
                $fichiers[] = [
                    'id_fichier' => $fichiers_ids[$i],
                    'nom_fichier' => $fichiers_noms[$i],
                    'chemin_fichier' => $fichiers_chemins[$i],
                ];
            }

            $depot['fichiers'] = $fichiers;
        }

        return $depotDetails;
    }


    public function enregistrerFichierRendu($idRendu, $idGroupe, $nomFichier, $cheminFichier)
    {
        $bdd = $this->getBdd();
        $query = "INSERT INTO Rendu_Fichier (id_rendu, id_groupe, nom_fichier, chemin_fichier) VALUES (?, ?, ?, ?)";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idRendu, $idGroupe, $nomFichier, $cheminFichier]);
    }

    public function setRenduStatut($idRendu, $idGroupe, $statut)
    {
        if ($statut === 'Remis' || $statut === 'En retard' || $statut === 'En attente') {
            $bdd = $this->getBdd();
            $query = "UPDATE Rendu_Groupe SET statut = ? WHERE id_rendu = ? AND id_groupe = ?";
            $stmt = $bdd->prepare($query);
            $stmt->execute([$statut, $idRendu, $idGroupe]);
        }
    }


    public function getCheminFichierRemis($idRendu, $idGroupe)
    {
        $bdd = $this->getBdd();
        $query = "SELECT contenu_rendu FROM Rendu_Groupe WHERE id_rendu = ? AND id_groupe = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idRendu, $idGroupe]);
        $cheminFichier = $stmt->fetch(PDO::FETCH_ASSOC);
        return $cheminFichier['contenu_rendu'];
    }

    public function getNomGroupe($idGroupe)
    {
        $bdd = $this->getBdd();
        $query = "SELECT nom FROM Groupe WHERE id_groupe = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idGroupe]);
        $groupe = $stmt->fetch(PDO::FETCH_ASSOC);
        return $groupe['nom'];
    }

    public function getNomRendu($idRendu)
    {
        $bdd = $this->getBdd();
        $query = "SELECT titre FROM rendu WHERE id_rendu = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idRendu]);
        $rendu = $stmt->fetch(PDO::FETCH_ASSOC);
        return $rendu['titre'];
    }

    public function getFichiersRemis($idRendu, $idGroupe)
    {
        $bdd = $this->getBdd();
        // Récupérer tous les fichiers associés à ce rendu et groupe
        $query = "SELECT chemin_fichier FROM Rendu_Fichier WHERE id_rendu = ? AND id_groupe = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idRendu, $idGroupe]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function supprimerTousLesFichiersRendu($idRendu, $idGroupe)
    {
        $bdd = $this->getBdd();
        // Supprimer tous les fichiers associés à ce rendu et groupe dans la table Rendu_Fichier
        $query = "DELETE FROM Rendu_Fichier WHERE id_rendu = ? AND id_groupe = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idRendu, $idGroupe]);
    }


}