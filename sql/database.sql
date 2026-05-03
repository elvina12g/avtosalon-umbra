-- Создание базы данных
CREATE DATABASE IF NOT EXISTS umbra_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE umbra_db;

-- Таблица пользователей
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Таблица автомобилей
CREATE TABLE cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    brand VARCHAR(50) NOT NULL,
    model VARCHAR(100) NOT NULL,
    year INT,
    price DECIMAL(15,2) NOT NULL,
    mileage INT,
    engine VARCHAR(50),
    power INT,
    transmission ENUM('automatic', 'manual', 'robot') DEFAULT 'automatic',
    drive ENUM('front', 'rear', 'all') DEFAULT 'rear',
    color VARCHAR(50),
    description TEXT,
    image VARCHAR(255),
    images TEXT, -- JSON массив дополнительных изображений
    is_popular BOOLEAN DEFAULT FALSE,
    is_new BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_brand (brand),
    INDEX idx_price (price)
);

-- Таблица тест-драйвов
CREATE TABLE test_drives (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    car_id INT,
    date DATE NOT NULL,
    time TIME NOT NULL,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    INDEX idx_date (date)
);

-- Таблица сервисных записей
CREATE TABLE service_appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    car_brand VARCHAR(50),
    car_model VARCHAR(100),
    car_year INT,
    service_type ENUM('to', 'repair', 'diagnostics', 'other') DEFAULT 'to',
    date DATE NOT NULL,
    time TIME NOT NULL,
    description TEXT,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user (user_id)
);

-- Таблица заявок Trade-in
CREATE TABLE trade_in_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    car_brand VARCHAR(50) NOT NULL,
    car_model VARCHAR(100) NOT NULL,
    car_year INT NOT NULL,
    car_mileage INT,
    car_condition TEXT,
    estimated_price DECIMAL(15,2),
    photos TEXT, -- JSON массив путей к фото
    status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Таблица избранного
CREATE TABLE favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    car_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE,
    UNIQUE KEY unique_favorite (user_id, car_id)
);

-- Таблица отзывов
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    rating TINYINT CHECK (rating >= 1 AND rating <= 5),
    text TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_status (status)
);

-- Таблица новостей/блога
CREATE TABLE news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    excerpt TEXT,
    content TEXT NOT NULL,
    image VARCHAR(255),
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_created (created_at)
);

-- Таблица подписчиков
CREATE TABLE subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Добавление тестовых данных
INSERT INTO users (name, email, password, phone, role) VALUES
('Администратор', 'admin@umbra.ru', '$2y$10$YourHashedPasswordHere', '+7 (495) 123-45-67', 'admin'),
('Иван Петров', 'ivan@example.com', '$2y$10$YourHashedPasswordHere', '+7 (999) 123-45-67', 'user');

INSERT INTO cars (brand, model, year, price, mileage, engine, power, transmission, drive, color, description, image, is_popular) VALUES
('Mercedes-Benz', 'S-Class', 2024, 15000000, 0, 'V6 3.0', 367, 'automatic', 'rear', 'Черный', 'Флагманский седан Mercedes-Benz S-Class в новом кузове', 'mercedes-s-class.jpg', 1),
('BMW', '7 Series', 2024, 14000000, 0, 'V8 4.4', 530, 'automatic', 'all', 'Синий', 'Роскошный седан BMW 7 Series с новым дизайном', 'bmw-7series.jpg', 1),
('Audi', 'A8', 2024, 13500000, 0, 'V6 3.0', 340, 'automatic', 'all', 'Серый', 'Инновационный Audi A8 с гибридной установкой', 'audi-a8.jpg', 1),
('Porsche', 'Panamera', 2024, 16000000, 0, 'V8 4.0', 630, 'automatic', 'all', 'Белый', 'Спортивный лифтбек Porsche Panamera Turbo', 'porsche-panamera.jpg', 1);

INSERT INTO reviews (user_id, rating, text, status) VALUES
(2, 5, 'Превосходный салон! Очень внимательный персонал, помогли с выбором автомобиля.', 'approved'),
(2, 5, 'Отличный сервис. Делал ТО своего BMW X5, все быстро и качественно.', 'approved');

INSERT INTO news (title, slug, excerpt, content) VALUES
('Новый Mercedes-Benz S-Class 2024', 'new-mercedes-s-class-2024', 'Презентация обновленного флагманского седана', 'Полное описание новинки...'),
('Как подготовить автомобиль к зиме', 'winter-car-preparation', 'Советы по подготовке автомобиля к зимнему сезону', 'Содержание статьи...');