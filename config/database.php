<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'umbra_db';  // ваша БД называется umbra_db
    private $username = 'root';
    private $password = '';
    private $conn;
    
    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->conn->exec("set names utf8mb4");
        } catch(PDOException $e) {
            echo "Ошибка подключения: " . $e->getMessage();
        }
        
        return $this->conn;
    }
    
    // Сохраняем старый метод connect() для обратной совместимости
    public function connect() {
        return $this->getConnection();
    }
    
    // Метод для импорта SQL файла
    public function importSQL($sqlFile) {
        if(file_exists($sqlFile)) {
            $sql = file_get_contents($sqlFile);
            try {
                $this->getConnection()->exec($sql);
                return true;
            } catch(PDOException $e) {
                echo 'Import Error: ' . $e->getMessage();
                return false;
            }
        }
        return false;
    }
}
?>