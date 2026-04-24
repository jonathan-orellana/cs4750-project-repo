<?php

class User {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    public function findByEmail($email) {
        $stmt = $this->pdo->prepare(
            'SELECT user_id, name, email, password
             FROM users
             WHERE email = :email
             LIMIT 1'
        );

        $stmt->execute([
            'email' => $email
        ]);

        return $stmt->fetch();
    }

    public function findById($userId) {
        $stmt = $this->pdo->prepare(
            'SELECT user_id, name, email
             FROM users
             WHERE user_id = :user_id
             LIMIT 1'
        );

        $stmt->execute([
            'user_id' => $userId
        ]);

        return $stmt->fetch();
    }

    public function create($name, $email, $hashedPassword) {
        $stmt = $this->pdo->prepare(
            'INSERT INTO users (name, email, password)
             VALUES (:name, :email, :password)'
        );

        return $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword
        ]);
    }
}
