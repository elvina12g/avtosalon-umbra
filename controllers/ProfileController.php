<?php
class ProfileController {
    
    public function index() {
        if(!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            return;
        }
        
        // Подключаемся к базе данных
        require_once 'config/database.php';
        require_once 'models/User.php';
        require_once 'models/TestDrive.php';
        require_once 'models/Car.php';
        
        // Создаем подключение к БД
        $database = new Database();
        $db = $database->getConnection();
        
        // Передаем подключение в модели
        $user = new User($db);
        $testDrive = new TestDrive($db);
        $carModel = new Car($db);
        
        // Получаем данные пользователя
        $userData = $user->getUserById($_SESSION['user_id']);
        
        // Получаем историю тест-драйвов
        $testDrives = $testDrive->getUserTestDrives($_SESSION['user_id']);
        
        // Получаем избранные автомобили
        $favorites = $user->getFavorites($_SESSION['user_id']);
        
        $pageTitle = 'Личный кабинет';
        require_once 'views/layouts/header.php';
        require_once 'views/profile/index.php';
        require_once 'views/layouts/footer.php';
    }
    
    public function update() {
        if(!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Location: /profile');
            return;
        }
        
        require_once 'config/database.php';
        require_once 'models/User.php';
        
        $database = new Database();
        $db = $database->getConnection();
        $user = new User($db);
        
        // Обновляем данные пользователя
        $userData = [
            'full_name' => $_POST['name'],
            'phone' => $_POST['phone'],
            'email' => $_SESSION['user_email'],
            'role' => $_SESSION['user_role'],
            'is_active' => 1
        ];
        
        if($user->updateUser($_SESSION['user_id'], $userData)) {
            $_SESSION['user_name'] = $_POST['name'];
            $_SESSION['success'] = 'Профиль успешно обновлен';
        } else {
            $_SESSION['error'] = 'Ошибка при обновлении профиля';
        }
        
        header('Location: /profile');
    }
    
    public function favorites() {
        if(!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            return;
        }
        
        require_once 'config/database.php';
        require_once 'models/User.php';
        require_once 'models/Car.php';
        
        $database = new Database();
        $db = $database->getConnection();
        $user = new User($db);
        
        $favorites = $user->getFavorites($_SESSION['user_id']);
        
        $pageTitle = 'Избранное';
        require_once 'views/layouts/header.php';
        require_once 'views/profile/favorites.php';
        require_once 'views/layouts/footer.php';
    }
    
    public function testdrives() {
        if(!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            return;
        }
        
        require_once 'config/database.php';
        require_once 'models/TestDrive.php';
        
        $database = new Database();
        $db = $database->getConnection();
        $testDrive = new TestDrive($db);
        
        $testDrives = $testDrive->getUserTestDrives($_SESSION['user_id']);
        
        $pageTitle = 'Мои тест-драйвы';
        require_once 'views/layouts/header.php';
        require_once 'views/profile/testdrives.php';
        require_once 'views/layouts/footer.php';
    }
    
    public function service() {
        if(!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            return;
        }
        
        $pageTitle = 'Запись на сервис';
        require_once 'views/layouts/header.php';
        require_once 'views/profile/service.php';
        require_once 'views/layouts/footer.php';
    }
    
    public function tradein() {
        if(!isset($_SESSION['user_id'])) {
            // Показываем специальную страницу с предложением войти/зарегистрироваться
            $pageTitle = 'Trade-in - Требуется авторизация';
            require_once 'views/layouts/header.php';
            require_once 'views/profile/tradein-guest.php';
            require_once 'views/layouts/footer.php';
            return;
        }
        
        $pageTitle = 'Trade-in';
        require_once 'views/layouts/header.php';
        require_once 'views/profile/tradein.php';
        require_once 'views/layouts/footer.php';
    }
}
?>