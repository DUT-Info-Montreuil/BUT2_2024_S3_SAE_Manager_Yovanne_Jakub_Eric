<?php

include_once "Connexion.php";
class ModeleCommun extends Connexion
{
    public function __construct() {
    }

    public static function getRoleSAE($idSAE, $idUtilisateur){
        $bdd = self::getBdd();

        $sql = "SELECT role_utilisateur 
            FROM Gerant 
            WHERE id_projet = ? AND id_utilisateur = ?";
        $req = $bdd->prepare($sql);
        $req->execute([$idSAE, $idUtilisateur]);

        if ($result = $req->fetch(PDO::FETCH_ASSOC)) {
            return $result['role_utilisateur'];
        } else {
            return null;
        }
    }

    public static function getDescriptionSAE($idProjet){
        $bdd = self::getBdd();
        $sql = "SELECT description_projet FROM Projet WHERE id_projet = ?";
        $req = $bdd->prepare($sql);
        $req->execute([$idProjet]);
        if ($result = $req->fetch(PDO::FETCH_ASSOC)) {
            return $result['description_projet'];
        }
        return null;
    }

    public static function getTypeUtilisateur($idUtilisateur) {
        $bdd = self::getBdd();
        $sql = "SELECT type_utilisateur FROM Utilisateur WHERE id_utilisateur = ?";
        $req = $bdd->prepare($sql);
        $req->execute([$idUtilisateur]);
        if ($result = $req->fetch(PDO::FETCH_ASSOC)) {
            return $result['type_utilisateur'];
        }
        return null;
    }

    public static function getTitreSAE($idProjet){
        $bdd = self::getBdd();
        $query = "SELECT titre FROM Projet WHERE id_projet = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idProjet]);
        $sae = $stmt->fetch(PDO::FETCH_ASSOC);
        return $sae['titre'];
    }

}