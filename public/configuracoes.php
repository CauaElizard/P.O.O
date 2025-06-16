<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['usuario_id'];
$user_name = $_SESSION['usuario_nome'] ?? 'Usuário';

require_once '../models/UserSettings.php';
$userSettings = new UserSettings();
$currentUser = $userSettings->getUserById($user_id);

$error_message = $_SESSION['erro_configuracao'] ?? '';
$success_message = $_SESSION['sucesso_configuracao'] ?? '';
unset($_SESSION['erro_configuracao'], $_SESSION['sucesso_configuracao']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações da Conta</title>
    <link rel="stylesheet" href="../src/css/configuracoes.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/lucide.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Configurações da Conta</h1>
            <p>Olá, <?php echo htmlspecialchars($user_name); ?>! Gerencie suas informações pessoais e configurações de segurança</p>
            <div class="nav-links">
                <a href="../index.php" class="nav-link">← Voltar ao Início</a>
                <a href="logout.php" class="nav-link logout-btn">Desconectar    </a>
            </div>
        </div>

        <?php if ($error_message): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <div class="cards-grid">
            <div class="card">
                <div class="card-header">
                    <h2><i data-lucide="mail"></i> Atualizar Email</h2>
                    <p>Altere o endereço de email associado à sua conta</p>
                </div>
                <div class="card-content">
                    <form id="email-form" action="process_configuracoes.php" method="POST">
                        <input type="hidden" name="action" value="update_email">
                        <div class="form-group">
                            <label for="current-email">Email Atual</label>
                            <input type="email" id="current-email" value="<?php echo htmlspecialchars($currentUser['email'] ?? ''); ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label for="new-email">Novo Email</label>
                            <input type="email" id="new-email" name="new_email" placeholder="novo@exemplo.com" required>
                        </div>
                        <button type="submit" class="btn btn-primary" disabled>Atualizar Email</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2><i data-lucide="lock"></i> Alterar Senha</h2>
                    <p>Mantenha sua conta segura com uma senha forte</p>
                </div>
                <div class="card-content">
                    <form id="password-form" action="process_configuracoes.php" method="POST">
                        <input type="hidden" name="action" value="update_password">
                        <div class="form-group">
                            <label for="current-password">Senha Atual</label>
                            <div class="password-input">
                                <input type="password" id="current-password" name="current_password" placeholder="Digite sua senha atual" required>
                                <button type="button" class="toggle-password" data-target="current-password">
                                    <i data-lucide="eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new-password">Nova Senha</label>
                            <div class="password-input">
                                <input type="password" id="new-password" name="new_password" placeholder="Digite sua nova senha" required>
                                <button type="button" class="toggle-password" data-target="new-password">
                                    <i data-lucide="eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="confirm-password">Confirmar Nova Senha</label>
                            <div class="password-input">
                                <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirme sua nova senha" required>
                                <button type="button" class="toggle-password" data-target="confirm-password">
                                    <i data-lucide="eye"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" disabled>Alterar Senha</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card danger-zone">
            <div class="card-header">
                <h2><i data-lucide="shield"></i> Zona de Perigo</h2>
                <p>Ações irreversíveis que afetam permanentemente sua conta</p>
            </div>
            <div class="card-content">
                <div class="separator"></div>
                <div class="danger-section">
                    <div class="danger-item">
                        <i data-lucide="trash-2"></i>
                        <div class="danger-content">
                            <h3>Excluir Conta</h3>
                            <p>Esta ação é permanente e não pode ser desfeita. Todos os seus dados serão removidos permanentemente.</p>
                            <button type="button" class="btn btn-danger" id="delete-account-btn">Excluir Minha Conta</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="delete-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i data-lucide="trash-2"></i> Confirmar Exclusão da Conta</h3>
            </div>
            <div class="modal-body">
                <p>Tem certeza de que deseja excluir sua conta? Esta ação é permanente e não pode ser desfeita. Todos os seus dados, configurações e histórico serão perdidos para sempre.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancel-delete">Cancelar</button>
                <form action="process_configuracoes.php" method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="delete_account">
                    <button type="submit" class="btn btn-danger" id="confirm-delete-form">Sim, Excluir Conta</button>
                </form>
            </div>
        </div>
    </div>

    <div id="loading" class="loading hidden">
        <div class="spinner"></div>
    </div>

    <div id="toast-container"></div>

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>
        window.currentUserId = <?php echo json_encode($user_id); ?>;
    </script>
    <script src="../src/js/configuracoes.js"></script>
</body>
</html>
