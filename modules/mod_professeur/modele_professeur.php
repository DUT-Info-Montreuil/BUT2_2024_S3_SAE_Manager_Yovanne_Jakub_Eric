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
        $stmt = $bdd->prepare("SELECT titre FROM Projet INNER JOIN Gerant ON Projet.id_projet = Gerant.id_projet WHERE id_utilisateur = ?");
        $stmt->execute([$id_utilisateur]);
        $titres = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $titres;
    }

    public function ajouterProjet($titre, $annee, $description, $semestre) {
        $bdd = $this->getBdd();
        $stmt = $bdd->prepare("INSERT INTO Projet (id_projet, titre, annee_universitaire, description_projet, semestre) VALUES (DEFAULT, ?, ?, ?, ?)");
        $stmt->execute([$titre, $annee, $description, $semestre]);

        $idProjet = $bdd->lastInsertId();
        $insertionGerant = $bdd->prepare("INSERT INTO Gerant (id_projet, id_utilisateur, role_utilisateur) VALUES (?, ?, ?)");

        $insertionGerant->execute([$idProjet, $_SESSION['id_utilisateur'], 'Responsable']);
    }

}