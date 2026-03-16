<?php

use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    private $pdo;

    protected function setUp(): void {
        require_once __DIR__ . '/../vendor/autoload.php';
        require_once __DIR__ . '/../config/Database.php';

        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();

        $this->pdo = Database::connect();
    }

    public function testDatabaseConnection(): void {
        $stmt = $this->pdo->query("SELECT 1");
        $result = $stmt->fetchColumn();

        $this->assertEquals(1, $result, "Failed to connect to the database.");
    }
}