-- Criar banco de dados unificado
CREATE DATABASE IF NOT EXISTS sistema_completo
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

-- Selecionar o banco
USE sistema_completo;

-- Tabela de usuários
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_usuarios_email (email),
    INDEX idx_usuarios_criado_em (criado_em)
);

-- Tabela de livros
CREATE TABLE IF NOT EXISTS livros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    autor VARCHAR(255) NOT NULL,
    ano INT NOT NULL,
    isbn VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_titulo (titulo),
    INDEX idx_autor (autor),
    INDEX idx_ano (ano),
    UNIQUE INDEX idx_isbn (isbn)
);

-- Inserir dados de exemplo na tabela livros
INSERT INTO livros (titulo, autor, ano, isbn) VALUES
('Dom Casmurro', 'Machado de Assis', 1899, '978-85-359-0277-5'),
('O Cortiço', 'Aluísio Azevedo', 1890, '978-85-359-0276-8'),
('Iracema', 'José de Alencar', 1865, '978-85-359-0275-1'),
('O Guarani', 'José de Alencar', 1857, '978-85-359-0274-4'),
('Senhora', 'José de Alencar', 1875, '978-85-359-0273-7');
