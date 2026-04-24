<?php

class Auth {
    public static function requireUser($userModel) {
        $userId = Session::get('user_id');

        if ($userId === null) {
            Response::redirect('/login');
        }

        $authenticatedUser = $userModel->findById((int) $userId);

        if (!$authenticatedUser) {
            Session::destroy();
            Response::redirect('/login');
        }

        Session::put('user_name', $authenticatedUser['name']);

        return $authenticatedUser;
    }
}
