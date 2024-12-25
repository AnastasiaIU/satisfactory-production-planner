<?php

use utils\ErrorHandler;

/**
 * Handles the database connection using PDO.
 */
class BaseModel
{
    protected static ?PDO $pdo = null;

    function __construct()
    {
        if (!self::$pdo) {

            $host = $_ENV['DB_HOST'];
            $db = $_ENV['DB_NAME'];
            $user = $_ENV['DB_USER'];
            $pass = $_ENV['DB_PASSWORD'];
            $charset = $_ENV['DB_CHARSET'];

            try {
                $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ];

                self::$pdo = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                ErrorHandler::handleException($e);
            }
        }
    }
}
