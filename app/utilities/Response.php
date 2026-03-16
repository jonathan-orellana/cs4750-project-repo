<?php

class Response {
    public static function redirect($path) {
        header("Location: {$path}");
        exit;
    }

    public static function view($file, $data = []) {
        extract($data);
        require $file;
    }

}