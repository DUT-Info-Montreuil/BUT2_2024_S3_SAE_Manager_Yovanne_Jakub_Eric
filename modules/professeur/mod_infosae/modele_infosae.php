<?php
include_once 'Connexion.php';
Class ModeleInfoSae extends Connexion
{
    public function __construct()
    {
    }
    public function modifierInfoGeneralSae($idSae, $titre, $annee, $semestre, $description) {
        $bdd = $this->getBdd();
        $stmt = $bdd->prepare("UPDATE Projet 
                           SET titre = ?, annee_universitaire = ?, semestre = ?, description_projet = ? 
                           WHERE id_projet = ?");
        $stmt->execute([$titre, $annee, $semestre, $description, $idSae]);
    }
    public function supprimerSAE($idSae) {
        $bdd = $this->getBdd();
        try {
            $bdd->beginTransaction();
            $requete = $bdd->prepare("DELETE FROM Projet WHERE id_projet = ?");
            $requete->execute([$idSae]);
            $bdd->commit();
        } catch (Exception $e) {
            $bdd->rollBack();
            echo "erreur pdt suppression de la SAE : " . $e->getMessage();
        }
    }
    public function getSaeDetails($idProjet) {
        $bdd = $this->getBdd();
        $stmt = $bdd->prepare("SELECT * FROM Projet WHERE id_projet = ?");
        $stmt->execute([$idProjet]);
        $saeDetails = $stmt->fetch(PDO::FETCH_ASSOC);
        return $saeDetails;
    }

    public function addChamp($champNom, $rempliPar, $idSae){
        $bdd = $this->getBdd();
        $sql = "INSERT INTO Champ (id_champ, id_projet, champ_nom, rempli_par) VALUES (DEFAULT, ?, ?, ?) ";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([$idSae, $champNom, $rempliPar]);
        return $bdd->lastInsertId();
    }
    public function addChampGrp($idChamp, $idGroupe){
        $bdd = $this->getBdd();
        $sql = "INSERT INTO Champ_Groupe (id_champ, id_groupe) VALUES (?, ?)";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([$idChamp, $idGroupe]);
    }

    public function getAllIdGroupeSAE($idSae) {
        $bdd = $this->getBdd();
        $stmt = $bdd->prepare("SELECT 
                            g.id_groupe AS id_groupe
                        FROM 
                            Projet_Groupe pg
                        INNER JOIN 
                            Groupe g ON pg.id_groupe = g.id_groupe
                        WHERE 
                            pg.id_projet = ?");
        $stmt->execute([$idSae]);
        $groupes = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return $groupes;
    }


    public function getAllChamp($idSae)
    {
        $bdd = $this->getBdd();
        $sql = "
        SELECT id_champ, champ_nom, rempli_par
        FROM Champ
        WHERE id_projet = ?
    ";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([$idSae]);
        $champs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $champs;
    }

    public function appliquerModifChamp($idChamp, $champNom, $rempliPar){
        $bdd = $this->getBdd();
        $sql = "UPDATE Champ SET rempli_par = ? , champ_nom = ? WHERE id_champ = ?";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([$rempliPar, $champNom, $idChamp]);
    }
    public function supprimerChamp($id_champ) {
        $bdd = $this->getBdd();
        $sql = "DELETE FROM Champ WHERE id_champ = ?";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([$id_champ]);
    }
}