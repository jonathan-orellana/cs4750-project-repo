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
        $firstName = trim((string) Request::input('first_name', ''));
        $lastName = trim((string) Request::input('last_name', ''));
        $email = trim((string) Request::input('email', ''));
        $password = (string) Request::input('password', '');
        $confirmPassword = (string) Request::input('confirm_password', '');
        $formData = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email
        ];

        if ($firstName === '' || $lastName === '' || $email === '' || $password === '' || $confirmPassword === '') {
            Response::view(__DIR__ . '/../views/auth/register.php', [
                'error' => 'All fields are required.',
                'formData' => $formData
            ]);
            return;
        }

        if ($password !== $confirmPassword) {
            Response::view(__DIR__ . '/../views/auth/register.php', [
                'error' => 'Passwords do not match.',
                'formData' => $formData
            ]);
            return;
        }

        $existingUser = $this->userModel->findByEmail($email);

        if ($existingUser) {
            Response::view(__DIR__ . '/../views/auth/register.php', [
                'error' => 'Email already exists.',
                'formData' => $formData
            ]);
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $name = trim($firstName . ' ' . $lastName);

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

        Response::redirect('/');
    }

    public function logout() {
        Session::destroy();
        Response::redirect('/login');
    }

    public function showHome() {
        Response::view(__DIR__ . '/../views/home.php');
    }

    public function showDashboard() {
        Auth::requireUser($this->userModel);
    
        $name = Session::get('user_name', 'User');
    
        Response::view(__DIR__ . '/../views/dashboard.php', [
            'name' => $name
        ]);
    }
}
