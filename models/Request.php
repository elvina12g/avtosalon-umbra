<?php
class Request {
    private $conn;
    private $table = "requests";
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Получить все заявки
    public function getAll($limit = null, $offset = null) {
        $query = "SELECT r.*, u.name as assigned_to_name 
                  FROM " . $this->table . " r
                  LEFT JOIN users u ON r.assigned_to = u.id
                  ORDER BY r.created_at DESC";
        
        if($limit) {
            $query .= " LIMIT :limit";
            if($offset) {
                $query .= " OFFSET :offset";
            }
        }
        
        $stmt = $this->conn->prepare($query);
        
        if($limit) {
            $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
            if($offset) {
                $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
            }
        }
        
        $stmt->execute();
        return $stmt;
    }
    
    // Получить заявки по статусу
    public function getByStatus($status) {
        $query = "SELECT * FROM " . $this->table . " WHERE status = :status ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $status);
        $stmt->execute();
        return $stmt;
    }
    
    // Получить заявки по типу
    public function getByType($type) {
        $query = "SELECT * FROM " . $this->table . " WHERE type = :type ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":type", $type);
        $stmt->execute();
        return $stmt;
    }
    
    // Получить заявки по исполнителю
    public function getByAssignedTo($user_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE assigned_to = :user_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        return $stmt;
    }
    
    // Получить заявку по ID
    public function getById($id) {
        $query = "SELECT r.*, u.name as assigned_to_name 
                  FROM " . $this->table . " r
                  LEFT JOIN users u ON r.assigned_to = u.id
                  WHERE r.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Создать заявку
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (type, user_id, car_id, name, phone, email, date, time, message, status, assigned_to, comment) 
                  VALUES 
                  (:type, :user_id, :car_id, :name, :phone, :email, :date, :time, :message, :status, :assigned_to, :comment)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":type", $data['type']);
        $stmt->bindParam(":user_id", $data['user_id']);
        $stmt->bindParam(":car_id", $data['car_id']);
        $stmt->bindParam(":name", $data['name']);
        $stmt->bindParam(":phone", $data['phone']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":date", $data['date']);
        $stmt->bindParam(":time", $data['time']);
        $stmt->bindParam(":message", $data['message']);
        $stmt->bindParam(":status", $data['status']);
        $stmt->bindParam(":assigned_to", $data['assigned_to']);
        $stmt->bindParam(":comment", $data['comment']);
        
        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }
    
    // Обновить заявку
    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET status = :status, 
                      assigned_to = :assigned_to, 
                      comment = :comment,
                      updated_at = NOW()";
        
        if(isset($data['date'])) {
            $query .= ", date = :date";
        }
        if(isset($data['time'])) {
            $query .= ", time = :time";
        }
        
        $query .= " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":status", $data['status']);
        $stmt->bindParam(":assigned_to", $data['assigned_to']);
        $stmt->bindParam(":comment", $data['comment']);
        $stmt->bindParam(":id", $id);
        
        if(isset($data['date'])) {
            $stmt->bindParam(":date", $data['date']);
        }
        if(isset($data['time'])) {
            $stmt->bindParam(":time", $data['time']);
        }
        
        return $stmt->execute();
    }
    
    // Удалить заявку
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        
        return $stmt->execute();
    }
    
    // Получить количество по статусу
    public function getCountByStatus($status) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE status = :status";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $status);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
    }
    
    // Получить количество по типу
    public function getCountByType($type) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE type = :type";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":type", $type);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
    }
    
    // Получить общее количество
    public function getTotal() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
    
    // Получить типы заявок
    public function getTypes() {
        return ['test_drive', 'service', 'trade_in', 'showroom'];
    }
    
    // Получить статусы
    public function getStatuses() {
        return ['new', 'in_progress', 'completed', 'cancelled'];
    }
    
    // Получить текст статуса
    public function getStatusText($status) {
        $statuses = [
            'new' => 'Новая',
            'in_progress' => 'В работе',
            'completed' => 'Выполнена',
            'cancelled' => 'Отменена'
        ];
        return $statuses[$status] ?? $status;
    }
    
    // Получить текст типа
    public function getTypeText($type) {
        $types = [
            'test_drive' => 'Тест-драйв',
            'service' => 'Сервисное обслуживание',
            'trade_in' => 'Trade-in',
            'showroom' => 'Визит в шоу-рум'
        ];
        return $types[$type] ?? $type;
    }
    
    // Отправка уведомления сотрудникам
    public function notifyStaff($request_id, $request_data) {
        try {
            // Определяем, кому отправлять в зависимости от типа заявки
            $role = 'manager'; // По умолчанию менеджер
            
            if ($request_data['type'] == 'service') {
                $role = 'consultant';
            }
            
            // Получаем сотрудников с нужной ролью
            $query = "SELECT id, email, phone FROM users WHERE role = :role OR role = 'admin'";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':role', $role);
            $stmt->execute();
            $staff = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Формируем сообщение
            $typeText = $this->getTypeText($request_data['type']);
            $message = "Новая заявка #{$request_id}\n";
            $message .= "Тип: {$typeText}\n";
            $message .= "Клиент: {$request_data['name']}\n";
            $message .= "Телефон: {$request_data['phone']}\n";
            $message .= "Email: {$request_data['email']}\n";
            if (!empty($request_data['date'])) {
                $message .= "Дата: {$request_data['date']} {$request_data['time']}\n";
            }
            if (!empty($request_data['message'])) {
                $message .= "Сообщение: {$request_data['message']}\n";
            }
            $message .= "\nСсылка: /admin/requests?id={$request_id}";
            
            // Сохраняем уведомление в БД
            foreach ($staff as $employee) {
                $notifyQuery = "INSERT INTO notifications (user_id, request_id, message, created_at) 
                                VALUES (:user_id, :request_id, :message, NOW())";
                $notifyStmt = $this->conn->prepare($notifyQuery);
                $notifyStmt->bindParam(':user_id', $employee['id']);
                $notifyStmt->bindParam(':request_id', $request_id);
                $notifyStmt->bindParam(':message', $message);
                $notifyStmt->execute();
            }
            
            return true;
        } catch(PDOException $e) {
            error_log("Notification error: " . $e->getMessage());
            return false;
        }
    }
}
?>