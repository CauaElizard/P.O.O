<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$user_name = $_SESSION['usuario_nome'] ?? 'Usuário';
$user_email = $_SESSION['usuario_email'] ?? '';
?>

<div class="user-header">
    <div class="user-info">
        <div class="user-avatar">
            <i data-lucide="user"></i>
        </div>
        <div class="user-details">
            <h3><?php echo htmlspecialchars($user_name); ?></h3>
            <p><?php echo htmlspecialchars($user_email); ?></p>
        </div>
    </div>
    
    <div class="user-actions">
        <a href="configuracoes.php" class="btn-icon" title="Configurações">
            <i data-lucide="settings"></i>
        </a>
        <a href="logout_modal.php" class="btn-icon logout-btn" title="Sair">
            <i data-lucide="log-out"></i>
        </a>
    </div>
</div>

<style>
.user-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: rgba(255, 255, 255, 0.1);
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 2rem;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.user-avatar {
    width: 48px;
    height: 48px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.user-details h3 {
    color: white;
    margin: 0;
    font-size: 1.1rem;
}

.user-details p {
    color: rgba(255, 255, 255, 0.8);
    margin: 0;
    font-size: 0.9rem;
}

.user-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-icon {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-icon:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.3);
}

.btn-icon.logout-btn:hover {
    background: rgba(239, 68, 68, 0.2);
    border-color: rgba(239, 68, 68, 0.3);
    color: #fca5a5;
}
</style>
