<?php
include_once 'Connexion.php';

Class ModeleSoutenanceProf extends Connexion
{
    public function __construct()
    {
    }

    public function getAllSoutenance($idSae){
        $bdd = $this->getBdd();
        $sql = "SELECT * FROM Soutenance WHERE id_projet = ?";
        $req = $bdd->prepare($sql);
        $req->execute([$idSae]);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllGroupeByIdSae($idSae){
        $bdd = $this->getBdd();
        $grpSql = "SELECT id_groupe FROM Projet_Groupe WHERE id_projet = ?";
        $grpReq = $bdd->prepare($grpSql);
        $grpReq->execute([$idSae]);
        return $grpReq->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getEtudiantsParSoutenance($idSoutenance)
    {
        $bdd = static::getBdd();
        $query = "
    SELECT GE.id_utilisateur, GE.id_groupe
    FROM Groupe_Etudiant GE
    JOIN Soutenance_Groupe SG ON GE.id_groupe = SG.id_groupe
    WHERE SG.id_soutenance = ?
    ";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idSoutenance]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function ajouterSoutenance($idSae, $titre, $date_soutenance){
        $bdd = $this->getBdd();

        try {
            $bdd->beginTransaction();

            $sql = "INSERT INTO Soutenance (id_soutenance, id_projet, titre, date_soutenance) VALUES (DEFAULT, ?, ?, ?)";
            $req = $bdd->prepare($sql);
            $req->execute([$idSae, $titre, $date_soutenance]);

            $lastId = $bdd->lastInsertId();
            $groupes = $this->getAllGroupeByIdSae($idSae);

            $grpSoutenanceSql = "INSERT INTO Soutenance_Groupe (id_soutenance, id_groupe) VALUES (?, ?)";
            $grpSoutenanceReq = $bdd->prepare($grpSoutenanceSql);

            foreach ($groupes as $idGroupe) {
                $grpSoutenanceReq->execute([$lastId, $idGroupe]);
            }

            $bdd->commit();
        } catch (Exception $e) {
            $bdd->rollBack();
            throw new Exception("erreur ajout soutenance " . $e->getMessage());
        }
    }

    public function modifierSoutenance($idSoutenance, $titre, $dateSoutenance)
    {
        $bdd = $this->getBdd();
        $sql = "UPDATE Soutenance 
                SET titre = ?, date_soutenance = ?
                WHERE id_soutenance = ?";
        $req = $bdd->prepare($sql);
        $req->execute([$titre, $dateSoutenance, $idSoutenance]);

    }
    public function supprimerSoutenance($idSoutenance)
    {
        $bdd = $this->getBdd();
        $sql = "DELETE FROM Soutenance WHERE id_soutenance = ?";
        $req = $bdd->prepare($sql);
        $req->execute([$idSoutenance]);
    }


}