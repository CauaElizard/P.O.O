<?php
require_once 'UsuarioLogin.php';
require_once '../config/Database.php';

class UsuarioDAO {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function buscarPorEmail($email) {
        $query = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function validarLogin($email, $senha) {
        $usuario = $this->buscarPorEmail($email);
        if ($usuario && password_verify($senha, $usuario['senha_hash'])) {
            return new UsuarioLogin($usuario);
        }
        return null;
    }

    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    public function createUser($email, $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (email, password) VALUES (:email, :password)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);

        return $stmt->execute();
    }

    public function getUserByEmail($email) {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateEmail($user_id, $new_email) {
        $query = "UPDATE users SET email = :email WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':email', $new_email);
        $stmt->bindParam(':id', $user_id);

        return $stmt->execute();
    }

    public function updatePassword($user_id, $new_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        $query = "UPDATE users SET password = :password WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':id', $user_id);

        return $stmt->execute();
    }

    public function verifyPassword($user_id, $password) {
        $query = "SELECT password FROM users WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            return password_verify($password, $row['password']);
        }
        
        return false;
    }

    public function deleteAccount($user_id) {
        $query = "DELETE FROM users WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $user_id);
        
        return $stmt->execute();
    }

    public function getUserById($user_id) {
        $query = "SELECT id, email, created_at FROM users WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
