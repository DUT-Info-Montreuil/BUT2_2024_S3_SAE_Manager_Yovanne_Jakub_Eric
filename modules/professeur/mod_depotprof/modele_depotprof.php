<?php
include_once 'Connexion.php';

Class ModeleDepotProf extends Connexion {
    public function __construct()
    {
    }

    public function getAllDepotSAE($idSae) {
        $bdd = $this->getBdd();
        $sql = "
        SELECT *
        FROM Rendu r
        WHERE r.id_projet = ?
        ORDER BY r.date_limite ASC
        ";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([$idSae]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNomDepot($idRendue){
        $bdd = $this->getBdd();
        $sql = "SELECT titre FROM rendu WHERE id_rendu = ?";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([$idRendue]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['titre'];
    }

    public function creerDepot($titre, $dateLimite, $idSae){
        $bdd = $this->getBdd();
        $sql = "INSERT INTO Rendu (id_rendu, titre, date_limite, id_projet) VALUES (DEFAULT, ?, ?, ?)";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([$titre, $dateLimite, $idSae]);
        $idRendue = $bdd->lastInsertId();
        $this->creerDepotAllSae($idSae, $idRendue);
        return $idRendue;
    }

    public function creerDepotAllSae($idSae, $idRendue) {
        $bdd = $this->getBdd();

        $sql = "SELECT id_groupe FROM Projet_Groupe WHERE id_projet = ?";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([$idSae]);
        $groupes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($groupes as $groupe) {
            $sqlInsert = "INSERT INTO Rendu_Groupe (id_rendu, id_groupe, statut) VALUES (?, ?, 'En attente')";
            $stmtInsert = $bdd->prepare($sqlInsert);
            $stmtInsert->execute([$idRendue, $groupe['id_groupe']]);
        }
    }

    public function modifierRendu($id_rendu, $titre, $dateLimite) {
        $bdd = $this->getBdd();
        $sql = "UPDATE Rendu SET titre = ?, date_limite = ? WHERE id_rendu = ?";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([$titre, $dateLimite, $id_rendu]);
    }

    public function supprimerDepot($id_rendu) {
        $bdd = $this->getBdd();

        $sql = "DELETE FROM Rendu_Evaluation WHERE id_rendu = ?";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([$id_rendu]);

        $sql = "DELETE FROM Rendu_Groupe WHERE id_rendu = ?";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([$id_rendu]);

        $sql = "DELETE FROM Rendu WHERE id_rendu = ?";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([$id_rendu]);
    }

    public function getGroupesParSae($idSae) {
        $bdd = $this->getBdd();

        $query = "SELECT g.id_groupe, g.nom
              FROM Groupe g
              JOIN Projet_Groupe pg ON g.id_groupe = pg.id_groupe
              WHERE pg.id_projet = ?";

        $stmt = $bdd->prepare($query);
        $stmt->execute([$idSae]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}