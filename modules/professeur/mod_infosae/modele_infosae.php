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
}