<?php
require_once "ModeleCommun.php";

Class ControllerCommun {
    public static function pasEtudiant($idSAE, $idUtilisateur) {
        $role = ModeleCommun::getRoleSAE($idSAE, $idUtilisateur);
        return $role !== "etudiant" && $role !== null;
    }

    public static function estAdmin(){
        return ModeleCommun::getTypeUtilisateur($_SESSION['id_utilisateur']) === "admin";
    }

    public static function estEtudiant(){
        return ModeleCommun::getTypeUtilisateur($_SESSION['id_utilisateur']) === "etudiant";
    }

    public static function estProfOuIntervenant(){
        $typeUser =  ModeleCommun::getTypeUtilisateur($_SESSION['id_utilisateur']);
        return $typeUser==="professeur" || $typeUser==="intervenant";
    }

    public static function estProf()
    {
        return ModeleCommun::getTypeUtilisateur($_SESSION['id_utilisateur']) === "professeur";
    }


}