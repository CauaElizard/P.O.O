<?php
require_once '../config/database.php';

class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $email;
    public $password;
    public $created_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function updateEmail($user_id, $new_email) {
        $query = "UPDATE " . $this->table_name . " 
                  SET email = :email 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':email', $new_email);
        $stmt->bindParam(':id', $user_id);

        return $stmt->execute();
    }

    public function updatePassword($user_id, $new_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        $query = "UPDATE " . $this->table_name . " 
                  SET password = :password 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':id', $user_id);

        return $stmt->execute();
    }

    public function verifyPassword($user_id, $password) {
        $query = "SELECT password FROM " . $this->table_name . " WHERE id = :id";
        
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
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $user_id);
        
        return $stmt->execute();
    }

    public function getUserById($user_id) {
        $query = "SELECT id, email, created_at FROM " . $this->table_name . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
