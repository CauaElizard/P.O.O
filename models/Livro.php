<?php
/**
 * Classe Livro
 * 
 * Representa um livro no sistema com suas propriedades básicas.
 * Implementa encapsulamento através de métodos getters e setters.
 */
class Livro
{
    private ?int $id;
    private string $titulo;
    private string $autor;
    private int $ano;
    private string $isbn;

    /**
     * Construtor da classe Livro
     * 
     * @param int|null $id ID do livro (null para novos livros)
     * @param string $titulo Título do livro
     * @param string $autor Autor do livro
     * @param int $ano Ano de publicação
     * @param string $isbn ISBN do livro
     */
    public function __construct(?int $id, string $titulo, string $autor, int $ano, string $isbn)
    {
        $this->id = $id;
        $this->setTitulo($titulo);
        $this->setAutor($autor);
        $this->setAno($ano);
        $this->setIsbn($isbn);
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function getAutor(): string
    {
        return $this->autor;
    }

    public function getAno(): int
    {
        return $this->ano;
    }

    public function getIsbn(): string
    {
        return $this->isbn;
    }

    // Setters com validação
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setTitulo(string $titulo): void
    {
        if (empty(trim($titulo))) {
            throw new InvalidArgumentException("Título não pode estar vazio.");
        }
        $this->titulo = trim($titulo);
    }

    public function setAutor(string $autor): void
    {
        if (empty(trim($autor))) {
            throw new InvalidArgumentException("Autor não pode estar vazio.");
        }
        $this->autor = trim($autor);
    }

    public function setAno(int $ano): void
    {
        $anoAtual = (int)date('Y');
        if ($ano < 1000 || $ano > $anoAtual + 1) {
            throw new InvalidArgumentException("Ano deve estar entre 1000 e " . ($anoAtual + 1) . ".");
        }
        $this->ano = $ano;
    }

    public function setIsbn(string $isbn): void
    {
        // Remove hífens e espaços do ISBN para validação
        $isbnLimpo = preg_replace('/[-\s]/', '', $isbn);
        
        if (empty(trim($isbn))) {
            throw new InvalidArgumentException("ISBN não pode estar vazio.");
        }
        
        // Validação básica de ISBN (10 ou 13 dígitos)
        if (!preg_match('/^\d{10}(\d{3})?$/', $isbnLimpo)) {
            throw new InvalidArgumentException("ISBN deve conter 10 ou 13 dígitos.");
        }
        
        $this->isbn = trim($isbn);
    }

    /**
     * Converte o objeto para array
     * 
     * @return array Representação em array do livro
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'autor' => $this->autor,
            'ano' => $this->ano,
            'isbn' => $this->isbn
        ];
    }

    /**
     * Representação em string do livro
     * 
     * @return string Informações do livro formatadas
     */
    public function __toString(): string
    {
        return sprintf(
            "Livro: %s | Autor: %s | Ano: %d | ISBN: %s",
            $this->titulo,
            $this->autor,
            $this->ano,
            $this->isbn
        );
    }
}
?>
