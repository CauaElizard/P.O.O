<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../models/UserSettings.php';
require_once '../utils/Sanitizacao.php';

$user_id = $_SESSION['usuario_id'];
$userSettings = new UserSettings();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'update_email':
            $new_email = Sanitizacao::limparEmail($_POST['new_email'] ?? '');
            
            if (empty($new_email)) {
                $_SESSION['erro_configuracao'] = 'Email é obrigatório';
                header('Location: configuracoes.php');
                exit;
            }
            
            if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['erro_configuracao'] = 'Email inválido';
                header('Location: configuracoes.php');
                exit;
            }
            
            try {
                $result = $userSettings->updateEmail($user_id, $new_email);
                if ($result) {
                    $_SESSION['sucesso_configuracao'] = 'Email atualizado com sucesso!';
                } else {
                    $_SESSION['erro_configuracao'] = 'Erro ao atualizar email';
                }
            } catch (Exception $e) {
                $_SESSION['erro_configuracao'] = $e->getMessage();
            }
            break;
            
        case 'update_password':
            $current_password = Sanitizacao::limparTexto($_POST['current_password'] ?? '');
            $new_password = Sanitizacao::limparTexto($_POST['new_password'] ?? '');
            $confirm_password = Sanitizacao::limparTexto($_POST['confirm_password'] ?? '');
            
            if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
                $_SESSION['erro_configuracao'] = 'Todos os campos são obrigatórios';
                header('Location: configuracoes.php');
                exit;
            }
            
            if ($new_password !== $confirm_password) {
                $_SESSION['erro_configuracao'] = 'As senhas não coincidem';
                header('Location: configuracoes.php');
                exit;
            }
            
            if (strlen($new_password) < 6) {
                $_SESSION['erro_configuracao'] = 'A senha deve ter pelo menos 6 caracteres';
                header('Location: configuracoes.php');
                exit;
            }
            
            try {
                if (!$userSettings->verifyPassword($user_id, $current_password)) {
                    $_SESSION['erro_configuracao'] = 'Senha atual incorreta';
                    header('Location: configuracoes.php');
                    exit;
                }
                
                $result = $userSettings->updatePassword($user_id, $new_password);
                if ($result) {
                    $_SESSION['sucesso_configuracao'] = 'Senha atualizada com sucesso!';
                } else {
                    $_SESSION['erro_configuracao'] = 'Erro ao atualizar senha';
                }
            } catch (Exception $e) {
                $_SESSION['erro_configuracao'] = 'Erro interno do servidor';
            }
            break;
            
        case 'delete_account':
            try {
                $result = $userSettings->deleteAccount($user_id);
                if ($result) {
                    session_destroy();
                    header('Location: login.php?message=Conta excluída com sucesso');
                    exit;
                } else {
                    $_SESSION['erro_configuracao'] = 'Erro ao excluir conta';
                }
            } catch (Exception $e) {
                $_SESSION['erro_configuracao'] = 'Erro interno do servidor';
            }
            break;
            
        default:
            $_SESSION['erro_configuracao'] = 'Ação inválida';
            break;
    }
}

header('Location: configuracoes.php');
exit;
?>
