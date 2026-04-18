<?php
class Car {
    private $conn;
    private $table = "cars";
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Получить все автомобили с фильтрацией
    public function getAll($filters = []) {
        $query = "SELECT * FROM " . $this->table . " WHERE 1=1";
        $params = [];
        
        // Фильтр по бренду
        if(!empty($filters['brand'])) {
            $query .= " AND brand = :brand";
            $params[':brand'] = $filters['brand'];
        }
        
        // Фильтр по цене
        if(!empty($filters['min_price'])) {
            $query .= " AND price >= :min_price";
            $params[':min_price'] = $filters['min_price'];
        }
        if(!empty($filters['max_price'])) {
            $query .= " AND price <= :max_price";
            $params[':max_price'] = $filters['max_price'];
        }
        
        // Фильтр по году
        if(!empty($filters['year'])) {
            $query .= " AND year = :year";
            $params[':year'] = $filters['year'];
        }
        
        $query .= " ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        foreach($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Получить популярные автомобили для главной
    public function getPopular($limit = 4) {
        $query = "SELECT * FROM " . $this->table . " 
                  ORDER BY created_at DESC 
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Получить автомобиль по ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Создать автомобиль
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (brand, model, year, price, mileage, engine, power, transmission, drive, color, description, image, status, is_popular) 
                  VALUES 
                  (:brand, :model, :year, :price, :mileage, :engine, :power, :transmission, :drive, :color, :description, :image, :status, :is_popular)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":brand", $data['brand']);
        $stmt->bindParam(":model", $data['model']);
        $stmt->bindParam(":year", $data['year']);
        $stmt->bindParam(":price", $data['price']);
        $stmt->bindParam(":mileage", $data['mileage'] ?? 0);
        $stmt->bindParam(":engine", $data['engine'] ?? '');
        $stmt->bindParam(":power", $data['power'] ?? 0);
        $stmt->bindParam(":transmission", $data['transmission'] ?? 'automatic');
        $stmt->bindParam(":drive", $data['drive'] ?? 'rear');
        $stmt->bindParam(":color", $data['color'] ?? '');
        $stmt->bindParam(":description", $data['description'] ?? '');
        $stmt->bindParam(":image", $data['image'] ?? '');
        $stmt->bindParam(":status", $data['status'] ?? 'available');
        $stmt->bindParam(":is_popular", $data['is_popular'] ?? 0);
        
        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }
    
    // Обновить автомобиль
    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET brand = :brand, 
                      model = :model, 
                      year = :year, 
                      price = :price, 
                      mileage = :mileage, 
                      engine = :engine, 
                      power = :power,
                      transmission = :transmission, 
                      drive = :drive,
                      color = :color, 
                      description = :description, 
                      status = :status, 
                      is_popular = :is_popular";
        
        if(isset($data['image']) && !empty($data['image'])) {
            $query .= ", image = :image";
        }
        
        $query .= " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":brand", $data['brand']);
        $stmt->bindParam(":model", $data['model']);
        $stmt->bindParam(":year", $data['year']);
        $stmt->bindParam(":price", $data['price']);
        $stmt->bindParam(":mileage", $data['mileage'] ?? 0);
        $stmt->bindParam(":engine", $data['engine'] ?? '');
        $stmt->bindParam(":power", $data['power'] ?? 0);
        $stmt->bindParam(":transmission", $data['transmission'] ?? 'automatic');
        $stmt->bindParam(":drive", $data['drive'] ?? 'rear');
        $stmt->bindParam(":color", $data['color'] ?? '');
        $stmt->bindParam(":description", $data['description'] ?? '');
        $stmt->bindParam(":status", $data['status'] ?? 'available');
        $stmt->bindParam(":is_popular", $data['is_popular'] ?? 0);
        $stmt->bindParam(":id", $id);
        
        if(isset($data['image']) && !empty($data['image'])) {
            $stmt->bindParam(":image", $data['image']);
        }
        
        return $stmt->execute();
    }
    
    // Удалить автомобиль
    public function delete($id) {
        // Сначала получаем изображение для удаления
        $car = $this->getById($id);
        if($car && !empty($car['image'])) {
            $imagePath = 'uploads/cars/' . $car['image'];
            if(file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        
        return $stmt->execute();
    }
    
    // Получить общее количество
    public function getTotal() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
    
    // Получить уникальные бренды
    public function getBrands() {
        $query = "SELECT DISTINCT brand FROM " . $this->table . " ORDER BY brand";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    // Добавить в избранное (исправлено: таблица favorites)
    public function addToFavorites($user_id, $car_id) {
        // Проверяем, существует ли таблица favorites
        try {
            $checkQuery = "SELECT id FROM favorites WHERE user_id = :user_id AND car_id = :car_id";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(":user_id", $user_id);
            $checkStmt->bindParam(":car_id", $car_id);
            $checkStmt->execute();
            
            if($checkStmt->rowCount() > 0) {
                return true; // Уже в избранном
            }
            
            $query = "INSERT INTO favorites (user_id, car_id, created_at) VALUES (:user_id, :car_id, NOW())";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":user_id", $user_id);
            $stmt->bindParam(":car_id", $car_id);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            // Если таблицы favorites нет, создаем её
            $this->createFavoritesTable();
            return $this->addToFavorites($user_id, $car_id);
        }
    }
    
    // Удалить из избранного
    public function removeFromFavorites($user_id, $car_id) {
        $query = "DELETE FROM favorites WHERE user_id = :user_id AND car_id = :car_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":car_id", $car_id);
        
        return $stmt->execute();
    }
    
    // Получить избранные автомобили пользователя
    public function getUserFavorites($user_id) {
        $query = "SELECT c.* FROM " . $this->table . " c 
                  INNER JOIN favorites f ON c.id = f.car_id 
                  WHERE f.user_id = :user_id 
                  ORDER BY f.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Проверить, в избранном ли автомобиль
    public function isFavorite($user_id, $car_id) {
        $query = "SELECT id FROM favorites WHERE user_id = :user_id AND car_id = :car_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":car_id", $car_id);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
    
    // Создать таблицу избранного если нет
    private function createFavoritesTable() {
        $query = "CREATE TABLE IF NOT EXISTS `favorites` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `user_id` INT(11) NOT NULL,
            `car_id` INT(11) NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `unique_favorite` (`user_id`, `car_id`),
            FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`car_id`) REFERENCES `cars`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        $this->conn->exec($query);
    }
}
?>