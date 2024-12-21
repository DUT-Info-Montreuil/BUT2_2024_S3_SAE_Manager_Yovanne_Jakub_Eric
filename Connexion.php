<?php

class Connexion {
    protected static $bdd;
    public static function initConnexion() {
        try {
            self::$bdd = new PDO("mysql:host=127.0.0.1;dbname=sae_manager", "root", "");
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }
    public static function getBdd() {
        return self::$bdd;
    }
}
