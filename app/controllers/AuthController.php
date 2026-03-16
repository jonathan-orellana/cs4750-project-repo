<?php

class AuthController {

    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function showLogin() {
        Response::view(__DIR__ . '/../views/auth/login.php');
    }

    public function showRegister() {
        Response::view(__DIR__ . '/../views/auth/register.php');
    }

    public function register() {
        $name = Request::input('name', '');
        $email = Request::input('email', '');
        $password = Request::input('password', '');

        if ($name == '' || $email == '' || $password == '') {
            Response::view(__DIR__ . '/../views/auth/register.php', [
                'error' => 'All fields are required.'
            ]);
            return;
        }

        $existingUser = $this->userModel->findByEmail($email);

        if ($existingUser) {
            Response::view(__DIR__ . '/../views/auth/register.php', [
                'error' => 'Email already exists.'
            ]);
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $this->userModel->create($name, $email, $hashedPassword);

        Response::redirect('/login');
    }

    public function login() {
        $email = Request::input('email', '');
        $password = Request::input('password', '');

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            Response::view(__DIR__ . '/../views/auth/login.php', [
                'error' => 'Invalid credentials.'
            ]);
            return;
        }

        Session::put('user_id', $user['user_id']);
        Session::put('user_name', $user['name']);

        Response::redirect('/dashboard');
    }

    public function logout() {
        Session::destroy();
        Response::redirect('/login');
    }

    public function showHome() {
        Response::view(__DIR__ . '/../views/home.php');
    }

    public function showDashboard() {

        if (!Session::has('user_id')) {
            Response::redirect('/login');
            return;
        }
    
        $name = Session::get('user_name', 'User');
    
        Response::view(__DIR__ . '/../views/dashboard.php', [
            'name' => $name
        ]);
    }
}