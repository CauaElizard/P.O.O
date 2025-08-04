<?php
/**
 * Classe Database
 * 
 * Gerencia a conexão com o banco de dados MySQL.
 * Implementa o padrão Singleton para garantir uma única instância de conexão.
 */
class Database
{
    private static ?PDO $conexao = null;

    private string $host = 'localhost';
    private string $dbname = 'sistema_completo';
    private string $username = 'root';
    private string $password = '&tec77@info!';

    /**
     * Construtor da classe Database
     * Estabelece a conexão com o banco de dados, se ainda não existir
     */
    public function __construct()
    {
        if (self::$conexao === null) {
            $this->conectar();
        }
    }

    /**
     * Estabelece a conexão com o banco de dados usando PDO
     * 
     * @throws PDOException Se ocorrer erro na conexão
     */
    private function conectar(): void
    {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
            self::$conexao = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        } catch (PDOException $e) {
            // Em ambiente de produção, prefira registrar o erro ao invés de exibir
            die("Erro na conexão com o banco de dados: " . $e->getMessage());
        }
    }

    /**
     * Retorna a instância da conexão PDO
     * 
     * @return PDO Objeto PDO da conexão ativa
     */
    public function getConexao(): PDO
    {
        return self::$conexao;
    }

    /**
     * Encerra a conexão com o banco de dados
     */
    public function fecharConexao(): void
    {
        self::$conexao = null;
    }
}
?>
