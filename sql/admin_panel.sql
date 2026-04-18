-- Создание таблицы пользователей (если её нет)
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `full_name` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(20),
    `role` ENUM('admin', 'manager', 'consultant') NOT NULL DEFAULT 'manager',
    `is_active` TINYINT(1) DEFAULT 1,
    `last_login` DATETIME,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `username` (`username`),
    UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Вставка тестовых пользователей
INSERT INTO `users` (`username`, `email`, `password`, `full_name`, `phone`, `role`) VALUES
('admin', 'admin@avtosalon.ru', '$2y$10$YourHashHere', 'Администратор', '+7 (999) 123-45-67', 'admin'),
('manager', 'manager@avtosalon.ru', '$2y$10$YourHashHere', 'Менеджер по продажам', '+7 (999) 123-45-68', 'manager'),
('consultant', 'consultant@avtosalon.ru', '$2y$10$YourHashHere', 'Сервисный консультант', '+7 (999) 123-45-69', 'consultant');

-- Таблица заявок
CREATE TABLE IF NOT EXISTS `requests` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `type` ENUM('test_drive', 'service', 'trade_in', 'showroom') NOT NULL,
    `user_id` INT(11) NULL,
    `car_id` INT(11) NULL,
    `name` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `email` VARCHAR(100),
    `date` DATE,
    `time` TIME,
    `message` TEXT,
    `status` ENUM('new', 'in_progress', 'completed', 'cancelled') DEFAULT 'new',
    `assigned_to` INT(11) NULL,
    `comment` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`assigned_to`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Таблица автомобилей
CREATE TABLE IF NOT EXISTS `cars` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `brand` VARCHAR(50) NOT NULL,
    `model` VARCHAR(100) NOT NULL,
    `year` INT(4),
    `price` DECIMAL(12,2) NOT NULL,
    `engine` VARCHAR(50),
    `transmission` VARCHAR(50),
    `mileage` INT(11),
    `color` VARCHAR(50),
    `description` TEXT,
    `image` VARCHAR(255),
    `status` ENUM('available', 'reserved', 'sold') DEFAULT 'available',
    `featured` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Таблица для логов активности
CREATE TABLE IF NOT EXISTS `activity_logs` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11),
    `action` VARCHAR(255),
    `details` TEXT,
    `ip_address` VARCHAR(45),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;