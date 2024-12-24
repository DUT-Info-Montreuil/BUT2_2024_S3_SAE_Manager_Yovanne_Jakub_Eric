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

    public function getProfesseur() {
        $bdd = $this->getBdd();
        $query = "SELECT login_utilisateur, id_utilisateur, CONCAT(prenom, ' ', nom) AS nom_complet FROM Utilisateur WHERE type_utilisateur = 'professeur'";
        $stmt = $bdd->prepare($query);
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

}