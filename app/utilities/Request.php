<?php

class Request {
    public static function input($key, $default = null) {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    public static function method() {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }
}