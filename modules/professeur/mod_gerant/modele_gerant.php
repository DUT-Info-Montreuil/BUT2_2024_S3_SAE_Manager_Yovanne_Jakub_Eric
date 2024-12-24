<?php
include_once 'Connexion.php';

Class ModeleGerant extends Connexion {
    public function __construct()
    {
    }

    public function getGerantSAE($idSae){
        $bdd = $this->getBdd();
        $query = "SELECT 
                  CONCAT(Utilisateur.prenom, ' ', Utilisateur.nom) AS nom_complet, 
                  Gerant.role_utilisateur, 
                  Gerant.id_utilisateur 
              FROM Utilisateur 
              INNER JOIN Gerant ON Utilisateur.id_utilisateur = Gerant.id_utilisateur
              WHERE Gerant.id_projet = ?";
        $requete = $bdd->prepare($query);
        $requete->execute([$idSae]);
        $gerants = $requete->fetchAll(PDO::FETCH_ASSOC);
        return $gerants;
    }

    public function getGerantById($idSae, $idGerant){
        $bdd = $this->getBdd();
        $query = "
        SELECT 
            CONCAT(Utilisateur.prenom, ' ', Utilisateur.nom) AS nom_complet,
            Gerant.role_utilisateur,
            Utilisateur.id_utilisateur
        FROM 
            Utilisateur
        INNER JOIN 
            Gerant 
        ON 
            Utilisateur.id_utilisateur = Gerant.id_utilisateur
        WHERE 
            Gerant.id_projet = ? 
            AND Utilisateur.id_utilisateur = ?";

        $requete = $bdd->prepare($query);
        $requete->execute([$idSae, $idGerant]);
        $gerant = $requete->fetch(PDO::FETCH_ASSOC);
        return $gerant;
    }

    public function getProfesseurNonGerant($idSae) {
        $bdd = $this->getBdd();
        $query = "
        SELECT u.login_utilisateur, u.id_utilisateur, CONCAT(u.prenom, ' ', u.nom) AS nom_complet
        FROM Utilisateur u
        WHERE u.type_utilisateur = 'professeur'
        AND u.id_utilisateur NOT IN (
            SELECT g.id_utilisateur
            FROM Gerant g
            WHERE g.id_projet = :idSae
        )
    ";
        $stmt = $bdd->prepare($query);
        $stmt->bindParam(':idSae', $idSae, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function ajouterGerantSAE($gerantId, $roleGerant, $idSae) {
        $bdd = $this->getBdd();
        $sql = "INSERT INTO Gerant (id_projet, id_utilisateur, role_utilisateur) 
            VALUES (:id_projet, :id_utilisateur, :role_utilisateur)";

        $query = $bdd->prepare($sql);
        $query->execute([
            ':id_projet' => $idSae,
            ':id_utilisateur' => $gerantId,
            ':role_utilisateur' => $roleGerant == 1 ? 'Co-Responsable' : 'Intervenant'
        ]);
    }

    public function modifierRoleGerant($idSae, $id_utilisateur, $roleGerant){
        $bdd = $this->getBdd();
        $sql = "UPDATE Gerant SET role_utilisateur = ? WHERE id_utilisateur = ? AND id_projet = ?";
        $query = $bdd->prepare($sql);
        $query->execute([$roleGerant, $id_utilisateur, $idSae]);
    }

    public function supprimerGerantSAE($idSae, $idGerant) {
        $bdd = $this->getBdd();
        $sql = "DELETE FROM Gerant WHERE id_projet = ? AND id_utilisateur = ?";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([$idSae, $idGerant]);
    }


}