<?php
include_once 'Connexion.php';
Class ModeleProfesseur extends Connexion{
    public function __construct() {
    }


    public function utilisateurExiste($login){
        $bdd = $this->getBdd();
        $stmt = $bdd->prepare("SELECT * FROM Utilisateur WHERE login_utilisateur = ?");
        $stmt->execute([$login]);
        $user = $stmt->fetch();
        if($user){
            return true;
        }
        return false;
    }
    public function saeGerer($id_utilisateur) {
        $bdd = $this->getBdd();
        $stmt = $bdd->prepare("SELECT * FROM Projet INNER JOIN Gerant ON Projet.id_projet = Gerant.id_projet WHERE id_utilisateur = ?");
        $stmt->execute([$id_utilisateur]);
        $sae = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $sae;
    }

    public function ajouterProjet($titre, $annee, $description, $semestre) {
        $bdd = $this->getBdd();
        $stmt = $bdd->prepare("INSERT INTO Projet (id_projet, titre, annee_universitaire, description_projet, semestre) VALUES (DEFAULT, ?, ?, ?, ?)");
        $stmt->execute([$titre, $annee, $description, $semestre]);

        $idProjet = $bdd->lastInsertId();
        $insertionGerant = $bdd->prepare("INSERT INTO Gerant (id_projet, id_utilisateur, role_utilisateur) VALUES (?, ?, ?)");

        $insertionGerant->execute([$idProjet, $_SESSION['id_utilisateur'], 'Responsable']);
    }

    public function getSaeDetails($idProjet) {
        $bdd = $this->getBdd();
        $stmt = $bdd->prepare("SELECT * FROM Projet WHERE id_projet = ?");
        $stmt->execute([$idProjet]);
        $saeDetails = $stmt->fetch(PDO::FETCH_ASSOC);
        return $saeDetails;
    }

    public function modifierInfoGeneralSae($idSae, $titre, $annee, $semestre, $description) {
        $bdd = $this->getBdd();
        $stmt = $bdd->prepare("UPDATE Projet 
                           SET titre = ?, annee_universitaire = ?, semestre = ?, description_projet = ? 
                           WHERE id_projet = ?");
        $stmt->execute([$titre, $annee, $semestre, $description, $idSae]);
    }

    public function getSaeGroupe($idSae){
        $bdd = $this->getBdd();
        $stmt = $bdd->prepare("SELECT 
                                g.nom AS nom_groupe,
                                u.nom AS nom_membre,
                                u.prenom AS prenom_membre
                            FROM 
                                Projet_Groupe pg
                            INNER JOIN 
                                Groupe g ON pg.id_groupe = g.id_groupe
                            INNER JOIN 
                                Groupe_Etudiant ge ON g.id_groupe = ge.id_groupe
                            INNER JOIN 
                                Utilisateur u ON ge.id_utilisateur = u.id_utilisateur
                            WHERE 
                                pg.id_projet = ?");
        $stmt->execute([$idSae]);
        $groupes = $stmt->fetch(PDO::FETCH_ASSOC);
        return $groupes;
    }



}