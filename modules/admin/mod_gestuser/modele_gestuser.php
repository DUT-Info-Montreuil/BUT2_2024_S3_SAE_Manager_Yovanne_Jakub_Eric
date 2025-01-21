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
            $query = $bdd->prepare("INSERT INTO Utilisateur (nom, prenom, email, type_utilisateur, login_utilisateur, password_utilisateur) 
                               VALUES (?, ?, ?, ?, ?, ?)");
            $query->execute([$nom, $prenom, $email, $type, $login, $password]);
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout de l'utilisateur : " . $e->getMessage();
        }
    }


    public function updateUsers($csvFilePath) {
        $bdd = $this->getBdd();
        $handle = fopen($csvFilePath, 'r');
        if ($handle) {
            // Lire le fichier ligne par ligne
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                // Supposons que le fichier CSV ait les colonnes suivantes :
                // [login, nom, prenom, email, type_utilisateur]
                $login = $data[0];
                $nom = $data[1];
                $prenom = $data[2];
                $email = $data[3];
                $type_utilisateur = $data[4];

                // Mettre à jour l'utilisateur correspondant
                $query = $bdd->prepare(
                    "UPDATE Utilisateur 
                 SET nom = ?, prenom = ?, email = ?, type_utilisateur = ? 
                 WHERE login_utilisateur = ?"
                );
                $query->execute([$nom, $prenom, $email, $type_utilisateur, $login]);
            }
            fclose($handle);
        }
    }



}