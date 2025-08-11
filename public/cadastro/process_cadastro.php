<?php
require_once "../../config/Database.php";
require_once "../../models/UsuarioCad.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->getConexao();

    $usuario = new Usuario($db);

    $usuario->nome = $_POST['nome'] ?? '';
    $usuario->email = $_POST['email'] ?? '';
    $usuario->senha = $_POST['senha'] ?? '';

    if ($usuario->cadastrar()) {
        header("Location: ../login/login.php");
        exit;
    } else {
        echo "Erro ao cadastrar usuário.";
    }
}
?>
