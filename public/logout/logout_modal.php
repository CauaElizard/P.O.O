<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$user_name = $_SESSION['usuario_nome'] ?? 'Usuário';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Logout</title>
    <link rel="stylesheet" href="../../src/css/configuracoes.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/lucide.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Confirmar Logout</h1>
            <p>Olá, <?php echo htmlspecialchars($user_name); ?>!</p>
        </div>

        <div class="card" style="max-width: 500px; margin: 0 auto;">
            <div class="card-header">
                <h2><i data-lucide="log-out"></i> Sair da Conta</h2>
                <p>Tem certeza de que deseja sair da sua conta?</p>
            </div>
            <div class="card-content">
                <div style="text-align: center; margin-bottom: 2rem;">
                    <i data-lucide="user-check" style="width: 64px; height: 64px; color: #8b5cf6; margin-bottom: 1rem;"></i>
                    <p>Você será redirecionado para a página de login.</p>
                </div>
                
                <div style="display: flex; gap: 1rem; justify-content: center;">
                    <a href="../configuracoes/configuracoes.php" class="btn btn-secondary">
                        <i data-lucide="arrow-left"></i> Cancelar
                    </a>
                    <a href="logout.php" class="btn btn-danger">
                        <i data-lucide="log-out"></i> Confirmar Logout
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
