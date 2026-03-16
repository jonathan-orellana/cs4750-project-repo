<?php

class User {
    public function findByEmail($email) {
        global $pdo;

        $stmt = $pdo->prepare(
            'SELECT id, name, email, password FROM users WHERE email = :email LIMIT 1'
        );

        $stmt->execute(['email' => $email]);

        return $stmt->fetch();
    }

    public function create($name, $email, $hashedPassword) {
        global $pdo;

        $stmt = $pdo->prepare(
            'INSERT INTO users (name, email, password) VALUES (:name, :email, :password)'
        );

        return $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
        ]);
    }

}