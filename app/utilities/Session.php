<?php

class Session {

    public static function put($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }

    public static function has($key) {
        return isset($_SESSION[$key]);
    }

    public static function forget($key) {
        unset($_SESSION[$key]);
    }

    public static function destroy() {
        $_SESSION = [];
        session_destroy();
    }

}