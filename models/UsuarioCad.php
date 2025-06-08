<?php
class Usuario {
    private $conn;
    private $tabela = "usuarios";

    public $nome;
    public $email;
    public $senha;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function cadastrar() {
        $sql = "INSERT INTO " . $this->tabela . " (nome, email, senha) VALUES (:nome, :email, :senha)";
        $stmt = $this->conn->prepare($sql);

        $this->senha = password_hash($this->senha, PASSWORD_DEFAULT);

        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":senha", $this->senha);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>
