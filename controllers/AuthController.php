<?php
// НЕТ session_start() здесь! Сессия уже запущена в index.php

require_once 'config/database.php';
require_once 'models/User.php';

class AuthController {
    private $user;
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }
    
    // Единая форма входа для всех
    public function login() {
        // Если пользователь уже авторизован
        if(isset($_SESSION['user_id'])) {
            $this->redirectAfterLogin();
            return;
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if(empty($username) || empty($password)) {
                $_SESSION['error'] = 'Пожалуйста, заполните все поля';
                header('Location: /auth/login');
                exit();
            }
            
            if($this->user->login($username, $password)) {
                $_SESSION['user_id'] = $this->user->id;
                $_SESSION['username'] = $this->user->username;
                $_SESSION['user_name'] = $this->user->full_name;
                $_SESSION['user_email'] = $this->user->email;
                $_SESSION['user_role'] = $this->user->role;
                
                // Логируем вход
                $this->user->logActivity('login', 'Пользователь вошел в систему');
                
                $this->redirectAfterLogin();
                exit();
            } else {
                $_SESSION['error'] = 'Неверный логин/email или пароль';
                header('Location: /auth/login');
                exit();
            }
        }
        
        // Показываем форму входа
        require_once 'views/auth/login.php';
    }
    
    // Перенаправление после входа
    private function redirectAfterLogin() {
        switch($_SESSION['user_role']) {
            case 'admin':
                header('Location: /admin/dashboard');
                break;
            case 'manager':
                header('Location: /admin/dashboard');
                break;
            case 'consultant':
                header('Location: /admin/dashboard');
                break;
            default:
                header('Location: /profile');
        }
    }
        
    // Регистрация пользователя
    public function register() {
        if(isset($_SESSION['user_id'])) {
            header('Location: /profile');
            exit();
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            // Валидация
            $errors = [];
            
            if(empty($name)) {
                $errors[] = 'Введите имя';
            }
            
            if(empty($email)) {
                $errors[] = 'Введите email';
            } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Неверный формат email';
            }
            
            if(empty($password)) {
                $errors[] = 'Введите пароль';
            } elseif(strlen($password) < 6) {
                $errors[] = 'Пароль должен быть не менее 6 символов';
            }
            
            if($password !== $confirm_password) {
                $errors[] = 'Пароли не совпадают';
            }
            
            // Проверка существования пользователя
            $existingUser = $this->checkExistingUser($email);
            if($existingUser) {
                $errors[] = 'Пользователь с таким email уже существует';
            }
            
            if(empty($errors)) {
                // Создаем пользователя
                $userData = [
                    'username' => $email,
                    'email' => $email,
                    'password' => $password,
                    'full_name' => $name,
                    'phone' => $phone,
                    'role' => 'user'
                ];
                
                if($this->user->createUser($userData)) {
                    $_SESSION['success'] = 'Регистрация успешна! Теперь вы можете войти.';
                    header('Location: /auth/login');
                    exit();
                } else {
                    $_SESSION['error'] = 'Ошибка при регистрации. Попробуйте позже.';
                }
            } else {
                $_SESSION['error'] = implode('<br>', $errors);
            }
            
            header('Location: /auth/register');
            exit();
        }
        
        require_once 'views/auth/register.php';
    }
    
    // Проверка существующего пользователя
    private function checkExistingUser($email) {
        $query = "SELECT id FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    // Выход из системы
    public function logout() {
        if(isset($_SESSION['user_id'])) {
            $this->user->logActivity('logout', 'Пользователь вышел из системы');
        }
        session_destroy();
        header('Location: /');
        exit();
    }
    
    // Проверка авторизации
    public function checkAuth() {
        if(!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            exit();
        }
        return true;
    }
    
    // Проверка роли
    public function checkRole($allowed_roles = []) {
        $this->checkAuth();
        
        if(!empty($allowed_roles) && !in_array($_SESSION['user_role'], $allowed_roles)) {
            header('Location: /');
            exit();
        }
        return true;
    }
}
?>