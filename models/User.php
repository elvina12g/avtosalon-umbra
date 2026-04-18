<?php
class User {
    private $conn;
    private $table = "users";
    
    public $id;
    public $username;
    public $email;
    public $full_name;
    public $phone;
    public $role;
    public $is_active;
    public $last_login;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Авторизация пользователя
    public function login($username, $password) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE (username = :username OR email = :username) 
                  AND is_active = 1 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if(password_verify($password, $row['password'])) {
                $this->id = $row['id'];
                $this->username = $row['username'];
                $this->email = $row['email'];
                $this->full_name = $row['full_name'] ?? ($row['name'] ?? $row['username']);
                $this->phone = $row['phone'] ?? '';
                $this->role = $row['role'] ?? 'user';
                $this->is_active = $row['is_active'] ?? 1;
                
                $this->updateLastLogin();
                return true;
            }
        }
        return false;
    }
    
    // Обновление времени последнего входа
    private function updateLastLogin() {
        // Проверяем, существует ли столбец last_login
        try {
            $query = "UPDATE " . $this->table . " 
                      SET last_login = NOW() 
                      WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $this->id);
            return $stmt->execute();
        } catch(PDOException $e) {
            // Если столбца нет, просто игнорируем ошибку
            return true;
        }
    }
    
    // Получить всех пользователей
    public function getAllUsers($role = null) {
        $query = "SELECT * FROM " . $this->table;
        if($role) {
            $query .= " WHERE role = :role";
        }
        $query .= " ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        if($role) {
            $stmt->bindParam(":role", $role);
        }
        $stmt->execute();
        
        return $stmt;
    }
    
    // Получить пользователя по ID
    public function getUserById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Создать пользователя
    public function createUser($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (username, email, password, full_name, phone, role, is_active) 
                  VALUES 
                  (:username, :email, :password, :full_name, :phone, :role, :is_active)";
        
        $stmt = $this->conn->prepare($query);
        
        $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
        $is_active = isset($data['is_active']) ? $data['is_active'] : 1;
        $role = isset($data['role']) ? $data['role'] : 'user';
        
        $stmt->bindParam(":username", $data['username']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":password", $hashed_password);
        $stmt->bindParam(":full_name", $data['full_name']);
        $stmt->bindParam(":phone", $data['phone']);
        $stmt->bindParam(":role", $role);
        $stmt->bindParam(":is_active", $is_active);
        
        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }
    
    // Обновить пользователя
    public function updateUser($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET full_name = :full_name, 
                      email = :email, 
                      phone = :phone, 
                      role = :role,
                      is_active = :is_active";
        
        if(isset($data['password']) && !empty($data['password'])) {
            $query .= ", password = :password";
        }
        
        $query .= " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":full_name", $data['full_name']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":phone", $data['phone']);
        $stmt->bindParam(":role", $data['role']);
        $stmt->bindParam(":is_active", $data['is_active']);
        $stmt->bindParam(":id", $id);
        
        if(isset($data['password']) && !empty($data['password'])) {
            $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
            $stmt->bindParam(":password", $hashed_password);
        }
        
        return $stmt->execute();
    }
    
    // Удалить пользователя
    public function deleteUser($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        
        return $stmt->execute();
    }
    
    // Получить пользователей по роли
    public function getUsersByRole($role) {
        $query = "SELECT * FROM " . $this->table . " WHERE role = :role AND is_active = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":role", $role);
        $stmt->execute();
        
        return $stmt;
    }
    
    // Получить всех активных пользователей
    public function getActiveUsers() {
        $query = "SELECT * FROM " . $this->table . " WHERE is_active = 1 ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
    // Логирование активности
    public function logActivity($action, $details = null) {
        // Проверяем существование таблицы activity_logs
        try {
            $query = "INSERT INTO activity_logs (user_id, action, details, ip_address) 
                      VALUES (:user_id, :action, :details, :ip_address)";
            
            $stmt = $this->conn->prepare($query);
            
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            
            $stmt->bindParam(":user_id", $this->id);
            $stmt->bindParam(":action", $action);
            $stmt->bindParam(":details", $details);
            $stmt->bindParam(":ip_address", $ip);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            // Если таблицы нет, просто игнорируем
            return true;
        }
    }
    
    // Получить избранные автомобили пользователя
    public function getFavorites($user_id) {
        $query = "SELECT c.* FROM cars c
                  INNER JOIN favorites f ON c.id = f.car_id
                  WHERE f.user_id = :user_id
                  ORDER BY f.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>