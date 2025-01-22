<?php
include_once 'Connexion.php';

Class ModeleSoutenanceProf extends Connexion
{
    public function __construct()
    {
    }

    public function getAllSoutenance($idSae){
        $bdd = $this->getBdd();

        $sql = "
    SELECT 
        soutenance.id_soutenance, 
        soutenance.id_projet, 
        soutenance.titre,
        soutenance.date_soutenance,
        soutenance.id_evaluation,
        groupe.id_groupe,
        groupe.nom AS groupe_nom,
        soutenance_groupe.heure_passage
    FROM Soutenance soutenance
    LEFT JOIN Soutenance_Groupe soutenance_groupe ON soutenance.id_soutenance = soutenance_groupe.id_soutenance
    LEFT JOIN Groupe groupe ON soutenance_groupe.id_groupe = groupe.id_groupe
    WHERE soutenance.id_projet = ?
    ";

        $req = $bdd->prepare($sql);
        $req->execute([$idSae]);
        $soutenances = $req->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($soutenances as $soutenance) {
            if (!isset($result[$soutenance['id_soutenance']])) {
                $result[$soutenance['id_soutenance']] = [
                    'id_soutenance' => $soutenance['id_soutenance'],
                    'id_projet' => $soutenance['id_projet'],
                    'titre' => $soutenance['titre'],
                    'date_soutenance' => $soutenance['date_soutenance'],
                    'id_evaluation' => $soutenance['id_evaluation'],
                    'groupes' => []
                ];
            }
            $result[$soutenance['id_soutenance']]['groupes'][] = [
                'id_groupe' => $soutenance['id_groupe'],
                'groupe_nom' => $soutenance['groupe_nom'],
                'heure_passage' => $soutenance['heure_passage']
            ];
        }
        return array_values($result);
    }

    public function modifierHeureSoutenance($idSoutenance, $idGroupe, $heurePassage)
    {
        $bdd = $this->getBdd();
        $sql = "UPDATE Soutenance_Groupe 
            SET heure_passage = :heure_passage 
            WHERE id_soutenance = :id_soutenance AND id_groupe = :id_groupe";
        $req = $bdd->prepare($sql);
        $req->execute([
            ':heure_passage' => $heurePassage,
            ':id_soutenance' => $idSoutenance,
            ':id_groupe' => $idGroupe
        ]);
    }



    public function getAllGroupeByIdSae($idSae) {
        $bdd = $this->getBdd();
        $grpSql = "
        SELECT g.id_groupe, g.nom 
        FROM Projet_Groupe pg
        JOIN Groupe g ON pg.id_groupe = g.id_groupe
        WHERE pg.id_projet = ?
    ";
        $grpReq = $bdd->prepare($grpSql);
        $grpReq->execute([$idSae]);
        return $grpReq->fetchAll(PDO::FETCH_ASSOC);
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


    public function ajouterSoutenance($idSae, $titre, $dateSoutenanceGenerale){
        $bdd = $this->getBdd();

        try {
            $bdd->beginTransaction();

            $sql = "INSERT INTO Soutenance (id_soutenance, id_projet, titre, date_soutenance) VALUES (DEFAULT, ?, ?, ?)";
            $req = $bdd->prepare($sql);
            $req->execute([$idSae, $titre, $dateSoutenanceGenerale]);

            $lastId = $bdd->lastInsertId();
            $bdd->commit();
            return $lastId;
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

    public function ajouterSoutenanceGroupe($idSoutenance, $idGroupe, $heure) {
        $bdd = $this->getBdd();
        $sql = "INSERT INTO Soutenance_Groupe (id_soutenance, id_groupe, heure_passage) VALUES (?, ?, ?)";
        $req = $bdd->prepare($sql);
        $req->execute([$idSoutenance, $idGroupe, $heure]);
    }

}