<?php
/**
 * Arquivo de configuração da aplicação
 * 
 * Define constantes e configurações globais do sistema
 */

// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'sistema_completo');
define('DB_USER', 'root');
define('DB_PASS', '&tec77@info!');
define('DB_CHARSET', 'utf8mb4');

// Configurações da aplicação
define('APP_NAME', 'Sistema de Cadastro de Livros');
define('APP_VERSION', '1.0.0');

// Configurações de erro
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configurações de sessão
session_start();

// Timezone
date_default_timezone_set('America/Sao_Paulo');
?>
