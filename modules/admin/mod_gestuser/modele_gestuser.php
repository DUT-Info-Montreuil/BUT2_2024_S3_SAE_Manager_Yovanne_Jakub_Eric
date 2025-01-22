<?php

include_once 'Connexion.php';
Class ModeleGestUser extends Connexion
{
    public function __construct()
    {
    }

    public function getAllUser(){
        $bdd = $this->getBdd();
        $query = $bdd->prepare("SELECT * FROM Utilisateur WHERE type_utilisateur != 'admin'");
        $query->execute();
        $users = $query->fetchAll();
        return $users;
    }

    public function getUserById($id){
        $bdd = $this->getBdd();
        $query = $bdd->prepare("SELECT * FROM Utilisateur WHERE id_utilisateur = ?");
        $query->execute([$id]);
        $user = $query->fetch();
        return $user;
    }

    public function supprimerUtilisateur($idUtilisateur) {
        $bdd = $this->getBdd();
        $query = $bdd->prepare("DELETE FROM Utilisateur WHERE id_utilisateur = ?");
        $query->execute([$idUtilisateur]);
    }


    public function updateUser($id_utilisateur, $nom, $prenom, $email, $login, $password, $type) {
        $bdd = $this->getBdd();
        try {
            $query = "UPDATE Utilisateur SET ";
            $fields = [];
            $params = [];

            if (!empty($nom)) {
                $fields[] = "nom = ?";
                $params[] = $nom;
            }
            if (!empty($prenom)) {
                $fields[] = "prenom = ?";
                $params[] = $prenom;
            }
            if (!empty($email)) {
                $fields[] = "email = ?";
                $params[] = $email;
            }
            if (!empty($login)) {
                $fields[] = "login_utilisateur = ?";
                $params[] = $login;
            }
            if (!empty($password)) {
                $fields[] = "password_utilisateur = ?";
                $params[] = $password;
            }
            if (!empty($type)) {
                $fields[] = "type_utilisateur = ?";
                $params[] = $type;
            }

            $query .= implode(", ", $fields) . " WHERE id_utilisateur = ?";
            $params[] = $id_utilisateur;

            $stmt = $bdd->prepare($query);
            $stmt->execute($params);
        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour de l'utilisateur : " . $e->getMessage();
        }
    }

    public function addUser($nom, $prenom, $email, $login, $password, $type) {
        $bdd = $this->getBdd();

        try {
            $profil_picture = "C:\\xampp\htdocs\\photo_profil\\default_avatar.png";
            $query = $bdd->prepare("INSERT INTO Utilisateur (nom, prenom, email, type_utilisateur, login_utilisateur, password_utilisateur,profil_picture) 
                               VALUES (?, ?, ?, ?, ?, ?,?)");
            $query->execute([$nom, $prenom, $email, $type, $login, $password,$profil_picture]);
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout de l'utilisateur : " . $e->getMessage();
        }
    }


    public function updateUserCSV($csvFilePath) {
        $bdd = $this->getBdd();
        $handle = fopen($csvFilePath, 'r');
        if ($handle) {

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                if (count($data) >= 6) {
                    $login = $data[0];
                    $nom = $data[1];
                    $prenom = $data[2];
                    $email = $data[3];
                    $password = password_hash($data[4], PASSWORD_DEFAULT);
                    $type_utilisateur = $data[5];
                    if (in_array($type_utilisateur, ['etudiant', 'professeur', 'intervenant'])) {
                        $query = $bdd->prepare(
                            "UPDATE Utilisateur 
                         SET nom = ?, prenom = ?, email = ?, type_utilisateur = ? ,password_utilisateur = ?
                         WHERE login_utilisateur = ?"
                        );
                        $query->execute([$nom, $prenom, $email, $type_utilisateur, $password, $login]);
                    } else {
                        echo "Erreur lors de l'ajout de l'utilisateur : " . $type_utilisateur . " n'est pas un type d'utilisateur valise. ['etudiant', 'professeur', 'intervenant']";
                    }
                }else{
                    echo "Erreur nombre d'argument incorrect.";
                }
            }
            fclose($handle);
        }
    }

    public function addUserCSV($csvFilePath) {
        $bdd = $this->getBdd();
        $handle = fopen($csvFilePath, 'r');
        if ($handle) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                if (count($data) >= 6) {
                    $login = $data[0];
                    $nom = $data[1];
                    $prenom = $data[2];
                    $email = $data[3];
                    $password = password_hash($data[4], PASSWORD_DEFAULT);
                    $type_utilisateur = $data[5];
                    $profil_picture = "C:\\xampp\htdocs\\photo_profil\\default_avatar.png";
                    if (in_array($type_utilisateur, ['etudiant', 'professeur', 'intervenant','admin'])) {
                        $query = $bdd->prepare(
                            "INSERT INTO Utilisateur (nom, prenom, email, login_utilisateur, password_utilisateur, type_utilisateur,profil_picture) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)"
                        );
                        $query->execute([$nom, $prenom, $email, $login, $password, $type_utilisateur,$profil_picture]);
                    }else {
                        echo "Erreur lors de l'ajout de l'utilisateur : " . $type_utilisateur . " n'est pas un type d'utilisateur valise. ['etudiant', 'professeur', 'intervenant','admin']";
                    }
                }else{
                    echo "Erreur nombre d'argument incorrect.";
                }
            }
            fclose($handle);
        }
    }









}