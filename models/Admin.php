<?php
class Admin {
    private $conn;
    private $table = "users";
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Получить статистику для дашборда
    public function getDashboardStats() {
        $stats = [];
        
        // Общее количество пользователей
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['total_users'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Количество пользователей по ролям
        $query = "SELECT role, COUNT(*) as count FROM " . $this->table . " GROUP BY role";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['users_by_role'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $stats;
    }
    
    // Получить все логи активности
    public function getActivityLogs($limit = 50) {
        $query = "SELECT al.*, u.username, u.full_name 
                  FROM activity_logs al
                  LEFT JOIN users u ON al.user_id = u.id
                  ORDER BY al.created_at DESC
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Очистить логи активности
    public function clearActivityLogs($days = 30) {
        $query = "DELETE FROM activity_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL :days DAY)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":days", $days, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    // Создать резервную копию БД
    public function backupDatabase() {
        $tables = [];
        $result = $this->conn->query("SHOW TABLES");
        
        while($row = $result->fetch(PDO::FETCH_NUM)) {
            $tables[] = $row[0];
        }
        
        $backup = "";
        
        foreach($tables as $table) {
            $result = $this->conn->query("SELECT * FROM " . $table);
            $num_fields = $result->columnCount();
            
            $backup .= "DROP TABLE IF EXISTS " . $table . ";\n";
            
            $row2 = $this->conn->query("SHOW CREATE TABLE " . $table)->fetch(PDO::FETCH_NUM);
            $backup .= $row2[1] . ";\n\n";
            
            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $backup .= "INSERT INTO " . $table . " VALUES(";
                $values = [];
                foreach($row as $value) {
                    $values[] = "'" . addslashes($value) . "'";
                }
                $backup .= implode(",", $values) . ");\n";
            }
            $backup .= "\n\n";
        }
        
        $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $filepath = 'backups/' . $filename;
        
        if(!is_dir('backups')) {
            mkdir('backups', 0777, true);
        }
        
        file_put_contents($filepath, $backup);
        
        return $filepath;
    }
    
    // Получить информацию о системе
    public function getSystemInfo() {
        $info = [
            'php_version' => phpversion(),
            'mysql_version' => $this->conn->getAttribute(PDO::ATTR_SERVER_VERSION),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'max_execution_time' => ini_get('max_execution_time'),
            'memory_limit' => ini_get('memory_limit')
        ];
        
        return $info;
    }
    
    // Очистить кэш
    public function clearCache() {
        $cache_dirs = ['cache', 'temp', 'uploads/temp'];
        $cleared = [];
        
        foreach($cache_dirs as $dir) {
            if(is_dir($dir)) {
                $files = glob($dir . '/*');
                foreach($files as $file) {
                    if(is_file($file)) {
                        unlink($file);
                    }
                }
                $cleared[] = $dir;
            }
        }
        
        return $cleared;
    }
    
    // Получить настройки сайта
    public function getSettings() {
        // Можно создать отдельную таблицу settings
        $default_settings = [
            'site_name' => 'Автосалон Премиум',
            'site_email' => 'info@avtosalon.ru',
            'site_phone' => '+7 (999) 123-45-67',
            'site_address' => 'г. Москва, ул. Автомобильная, д. 1',
            'work_hours' => 'Пн-Пт: 9:00-20:00, Сб-Вс: 10:00-18:00',
            'maintenance_mode' => '0',
            'social_vk' => '',
            'social_tg' => '',
            'social_inst' => ''
        ];
        
        // Проверяем, есть ли таблица settings
        try {
            $query = "SELECT * FROM settings WHERE id = 1";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            if($stmt->rowCount() > 0) {
                $settings = $stmt->fetch(PDO::FETCH_ASSOC);
                unset($settings['id']);
                return $settings;
            }
        } catch(PDOException $e) {
            // Таблица settings не существует
        }
        
        return $default_settings;
    }
    
    // Сохранить настройки сайта
    public function saveSettings($data) {
        // Проверяем существование таблицы settings
        try {
            $this->conn->query("SELECT 1 FROM settings LIMIT 1");
        } catch(PDOException $e) {
            // Создаем таблицу settings
            $this->createSettingsTable();
        }
        
        // Проверяем, есть ли запись
        $query = "SELECT id FROM settings LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            // Обновляем существующую запись
            $fields = [];
            $values = [];
            foreach($data as $key => $value) {
                $fields[] = "`$key` = :$key";
                $values[":$key"] = $value;
            }
            
            $query = "UPDATE settings SET " . implode(", ", $fields) . " WHERE id = 1";
            $stmt = $this->conn->prepare($query);
            
            foreach($values as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            return $stmt->execute();
        } else {
            // Создаем новую запись
            $fields = array_keys($data);
            $placeholders = array_map(function($field) { return ":" . $field; }, $fields);
            
            $query = "INSERT INTO settings (" . implode(", ", $fields) . ") 
                      VALUES (" . implode(", ", $placeholders) . ")";
            $stmt = $this->conn->prepare($query);
            
            foreach($data as $key => $value) {
                $stmt->bindValue(":" . $key, $value);
            }
            
            return $stmt->execute();
        }
    }
    
    // Создать таблицу настроек
    private function createSettingsTable() {
        $query = "CREATE TABLE IF NOT EXISTS `settings` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `site_name` VARCHAR(255) DEFAULT 'Автосалон Премиум',
            `site_email` VARCHAR(100) DEFAULT NULL,
            `site_phone` VARCHAR(50) DEFAULT NULL,
            `site_address` TEXT,
            `work_hours` VARCHAR(255) DEFAULT NULL,
            `maintenance_mode` TINYINT(1) DEFAULT 0,
            `social_vk` VARCHAR(255) DEFAULT NULL,
            `social_tg` VARCHAR(255) DEFAULT NULL,
            `social_inst` VARCHAR(255) DEFAULT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        $this->conn->exec($query);
        
        // Вставляем начальные настройки
        $query = "INSERT INTO settings (site_name, site_email, site_phone, site_address, work_hours) 
                  VALUES ('Автосалон Премиум', 'info@avtosalon.ru', '+7 (999) 123-45-67', 'г. Москва, ул. Автомобильная, д. 1', 'Пн-Пт: 9:00-20:00, Сб-Вс: 10:00-18:00')";
        $this->conn->exec($query);
    }
    
    // Получить все заявки для отчета
    public function getRequestsReport($start_date = null, $end_date = null) {
        $query = "SELECT 
                    type,
                    status,
                    COUNT(*) as count,
                    DATE(created_at) as date
                  FROM requests";
        
        $conditions = [];
        $params = [];
        
        if($start_date) {
            $conditions[] = "DATE(created_at) >= :start_date";
            $params[':start_date'] = $start_date;
        }
        
        if($end_date) {
            $conditions[] = "DATE(created_at) <= :end_date";
            $params[':end_date'] = $end_date;
        }
        
        if(!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
        
        $query .= " GROUP BY type, status, DATE(created_at) 
                    ORDER BY date DESC";
        
        $stmt = $this->conn->prepare($query);
        
        foreach($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Получить активность пользователей
    public function getUserActivity($user_id = null) {
        $query = "SELECT 
                    u.id,
                    u.username,
                    u.full_name,
                    u.role,
                    COUNT(al.id) as activity_count,
                    MAX(al.created_at) as last_activity
                  FROM users u
                  LEFT JOIN activity_logs al ON u.id = al.user_id";
        
        $conditions = [];
        $params = [];
        
        if($user_id) {
            $conditions[] = "u.id = :user_id";
            $params[':user_id'] = $user_id;
        }
        
        if(!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
        
        $query .= " GROUP BY u.id, u.username, u.full_name, u.role
                    ORDER BY last_activity DESC";
        
        $stmt = $this->conn->prepare($query);
        
        foreach($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>