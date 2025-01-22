<?php

include_once "Connexion.php";

class ModeleCommunEtudiant extends Connexion
{
    public static function getGroupeForUser($idProjet, $idUtilisateur) {
        $bdd = static::getBdd();

        $query = "
        SELECT g.id_groupe
        FROM Groupe g
        INNER JOIN Groupe_Etudiant ge ON g.id_groupe = ge.id_groupe
        INNER JOIN Projet_Groupe pg ON g.id_groupe = pg.id_groupe
        WHERE pg.id_projet = ? AND ge.id_utilisateur = ?
    ";

        $stmt = $bdd->prepare($query);
        $stmt->execute([$idProjet, $idUtilisateur]);
        $groupe = $stmt->fetch(PDO::FETCH_ASSOC);
        return $groupe ? $groupe['id_groupe'] : null;
    }
}