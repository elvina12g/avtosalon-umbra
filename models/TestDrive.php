<?php
require_once 'config/database.php';

class TestDrive {
    private $conn;
    private $table = 'test_drives';

    public $id;
    public $user_id;
    public $car_id;
    public $date;
    public $time;
    public $status;
    public $created_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (user_id, car_id, date, time, status) 
                  VALUES (:user_id, :car_id, :date, :time, 'pending')";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':car_id', $this->car_id);
        $stmt->bindParam(':date', $this->date);
        $stmt->bindParam(':time', $this->time);
        
        return $stmt->execute();
    }

    public function getUserTestDrives($user_id) {
        $query = "SELECT td.*, c.brand, c.model, c.image 
                  FROM " . $this->table . " td
                  JOIN cars c ON td.car_id = c.id
                  WHERE td.user_id = :user_id
                  ORDER BY td.date DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>