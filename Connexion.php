<?php

class Connexion {
    protected static $bdd;
    public static function initConnexion() {
        try {
            self::$bdd = new PDO(
                "mysql:host=127.0.0.1;dbname=sae_manager", "root", "");
            echo "Connexion réussis";
        } catch (PDOException $e) {
            print_r(PDO::getAvailableDrivers());
        }
    }
    public static function getBdd() {
        return self::$bdd;
    }
}
