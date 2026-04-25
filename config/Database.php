<?php

class Database {
    private static $pdo = null;

    public static function connect() {

        if (self::$pdo === null) {

            try {
                $host = $_ENV['DB_HOST'];
                $port = $_ENV['DB_PORT'];
                $db   = $_ENV['DB_NAME'];
                $user = $_ENV['DB_USER'];
                $pass = $_ENV['DB_PASS'];

                $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

                self::$pdo = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);

            } catch (PDOException $e) {

                $logDir = __DIR__ . '/../storage/logs';
                $logFile = $logDir . '/app.log';

                if (!is_dir($logDir)) {
                    mkdir($logDir, 0775, true);
                }

                $message = date('Y-m-d H:i:s') .
                           " DATABASE ERROR: " .
                           $e->getMessage() .
                           PHP_EOL;

                file_put_contents($logFile, $message, FILE_APPEND);
                die("Database connection error.");
            }
        }
        return self::$pdo;
    }
}
