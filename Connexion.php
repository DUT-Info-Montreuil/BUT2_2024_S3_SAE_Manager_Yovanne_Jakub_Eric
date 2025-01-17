<?php

class Connexion {
    protected static $bdd;
    public static function initConnexion() {
        try {
            self::$bdd = new PDO("mysql:host=database-etudiants.iut.univ-paris8.fr;dbname=dutinfopw201655", "dutinfopw201655", "hevenequ");
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }
    public static function getBdd() {
        return self::$bdd;
    }
}
