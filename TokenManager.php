<?php

class TokenManager
{
    public static function genererToken()
    {
        return bin2hex(random_bytes(32));
    }


    public static function stockerAndGenerateToken()
    {
        $token = static::genererToken();
        $_SESSION['token'] = $token;
        $_SESSION['token_expiration'] = time() + 900;
    }

    public static function verifierToken()
    {
        if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
            return false;
        } else if ($_SESSION['token_expiration'] < time()) {
            return false;
        }
        return true;
    }


    public static function reinitialiserToken()
    {
        unset($_SESSION['token']);
        unset($_SESSION['token_expiration']);
    }

}
