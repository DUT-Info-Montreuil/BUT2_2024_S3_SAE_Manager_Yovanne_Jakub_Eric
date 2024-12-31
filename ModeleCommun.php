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

    public static function pasEtudiant($idSAE, $idUtilisateur) {
        $role = self::getRoleSAE($idSAE, $idUtilisateur);
        return $role !== "etudiant" && $role !== null;
    }


}