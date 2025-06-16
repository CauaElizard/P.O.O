<?php

class Sanitizacao {

    public function sanitizeString($string) {
        return filter_var(trim($string), FILTER_SANITIZE_STRING);
    }

    public function sanitizeInt($int) {
        return filter_var(trim($int), FILTER_SANITIZE_NUMBER_INT);
    }

    public function sanitizeFloat($float) {
        return filter_var(trim($float), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    public function sanitizeURL($url) {
        return filter_var(trim($url), FILTER_SANITIZE_URL);
    }

    public function sanitizeEmail($email) {
        return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    }

    public static function limparTexto($texto) {
        return htmlspecialchars(trim($texto), ENT_QUOTES, 'UTF-8');
    }

    public static function limparEmail($email) {
        return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    }
}
