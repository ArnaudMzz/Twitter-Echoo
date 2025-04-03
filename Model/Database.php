<?php

class Database
{
    private static $pdo = null;

    public static function getConnection()
    {
        if (self::$pdo === null) {
            $dbHost = "localhost";
            $dbName = "my_twitter";
            $username = "administrateur";
            $password = "nono";

            try {
                self::$pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8", $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]);
            } catch (PDOException $e) {
                error_log("Erreur de connexion : " . $e->getMessage());
                die("Erreur de connexion à la base de données.");
            }
        }
        return self::$pdo;
    }
}
