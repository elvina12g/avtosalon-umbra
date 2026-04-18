<?php
// НЕТ session_start() здесь! Сессия уже запущена в index.php

require_once 'config/database.php';
require_once 'models/User.php';
require_once 'models/Car.php';
require_once 'models/Request.php';
require_once 'models/News.php'; // Добавляем модель новостей

class AdminController {
    private $db;
    private $user;
    private $car;
    private $request;
    private $news; // Добавляем свойство для новостей
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
        $this->car = new Car($this->db);
        $this->request = new Request($this->db);
        $this->news = new News($this->db); // Инициализируем модель новостей
        
        // Проверяем авторизацию для всех методов
        if(!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            exit();
        }
    }
    
    public function dashboard() {
        // Получаем статистику
        $stats = [
            'total_cars' => $this->car->getTotal(),
            'new_requests' => $this->request->getCountByStatus('new'),
            'test_drives' => $this->request->getCountByType('test_drive'),
            'service_requests' => $this->request->getCountByType('service')
        ];
        
        // Получаем последние заявки
        $recent_requests = $this->request->getAll(5);
        
        include 'views/admin/dashboard.php';
    }
    
    public function users() {
        // Проверяем права (только админ)
        if($_SESSION['user_role'] != 'admin') {
            header('Location: /admin/dashboard');
            exit();
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(isset($_POST['action'])) {
                switch($_POST['action']) {
                    case 'create':
                        $this->user->createUser($_POST);
                        $_SESSION['success'] = 'Пользователь успешно создан';
                        break;
                    case 'update':
                        $this->user->updateUser($_POST['id'], $_POST);
                        $_SESSION['success'] = 'Пользователь успешно обновлен';
                        break;
                    case 'delete':
                        $this->user->deleteUser($_POST['id']);
                        $_SESSION['success'] = 'Пользователь удален';
                        break;
                }
                header('Location: /admin/users');
                exit();
            }
        }
        
        $users = $this->user->getAllUsers();
        include 'views/admin/users.php';
    }
    
    public function cars() {
        // Проверяем права (админ и менеджер)
        if(!in_array($_SESSION['user_role'], ['admin', 'manager'])) {
            header('Location: /admin/dashboard');
            exit();
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(isset($_POST['action'])) {
                // Обработка загрузки изображения
                $image_name = null;
                if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                    $upload_dir = 'uploads/cars/';
                    if(!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }
                    $image_name = time() . '_' . basename($_FILES['image']['name']);
                    move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name);
                }
                
                $car_data = $_POST;
                if($image_name) {
                    $car_data['image'] = $image_name;
                }
                
                switch($_POST['action']) {
                    case 'create':
                        $this->car->create($car_data);
                        $_SESSION['success'] = 'Автомобиль успешно добавлен';
                        break;
                    case 'update':
                        $this->car->update($_POST['id'], $car_data);
                        $_SESSION['success'] = 'Автомобиль успешно обновлен';
                        break;
                    case 'delete':
                        $this->car->delete($_POST['id']);
                        $_SESSION['success'] = 'Автомобиль удален';
                        break;
                }
                header('Location: /admin/cars');
                exit();
            }
        }
        
        $cars = $this->car->getAll();
        include 'views/admin/cars.php';
    }
    
    public function requests() {
        $user_role = $_SESSION['user_role'];
        $user_id = $_SESSION['user_id'];
        
        // Получаем заявки в зависимости от роли
        if($user_role == 'admin') {
            $requests = $this->request->getAll();
        } elseif($user_role == 'manager') {
            $requests = $this->request->getByType('test_drive');
        } elseif($user_role == 'consultant') {
            $requests = $this->request->getByType('service');
        } else {
            $requests = $this->request->getAll();
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_status') {
            $this->request->update($_POST['id'], [
                'status' => $_POST['status'],
                'assigned_to' => $_POST['assigned_to'],
                'comment' => $_POST['comment']
            ]);
            $_SESSION['success'] = 'Статус заявки обновлен';
            header('Location: /admin/requests');
            exit();
        }
        
        $users = $this->user->getAllUsers();
        include 'views/admin/requests.php';
    }
    
    public function settings() {
        // Проверяем права (только админ)
        if($_SESSION['user_role'] != 'admin') {
            header('Location: /admin/dashboard');
            exit();
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['success'] = 'Настройки сохранены';
            header('Location: /admin/settings');
            exit();
        }
        
        include 'views/admin/settings.php';
    }
    
    public function news() {
        // Проверяем права (админ и менеджер могут управлять новостями)
        if(!in_array($_SESSION['user_role'], ['admin', 'manager'])) {
            header('Location: /admin/dashboard');
            exit();
        }
        
        // Обработка POST запросов (создание, обновление, удаление)
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(isset($_POST['action'])) {
                // Обработка загрузки изображения для новости
                $image_name = null;
                if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                    $upload_dir = 'uploads/news/';
                    if(!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }
                    $image_name = time() . '_' . basename($_FILES['image']['name']);
                    move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name);
                }
                
                $news_data = $_POST;
                if($image_name) {
                    $news_data['image'] = $image_name;
                }
                
                switch($_POST['action']) {
                    case 'create':
                        $news_data['created_by'] = $_SESSION['user_id'];
                        $this->news->create($news_data);
                        $_SESSION['success'] = 'Новость успешно создана';
                        break;
                    case 'update':
                        $this->news->update($_POST['id'], $news_data);
                        $_SESSION['success'] = 'Новость успешно обновлена';
                        break;
                    case 'delete':
                        $this->news->delete($_POST['id']);
                        $_SESSION['success'] = 'Новость удалена';
                        break;
                }
                header('Location: /admin/news');
                exit();
            }
        }
        
        // Получаем все новости для отображения
        $news = $this->news->getAll();
        include 'views/admin/news.php';
    }
}
?>