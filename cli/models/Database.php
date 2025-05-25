<?php
class Database
{
    private static $pdo = null;

    public static function getConnection()
    {
        if (self::$pdo === null) {
            $config = require __DIR__ . '/../config/mysql.php';
            try {
                self::$pdo = new PDO(
                    "mysql:host={$config['host_name']};dbname={$config['database']};charset=utf8mb4",
                    $config['user_name'] ,
                    $config['password'],
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
            } catch (PDOException $e) {
                error_log('ENOOKI - Erreur connexion MySQL : ' . $e->getMessage());
                throw new Exception('Erreur de connexion à la base de données.');
            }
        }
        return self::$pdo;
    }
}
