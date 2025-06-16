<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../utils/Sanitizacao.php';

class UserSettings {
    private $conn;
    private $table_name = "usuarios";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function updateEmail($user_id, $new_email) {
        $new_email = Sanitizacao::limparEmail($new_email);

        $checkQuery = "SELECT id FROM " . $this->table_name . " WHERE email = :email AND id != :user_id";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(':email', $new_email);
        $checkStmt->bindParam(':user_id', $user_id);
        $checkStmt->execute();
        
        if ($checkStmt->rowCount() > 0) {
            throw new Exception('Email já está em uso por outro usuário');
        }
        
        $query = "UPDATE " . $this->table_name . " SET email = :email WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':email', $new_email);
        $stmt->bindParam(':id', $user_id);

        return $stmt->execute();
    }

    public function updatePassword($user_id, $new_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        $query = "UPDATE " . $this->table_name . " SET senha = :senha WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':senha', $hashed_password);
        $stmt->bindParam(':id', $user_id);

        return $stmt->execute();
    }

    public function verifyPassword($user_id, $password) {
        $query = "SELECT senha FROM " . $this->table_name . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            return password_verify($password, $row['senha']);
        }
        
        return false;
    }

    public function deleteAccount($user_id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $user_id);
        
        return $stmt->execute();
    }

    public function getUserById($user_id) {
        $query = "SELECT id, nome, email, criado_em FROM " . $this->table_name . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByEmail($email) {
        $query = "SELECT id, nome, email, criado_em FROM " . $this->table_name . " WHERE email = :email";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
