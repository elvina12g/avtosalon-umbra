<?php
class News {
    private $conn;
    private $table = "news";
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Получить все новости
    public function getAll($limit = null, $offset = null) {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        
        if($limit) {
            $query .= " LIMIT :limit";
            if($offset !== null) {
                $query .= " OFFSET :offset";
            }
        }
        
        $stmt = $this->conn->prepare($query);
        
        if($limit) {
            $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
            if($offset !== null) {
                $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
            }
        }
        
        $stmt->execute();
        return $stmt;
    }
    
    // Получить новость по ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Создать новость
    public function create($data) {
        $slug = $this->generateSlug($data['title']);
        
        // Проверяем уникальность слага
        $checkQuery = "SELECT id FROM " . $this->table . " WHERE slug = :slug";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(":slug", $slug);
        $checkStmt->execute();
        
        if($checkStmt->rowCount() > 0) {
            $slug = $slug . '-' . time();
        }
        
        $query = "INSERT INTO " . $this->table . " 
                  (title, slug, content, excerpt, image, category, created_at) 
                  VALUES 
                  (:title, :slug, :content, :excerpt, :image, :category, NOW())";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":title", $data['title']);
        $stmt->bindParam(":slug", $slug);
        $stmt->bindParam(":content", $data['content']);
        $stmt->bindParam(":excerpt", $data['excerpt'] ?? '');
        $stmt->bindParam(":image", $data['image'] ?? null);
        $stmt->bindParam(":category", $data['category'] ?? 'news');
        
        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }
    
    // Обновить новость
    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET title = :title, 
                      content = :content, 
                      excerpt = :excerpt, 
                      category = :category";
        
        if(isset($data['image']) && !empty($data['image'])) {
            $query .= ", image = :image";
        }
        
        $query .= " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":title", $data['title']);
        $stmt->bindParam(":content", $data['content']);
        $stmt->bindParam(":excerpt", $data['excerpt'] ?? '');
        $stmt->bindParam(":category", $data['category'] ?? 'news');
        $stmt->bindParam(":id", $id);
        
        if(isset($data['image']) && !empty($data['image'])) {
            $stmt->bindParam(":image", $data['image']);
        }
        
        return $stmt->execute();
    }
    
    // Удалить новость
    public function delete($id) {
        // Получаем изображение для удаления
        $news = $this->getById($id);
        if($news && !empty($news['image'])) {
            $imagePath = 'uploads/news/' . $news['image'];
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
    
    // Получить новости по категории
    public function getByCategory($category, $limit = null, $offset = null) {
        $query = "SELECT * FROM " . $this->table . " WHERE category = :category ORDER BY created_at DESC";
        
        if($limit) {
            $query .= " LIMIT :limit";
            if($offset !== null) {
                $query .= " OFFSET :offset";
            }
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":category", $category);
        
        if($limit) {
            $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
            if($offset !== null) {
                $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
            }
        }
        
        $stmt->execute();
        return $stmt;
    }
    
    // Получить количество новостей по категории
    public function getCountByCategory($category) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE category = :category";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":category", $category);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
    
    // Получить категории с количеством новостей
    public function getCategoriesWithCount() {
        $query = "SELECT category, COUNT(*) as count FROM " . $this->table . " GROUP BY category ORDER BY count DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Получить популярные новости
    public function getPopular($limit = 5) {
        $query = "SELECT * FROM " . $this->table . " ORDER BY views DESC, created_at DESC LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Увеличить счетчик просмотров
    public function incrementViews($id) {
        $query = "UPDATE " . $this->table . " SET views = views + 1 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
    
    // Получить статистику
    public function getStats() {
        $stats = [];
        
        // Общее количество
        $stats['total'] = $this->getTotal();
        
        // По категориям
        $stats['by_category'] = $this->getCategoriesWithCount();
        
        // Самая просматриваемая
        $query = "SELECT title, views FROM " . $this->table . " ORDER BY views DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['most_viewed'] = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $stats;
    }
    
    // Генерация URL-слага
    private function generateSlug($string) {
        $string = preg_replace('/[^a-zA-Z0-9а-яА-Я\-]/u', '-', $string);
        $string = strtolower(trim($string, '-'));
        $string = preg_replace('/-+/', '-', $string);
        return $string;
    }
    
    // Проверка и создание таблицы новостей
    public function ensureTableExists() {
        try {
            $this->conn->query("SELECT 1 FROM " . $this->table . " LIMIT 1");
        } catch(PDOException $e) {
            $this->createTable();
        }
    }
    
    // Создание таблицы новостей
    private function createTable() {
        $query = "CREATE TABLE IF NOT EXISTS `news` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `title` VARCHAR(255) NOT NULL,
            `slug` VARCHAR(255),
            `content` TEXT NOT NULL,
            `excerpt` TEXT,
            `image` VARCHAR(255),
            `category` ENUM('news', 'events', 'articles', 'reviews') DEFAULT 'news',
            `views` INT(11) DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `slug` (`slug`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        $this->conn->exec($query);
        
        // Добавляем демо-новости если таблица пустая
        $this->addDemoNews();
    }
    
    // Добавление демо-новостей
    private function addDemoNews() {
        $query = "SELECT COUNT(*) as count FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        if($count == 0) {
            $demoNews = [
                [
                    'title' => 'Новый Mercedes-Benz S-Class 2024 уже в салоне',
                    'content' => '<p>Представляем флагманский седан Mercedes-Benz S-Class нового поколения. Инновационные технологии, непревзойденный комфорт и элегантный дизайн.</p><p>Новый S-Class устанавливает новые стандарты в сегменте люкс-автомобилей.</p>',
                    'excerpt' => 'Флагманский седан Mercedes-Benz S-Class нового поколения уже доступен для тест-драйва.',
                    'category' => 'news',
                    'image' => null
                ],
                [
                    'title' => 'Закрытый показ BMW 7 Series',
                    'content' => '<p>Приглашаем вас на эксклюзивный закрытый показ нового BMW 7 Series.</p>',
                    'excerpt' => 'Эксклюзивное мероприятие для ценителей BMW.',
                    'category' => 'events',
                    'image' => null
                ]
            ];
            
            foreach($demoNews as $news) {
                $this->create($news);
            }
        }
    }
}
?>