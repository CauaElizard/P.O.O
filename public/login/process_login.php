<?php
session_start();
require_once __DIR__ . '/../../models/UsuarioLogin.php';
require_once __DIR__ . '/../../utils/Sanitizacao.php';


$email = Sanitizacao::limparEmail($_POST['email']);
$senha = Sanitizacao::limparTexto($_POST['senha']);

$login = new UsuarioLogin();
$usuario = $login->autenticar($email, $senha);

if ($usuario) {
    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['usuario_nome'] = $usuario['nome'];
    header("Location: ../configuracoes/configuracoes.php");
    exit;
} else {
    $_SESSION['erro_login'] = "Email ou senha inválidos.";
    header("Location: ../login/login.php");
    exit;
}
?>
