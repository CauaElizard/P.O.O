<?php
/**
 * Classe LivroRepository
 * 
 * Responsável por todas as operações de persistência dos livros.
 * Implementa os métodos CRUD (Create, Read, Update, Delete).
 */
require_once __DIR__ . '/../config/Database.php';

class LivroRepository
{
    private PDO $conexao;

    /**
     * Construtor do repositório
     * 
     * @param Database $database Instância da classe Database
     */
    public function __construct(Database $database)
    {
        $this->conexao = $database->getConexao();
    }

    /**
     * Adiciona um novo livro ao banco de dados
     * 
     * @param Livro $livro Objeto livro a ser adicionado
     * @return bool True se adicionado com sucesso, false caso contrário
     */
    public function adicionar(Livro $livro): bool
    {
        try {
            $sql = "INSERT INTO livros (titulo, autor, ano, isbn) VALUES (:titulo, :autor, :ano, :isbn)";
            $stmt = $this->conexao->prepare($sql);
            
            return $stmt->execute([
                ':titulo' => $livro->getTitulo(),
                ':autor' => $livro->getAutor(),
                ':ano' => $livro->getAno(),
                ':isbn' => $livro->getIsbn()
            ]);
            
        } catch (PDOException $e) {
            error_log("Erro ao adicionar livro: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lista todos os livros do banco de dados
     * 
     * @return array Array de objetos Livro
     */
    public function listarTodos(): array
    {
        try {
            $sql = "SELECT * FROM livros ORDER BY titulo ASC";
            $stmt = $this->conexao->query($sql);
            $livros = [];
            
            while ($row = $stmt->fetch()) {
                $livros[] = new Livro(
                    $row['id'],
                    $row['titulo'],
                    $row['autor'],
                    $row['ano'],
                    $row['isbn']
                );
            }
            
            return $livros;
            
        } catch (PDOException $e) {
            error_log("Erro ao listar livros: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Busca um livro específico pelo ID
     * 
     * @param int $id ID do livro
     * @return Livro|null Objeto Livro ou null se não encontrado
     */
    public function buscarPorId(int $id): ?Livro
    {
        try {
            $sql = "SELECT * FROM livros WHERE id = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([':id' => $id]);
            
            $row = $stmt->fetch();
            
            if ($row) {
                return new Livro(
                    $row['id'],
                    $row['titulo'],
                    $row['autor'],
                    $row['ano'],
                    $row['isbn']
                );
            }
            
            return null;
            
        } catch (PDOException $e) {
            error_log("Erro ao buscar livro: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Edita um livro existente
     * 
     * @param Livro $livro Objeto livro com dados atualizados
     * @return bool True se editado com sucesso, false caso contrário
     */
    public function editar(Livro $livro): bool
    {
        try {
            $sql = "UPDATE livros SET titulo = :titulo, autor = :autor, ano = :ano, isbn = :isbn WHERE id = :id";
            $stmt = $this->conexao->prepare($sql);
            
            return $stmt->execute([
                ':id' => $livro->getId(),
                ':titulo' => $livro->getTitulo(),
                ':autor' => $livro->getAutor(),
                ':ano' => $livro->getAno(),
                ':isbn' => $livro->getIsbn()
            ]);
            
        } catch (PDOException $e) {
            error_log("Erro ao editar livro: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Exclui um livro do banco de dados
     * 
     * @param int $id ID do livro a ser excluído
     * @return bool True se excluído com sucesso, false caso contrário
     */
    public function excluir(int $id): bool
    {
        try {
            $sql = "DELETE FROM livros WHERE id = :id";
            $stmt = $this->conexao->prepare($sql);
            
            return $stmt->execute([':id' => $id]);
            
        } catch (PDOException $e) {
            error_log("Erro ao excluir livro: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca livros por título (busca parcial)
     * 
     * @param string $titulo Título ou parte do título
     * @return array Array de objetos Livro
     */
    public function buscarPorTitulo(string $titulo): array
    {
        try {
            $sql = "SELECT * FROM livros WHERE titulo LIKE :titulo ORDER BY titulo ASC";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([':titulo' => '%' . $titulo . '%']);
            
            $livros = [];
            while ($row = $stmt->fetch()) {
                $livros[] = new Livro(
                    $row['id'],
                    $row['titulo'],
                    $row['autor'],
                    $row['ano'],
                    $row['isbn']
                );
            }
            
            return $livros;
            
        } catch (PDOException $e) {
            error_log("Erro ao buscar livros por título: " . $e->getMessage());
            return [];
        }
    }
}
?>
