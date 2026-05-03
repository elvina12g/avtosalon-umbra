<?php
// controllers/NewsController.php
class NewsController {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
        $this->ensureTableExists();
    }
    
    // Проверка и создание таблицы новостей
    private function ensureTableExists() {
        try {
            $this->db->query("SELECT 1 FROM news LIMIT 1");
        } catch(PDOException $e) {
            $this->createTable();
            $this->addDemoNews();
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
        
        $this->db->exec($query);
    }
    
    // Добавление демо-новостей
    private function addDemoNews() {
        $demoNews = [
            [
                'title' => 'Новый Mercedes-Benz S-Class 2024 уже в салоне',
                'content' => '<p>Представляем флагманский седан Mercedes-Benz S-Class нового поколения. Инновационные технологии, непревзойденный комфорт и элегантный дизайн.</p><p>Новый S-Class устанавливает новые стандарты в сегменте люкс-автомобилей. Полностью цифровой салон, система MBUX с искусственным интеллектом, автопилот 3 уровня — все это доступно уже сегодня.</p><p>Приглашаем вас на тест-драйв этого великолепного автомобиля. Наши менеджеры ответят на все вопросы и помогут подобрать идеальную комплектацию.</p>',
                'excerpt' => 'Флагманский седан Mercedes-Benz S-Class нового поколения уже доступен для тест-драйва. Узнайте о всех инновациях и преимуществах модели.',
                'category' => 'news',
                'image' => 'mercedes-s-class.jpg'
            ],
            [
                'title' => 'Закрытый показ BMW 7 Series',
                'content' => '<p>Приглашаем вас на эксклюзивный закрытый показ нового BMW 7 Series. Мероприятие состоится 25 марта в 19:00 в нашем шоу-руме.</p><p>В программе: дегустация премиальных напитков, живая музыка, презентация автомобиля от главного инженера BMW Russia, а также возможность первыми увидеть автомобиль и записаться на тест-драйв.</p><p>Количество мест ограничено. Обязательна предварительная регистрация по телефону +7 (495) 123-45-67.</p>',
                'excerpt' => 'Эксклюзивное мероприятие для ценителей BMW. Первыми увидите новый 7 Series, пообщаетесь с инженерами и насладитесь вечером в атмосфере роскоши.',
                'category' => 'events',
                'image' => 'bmw-7series.jpg'
            ],
            [
                'title' => 'Как подготовить автомобиль к весне: советы экспертов',
                'content' => '<p>Весна — ответственное время для автовладельцев. После зимы автомобиль требует особого внимания. Наши эксперты подготовили подробный чек-лист подготовки:</p><ul><li>Смена шин — важнейший этап. Рекомендуем менять резину при устойчивой температуре +5°C.</li><li>Мойка кузова и салона — удалите реагенты и соль, обработайте кузов защитным воском.</li><li>Проверка подвески — после зимних ям обязательно проверьте состояние амортизаторов и сайлентблоков.</li><li>Замена масла — если вы не меняли масло перед зимой, сейчас самое время.</li><li>Кондиционер — проведите диагностику и заправку системы.</li></ul><p>Запишитесь на комплексное обслуживание в наш сервисный центр и получите скидку 15% на весеннюю подготовку!</p>',
                'excerpt' => 'Советы экспертов Umbra по подготовке вашего премиального автомобиля к весеннему сезону: шины, кузов, салон и техническое обслуживание.',
                'category' => 'articles',
                'image' => 'spring-prep.jpg'
            ],
            [
                'title' => 'Porsche Panamera: обновленная версия с гибридной установкой',
                'content' => '<p>Porsche представил обновленный Panamera с новой гибридной установкой. Мощность комбинированной системы достигает 680 л.с., а запас хода на электротяге — до 90 км.</p><p>Новый Panamera получил обновленный дизайн передней части, новые матричные фары HD Matrix LED и полностью переработанный салон с увеличенным количеством цифровых экранов.</p><p>Разгон до 100 км/ч занимает всего 3.3 секунды, а максимальная скорость ограничена электроникой на отметке 315 км/ч.</p><p>Первые автомобили появятся в нашем салоне уже в апреле. Принимаем предварительные заказы!</p>',
                'excerpt' => 'Обновленный Porsche Panamera с гибридной установкой. Мощность 680 л.с., разгон до 100 км/ч за 3.3 секунды. Принимаем предзаказы.',
                'category' => 'reviews',
                'image' => 'porsche-panamera.jpg'
            ]
        ];
        
        foreach($demoNews as $news) {
            $slug = $this->generateSlug($news['title']);
            $query = "INSERT INTO news (title, slug, content, excerpt, category, image) 
                      VALUES (:title, :slug, :content, :excerpt, :category, :image)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':title' => $news['title'],
                ':slug' => $slug,
                ':content' => $news['content'],
                ':excerpt' => $news['excerpt'],
                ':category' => $news['category'],
                ':image' => $news['image']
            ]);
        }
    }
    
    // Генерация URL-слага
    private function generateSlug($string) {
        $string = preg_replace('/[^a-zA-Z0-9а-яА-Я\-]/u', '-', $string);
        $string = strtolower(trim($string, '-'));
        $string = preg_replace('/-+/', '-', $string);
        return $string;
    }
    
    // Получить все новости
    public function getAll($limit = null, $offset = null) {
        $query = "SELECT * FROM news ORDER BY created_at DESC";
        if($limit) {
            $query .= " LIMIT :limit";
            if($offset !== null) {
                $query .= " OFFSET :offset";
            }
        }
        
        $stmt = $this->db->prepare($query);
        if($limit) {
            $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
            if($offset !== null) {
                $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
            }
        }
        $stmt->execute();
        
        return $stmt;
    }
    
    // Получить общее количество новостей
    public function getTotalCount() {
        $query = "SELECT COUNT(*) as total FROM news";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
    
    // Получить новости по категории
    public function getByCategory($category, $limit = null, $offset = null) {
        $query = "SELECT * FROM news WHERE category = :category ORDER BY created_at DESC";
        if($limit) {
            $query .= " LIMIT :limit";
            if($offset !== null) {
                $query .= " OFFSET :offset";
            }
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":category", $category, PDO::PARAM_STR);
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
        $query = "SELECT COUNT(*) as total FROM news WHERE category = :category";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":category", $category, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
    
    // Получить категории с количеством новостей
    public function getCategoriesWithCount() {
        $query = "SELECT category, COUNT(*) as count FROM news GROUP BY category ORDER BY count DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Получить популярные новости
    public function getPopular($limit = 5) {
        $query = "SELECT * FROM news ORDER BY views DESC, created_at DESC LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Просмотр конкретной новости
    public function view($id) {
        $query = "SELECT * FROM news WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $news = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(!$news) {
            header("HTTP/1.0 404 Not Found");
            require_once 'views/errors/404.php';
            return;
        }
        
        // Увеличиваем счетчик просмотров
        $query = "UPDATE news SET views = views + 1 WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $pageTitle = $news['title'] . ' - Umbra Premium Auto';
        $metaDescription = strip_tags(mb_substr($news['excerpt'] ?: $news['content'], 0, 160));
        
        require_once 'views/layouts/header.php';
        require_once 'views/news/detail.php';
        require_once 'views/layouts/footer.php';
    }
    
    // Создать новость (для админки)
    public function create($data) {
        $slug = $this->generateSlug($data['title']);
        
        // Проверяем уникальность слага
        $checkQuery = "SELECT id FROM news WHERE slug = :slug";
        $checkStmt = $this->db->prepare($checkQuery);
        $checkStmt->bindValue(":slug", $slug);
        $checkStmt->execute();
        
        if($checkStmt->rowCount() > 0) {
            $slug = $slug . '-' . time();
        }
        
        $query = "INSERT INTO news (title, slug, content, excerpt, image, category) 
                  VALUES (:title, :slug, :content, :excerpt, :image, :category)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":title", $data['title']);
        $stmt->bindValue(":slug", $slug);
        $stmt->bindValue(":content", $data['content']);
        $stmt->bindValue(":excerpt", $data['excerpt'] ?? '');
        $stmt->bindValue(":image", $data['image'] ?? null);
        $stmt->bindValue(":category", $data['category'] ?? 'news');
        
        return $stmt->execute();
    }
    
    // Обновить новость
    public function update($id, $data) {
        $query = "UPDATE news SET 
                  title = :title,
                  content = :content,
                  excerpt = :excerpt,
                  category = :category";
        
        if(isset($data['image']) && !empty($data['image'])) {
            $query .= ", image = :image";
        }
        
        $query .= " WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":title", $data['title']);
        $stmt->bindValue(":content", $data['content']);
        $stmt->bindValue(":excerpt", $data['excerpt'] ?? '');
        $stmt->bindValue(":category", $data['category'] ?? 'news');
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        
        if(isset($data['image']) && !empty($data['image'])) {
            $stmt->bindValue(":image", $data['image']);
        }
        
        return $stmt->execute();
    }
    
    // Удалить новость
    public function delete($id) {
        // Получаем изображение для удаления
        $query = "SELECT image FROM news WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $news = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Удаляем файл изображения
        if($news && !empty($news['image'])) {
            $imagePath = 'uploads/news/' . $news['image'];
            if(file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        $query = "DELETE FROM news WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    // Получить статистику
    public function getStats() {
        $stats = [];
        
        // Общее количество
        $query = "SELECT COUNT(*) as total FROM news";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $stats['total'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // По категориям
        $query = "SELECT category, COUNT(*) as count FROM news GROUP BY category";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $stats['by_category'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Самая просматриваемая
        $query = "SELECT title, views FROM news ORDER BY views DESC LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $stats['most_viewed'] = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $stats;
    }
}
?>