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
    private DateTime $dataPublicacao;
    private string $isbn;

    /**
     * Construtor da classe Livro
     * 
     * @param int|null $id ID do livro (null para novos livros)
     * @param string $titulo Título do livro
     * @param string $autor Autor do livro
     * @param DateTime|string $dataPublicacao Data de publicação (DateTime ou string no formato Y-m-d)
     * @param string $isbn ISBN do livro
     */
    public function __construct(?int $id, string $titulo, string $autor, $dataPublicacao, string $isbn)
    {
        $this->id = $id;
        $this->setTitulo($titulo);
        $this->setAutor($autor);
        $this->setDataPublicacao($dataPublicacao);
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

    public function getDataPublicacao(): DateTime
    {
        return $this->dataPublicacao;
    }

    /**
     * Retorna a data de publicação formatada
     * 
     * @param string $formato Formato da data (padrão: d/m/Y)
     * @return string Data formatada
     */
    public function getDataPublicacaoFormatada(string $formato = 'd/m/Y'): string
    {
        return $this->dataPublicacao->format($formato);
    }

    /**
     * Retorna a data de publicação no formato do banco (Y-m-d)
     * 
     * @return string Data no formato Y-m-d
     */
    public function getDataPublicacaoBanco(): string
    {
        return $this->dataPublicacao->format('Y-m-d');
    }

    /**
     * Retorna apenas o ano da publicação
     * 
     * @return int Ano da publicação
     */
    public function getAnoPublicacao(): int
    {
        return (int)$this->dataPublicacao->format('Y');
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

    /**
     * Define a data de publicação
     * 
     * @param DateTime|string $dataPublicacao Data como DateTime ou string no formato Y-m-d
     * @throws InvalidArgumentException Se a data for inválida
     */
    public function setDataPublicacao($dataPublicacao): void
    {
        if ($dataPublicacao instanceof DateTime) {
            $this->dataPublicacao = $dataPublicacao;
        } elseif (is_string($dataPublicacao)) {
            try {
                $this->dataPublicacao = new DateTime($dataPublicacao);
            } catch (Exception $e) {
                throw new InvalidArgumentException("Data de publicação inválida. Use o formato YYYY-MM-DD.");
            }
        } else {
            throw new InvalidArgumentException("Data de publicação deve ser uma string ou objeto DateTime.");
        }

        // Validar se a data não é futura
        $hoje = new DateTime();
        if ($this->dataPublicacao > $hoje) {
            throw new InvalidArgumentException("Data de publicação não pode ser futura.");
        }

        // Validar se a data não é muito antiga (antes do ano 1000)
        $anoMinimo = new DateTime('1000-01-01');
        if ($this->dataPublicacao < $anoMinimo) {
            throw new InvalidArgumentException("Data de publicação deve ser posterior ao ano 1000.");
        }
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
            'data_publicacao' => $this->getDataPublicacaoBanco(),
            'data_publicacao_formatada' => $this->getDataPublicacaoFormatada(),
            'ano_publicacao' => $this->getAnoPublicacao(),
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
            "Livro: %s | Autor: %s | Data: %s | ISBN: %s",
            $this->titulo,
            $this->autor,
            $this->getDataPublicacaoFormatada(),
            $this->isbn
        );
    }
}
?>
