<?php


class Sanitizacao {
    public static function limparTexto($texto) {
        return htmlspecialchars(trim($texto), ENT_QUOTES, 'UTF-8');
    }

    public static function limparEmail($email) {
        return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    }
}
?>
