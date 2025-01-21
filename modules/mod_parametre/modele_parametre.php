<?php

include_once 'Connexion.php';

class ModeleParametre extends Connexion
{
    public function __construct()
    {
    }

    public function getCompteById($idUtilisateur)
    {
        $bdd = $this->getBdd();
        $query = "
        SELECT *
        FROM Utilisateur 
        WHERE id_utilisateur = ?";

        $stmt = $bdd->prepare($query);
        $stmt->execute([$idUtilisateur]);
        $compte = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $compte;
    }

    public function modifierAnneeScolaire($idUtilisateur, $annee_debut, $annee_fin, $semestre)
    {
        $bdd = $this->getBdd();
        $sqlCheck = "SELECT id_annee FROM Annee_Scolaire 
                 WHERE annee_debut = ? 
                   AND annee_fin = ? 
                   AND semestre = ?";

        $stmtCheck = $bdd->prepare($sqlCheck);
        $stmtCheck->execute([$annee_debut, $annee_fin, $semestre]);

        $existingAnnee = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if ($existingAnnee) {
            $idAnneeScolaire = $existingAnnee['id_annee'];
        } else {
            $sqlInsertAnnee = "INSERT INTO Annee_Scolaire (annee_debut, annee_fin, semestre)
                           VALUES (?, ?, ?)";
            $stmtInsertAnnee = $bdd->prepare($sqlInsertAnnee);
            $stmtInsertAnnee->execute([$annee_debut, $annee_fin, $semestre]);
            $idAnneeScolaire = $bdd->lastInsertId();
        }

        $sqlDeleteAssoc = "DELETE FROM Etudiant_Annee 
                       WHERE id_utilisateur = ? 
                         AND id_annee != ?";
        $stmtDeleteAssoc = $bdd->prepare($sqlDeleteAssoc);
        $stmtDeleteAssoc->execute([$idUtilisateur, $idAnneeScolaire]);
        $sqlInsertAssoc = "INSERT INTO Etudiant_Annee (id_utilisateur, id_annee) 
                       VALUES (?, ?)";
        $stmtInsertAssoc = $bdd->prepare($sqlInsertAssoc);
        $stmtInsertAssoc->execute([$idUtilisateur, $idAnneeScolaire]);
    }



    public function modifierCompte($idUtilisateur, $nom, $prenom, $email, $login_utilisateur, $password_utilisateur)
    {
        $bdd = $this->getBdd();
        if ($password_utilisateur) {
            $query = "
        UPDATE Utilisateur 
        SET nom = ?, prenom = ?, email = ?, login_utilisateur = ?, password_utilisateur = ?
        WHERE id_utilisateur = ?";
            $stmt = $bdd->prepare($query);
            $stmt->execute([$nom, $prenom, $email, $login_utilisateur, $password_utilisateur, $idUtilisateur]);
        } else {
            $query = "
        UPDATE Utilisateur 
        SET nom = ?, prenom = ?, email = ?, login_utilisateur = ?
        WHERE id_utilisateur = ?";
            $stmt = $bdd->prepare($query);
            $stmt->execute([$nom, $prenom, $email, $login_utilisateur, $idUtilisateur]);
        }
    }


    public function modifierPhotoDeProfil($id_utilisateur, $logo)
    {

        $bdd = $this->getBdd();

        $query = "
        UPDATE Utilisateur
        SET profil_picture = ?
        WHERE id_utilisateur = ?";

        $stmt = $bdd->prepare($query);
        $stmt->execute([$logo, $id_utilisateur]);
    }

    public function modifierCheminProfilPicture($idUtilisateur, $uploadPath)
    {
        $bdd = $this->getBdd();
        $sql = "UPDATE Utilisateur SET profil_picture = ? WHERE id_utilisateur = ?";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([$uploadPath, $idUtilisateur]);
    }

    public function getProfilPictureById($idUtilisateur)
    {
        $bdd = $this->getBdd();
        $query = "SELECT profil_picture FROM Utilisateur WHERE id_utilisateur = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idUtilisateur]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && isset($result['profil_picture'])) {
            $profilPictureName = basename($result['profil_picture']);
            return $profilPictureName;
        }

        return null;
    }

    public function getAnneeScolaireByEtudiant($idUtilisateur)
    {
        $bdd = $this->getBdd();
        $query = "
    SELECT a.id_annee, a.annee_debut, a.annee_fin, semestre
    FROM Annee_Scolaire a
    JOIN Etudiant_Annee ea ON a.id_annee = ea.id_annee
    WHERE ea.id_utilisateur = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idUtilisateur]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



}