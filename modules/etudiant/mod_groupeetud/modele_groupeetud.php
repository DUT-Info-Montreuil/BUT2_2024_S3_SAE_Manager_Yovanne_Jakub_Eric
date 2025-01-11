<?php

include_once 'Connexion.php';
Class ModeleGroupeEtud extends Connexion
{
    public function __construct()
    {
    }

    public function getGroupeSAE($idGroupe) {
        $bdd = $this->getBdd();
        $query = "
        SELECT u.id_utilisateur, u.nom, u.prenom, u.email
        FROM Utilisateur u
        INNER JOIN Groupe_Etudiant ge ON u.id_utilisateur = ge.id_utilisateur
        WHERE ge.id_groupe = ?
    ";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idGroupe]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNomGroupe($idGroupe){
        $bdd = $this->getBdd();
        $query = "SELECT nom FROM Groupe WHERE id_groupe = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idGroupe]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['nom'];
    }

    public function getChampARemplir($idGroupe, $idSae) {
        $bdd = $this->getBdd();

        $query = "
    SELECT 
        c.id_champ, 
        c.champ_nom, 
        cg.champ_valeur
    FROM Champ c
    LEFT JOIN Champ_Groupe cg ON c.id_champ = cg.id_champ AND cg.id_groupe = ?
    WHERE c.id_projet = ? AND c.rempli_par = 'Groupe'
    ";

        $stmt = $bdd->prepare($query);
        $stmt->execute([$idGroupe, $idSae]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateChampGroupe($idGroupe, $idChamp, $champValeur) {
        $bdd = $this->getBdd();

        $query = "SELECT COUNT(*) FROM Champ_Groupe WHERE id_groupe = ? AND id_champ = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idGroupe, $idChamp]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $query = "UPDATE Champ_Groupe SET champ_valeur = ? WHERE id_groupe = ? AND id_champ = ?";
            $stmt = $bdd->prepare($query);
            $stmt->execute([$champValeur, $idGroupe, $idChamp]);
        } else {
            $query = "INSERT INTO Champ_Groupe (id_groupe, id_champ, champ_valeur) VALUES (?, ?, ?)";
            $stmt = $bdd->prepare($query);
            $stmt->execute([$idGroupe, $idChamp, $champValeur]);
        }
    }





}