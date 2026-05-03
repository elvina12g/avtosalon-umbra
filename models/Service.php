<?php
class Service {
    private $conn;
    private $table = "service_components";
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Получить все услуги
    public function getAllServices() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY category, name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Получить услугу по ID
    public function getServiceById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Получить услуги по категории
    public function getServicesByCategory($category) {
        $query = "SELECT * FROM " . $this->table . " WHERE category = :category ORDER BY name";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":category", $category);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Создать услугу
    public function createService($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (name, description, price, category, duration, is_active) 
                  VALUES 
                  (:name, :description, :price, :category, :duration, :is_active)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":name", $data['name']);
        $stmt->bindParam(":description", $data['description']);
        $stmt->bindParam(":price", $data['price']);
        $stmt->bindParam(":category", $data['category']);
        $stmt->bindParam(":duration", $data['duration']);
        $stmt->bindParam(":is_active", $data['is_active']);
        
        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }
    
    // Обновить услугу
    public function updateService($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET name = :name, 
                      description = :description, 
                      price = :price, 
                      category = :category, 
                      duration = :duration, 
                      is_active = :is_active 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":name", $data['name']);
        $stmt->bindParam(":description", $data['description']);
        $stmt->bindParam(":price", $data['price']);
        $stmt->bindParam(":category", $data['category']);
        $stmt->bindParam(":duration", $data['duration']);
        $stmt->bindParam(":is_active", $data['is_active']);
        $stmt->bindParam(":id", $id);
        
        return $stmt->execute();
    }
    
    // Удалить услугу
    public function deleteService($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        
        return $stmt->execute();
    }
    
    // Получить все категории услуг
    public function getCategories() {
        $query = "SELECT DISTINCT category FROM " . $this->table . " ORDER BY category";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    // Получить активные услуги
    public function getActiveServices() {
        $query = "SELECT * FROM " . $this->table . " WHERE is_active = 1 ORDER BY category, name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Рассчитать стоимость обслуживания
    public function calculateServiceCost($service_ids) {
        if(empty($service_ids)) {
            return 0;
        }
        
        $placeholders = str_repeat('?,', count($service_ids) - 1) . '?';
        $query = "SELECT SUM(price) as total FROM " . $this->table . " WHERE id IN ($placeholders) AND is_active = 1";
        
        $stmt = $this->conn->prepare($query);
        
        foreach($service_ids as $key => $id) {
            $stmt->bindValue($key + 1, $id);
        }
        
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['total'] ?? 0;
    }
    
    // Получить услуги для заявки
    public function getServicesForRequest($request_id) {
        $query = "SELECT s.*, rs.quantity, rs.price as service_price 
                  FROM request_services rs
                  JOIN " . $this->table . " s ON rs.service_id = s.id
                  WHERE rs.request_id = :request_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":request_id", $request_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Добавить услуги к заявке
    public function addServicesToRequest($request_id, $services) {
        // Сначала удаляем существующие
        $query = "DELETE FROM request_services WHERE request_id = :request_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":request_id", $request_id);
        $stmt->execute();
        
        // Добавляем новые
        if(!empty($services)) {
            $query = "INSERT INTO request_services (request_id, service_id, quantity, price) 
                      VALUES (:request_id, :service_id, :quantity, :price)";
            $stmt = $this->conn->prepare($query);
            
            foreach($services as $service) {
                $stmt->bindParam(":request_id", $request_id);
                $stmt->bindParam(":service_id", $service['id']);
                $stmt->bindParam(":quantity", $service['quantity']);
                $stmt->bindParam(":price", $service['price']);
                $stmt->execute();
            }
        }
        
        return true;
    }
    
    // Создать таблицу для связи заявок и услуг (если нет)
    public function createRequestServicesTable() {
        $query = "CREATE TABLE IF NOT EXISTS `request_services` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `request_id` INT(11) NOT NULL,
            `service_id` INT(11) NOT NULL,
            `quantity` INT(11) DEFAULT 1,
            `price` DECIMAL(10,2) NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            FOREIGN KEY (`request_id`) REFERENCES `requests`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`service_id`) REFERENCES `service_components`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        $this->conn->exec($query);
    }
    
    // Получить статистику по услугам
    public function getServicesStats() {
        $stats = [];
        
        // Общее количество услуг
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['total_services'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Активные услуги
        $query = "SELECT COUNT(*) as active FROM " . $this->table . " WHERE is_active = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['active_services'] = $stmt->fetch(PDO::FETCH_ASSOC)['active'];
        
        // Категории
        $stats['categories'] = $this->getCategories();
        
        // Самая популярная услуга
        $query = "SELECT s.name, COUNT(rs.service_id) as count 
                  FROM request_services rs
                  JOIN " . $this->table . " s ON rs.service_id = s.id
                  GROUP BY rs.service_id, s.name
                  ORDER BY count DESC
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['most_popular'] = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $stats;
    }
    
    // Создать таблицу service_components если нет
    public function createServiceComponentsTable() {
        $query = "CREATE TABLE IF NOT EXISTS `service_components` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(255) NOT NULL,
            `description` TEXT,
            `price` DECIMAL(10,2) NOT NULL,
            `category` VARCHAR(100) DEFAULT 'general',
            `duration` INT(11) DEFAULT 60,
            `is_active` TINYINT(1) DEFAULT 1,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        $this->conn->exec($query);
        
        // Добавляем тестовые услуги
        $test_services = [
            ['name' => 'Замена масла', 'description' => 'Замена масла и масляного фильтра', 'price' => 3000, 'category' => 'ТО', 'duration' => 60],
            ['name' => 'Диагностика двигателя', 'description' => 'Полная компьютерная диагностика', 'price' => 2000, 'category' => 'Диагностика', 'duration' => 45],
            ['name' => 'Шиномонтаж', 'description' => 'Сезонная смена шин', 'price' => 2500, 'category' => 'Шины', 'duration' => 90],
            ['name' => 'Развал-схождение', 'description' => 'Регулировка углов установки колес', 'price' => 1800, 'category' => 'Подвеска', 'duration' => 60],
            ['name' => 'Замена тормозных колодок', 'description' => 'Замена передних/задних колодок', 'price' => 2500, 'category' => 'Тормозная система', 'duration' => 60]
        ];
        
        $query = "SELECT COUNT(*) as count FROM service_components";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        if($count == 0) {
            $insert_query = "INSERT INTO service_components (name, description, price, category, duration) 
                             VALUES (:name, :description, :price, :category, :duration)";
            $stmt = $this->conn->prepare($insert_query);
            
            foreach($test_services as $service) {
                $stmt->bindParam(":name", $service['name']);
                $stmt->bindParam(":description", $service['description']);
                $stmt->bindParam(":price", $service['price']);
                $stmt->bindParam(":category", $service['category']);
                $stmt->bindParam(":duration", $service['duration']);
                $stmt->execute();
            }
        }
    }
}
?>