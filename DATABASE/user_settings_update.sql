USE cadastro_oo;

-- Tentar criar os índices diretamente (o MySQL ignora caso já existam)
CREATE INDEX idx_usuarios_email ON usuarios(email);
CREATE INDEX idx_usuarios_criado_em ON usuarios(criado_em);

-- Verificar a estrutura da tabela usuarios
DESCRIBE usuarios;
