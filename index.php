<?php
// Безопасный запуск сессии - только здесь!
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Включаем отображение ошибок для отладки
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ========== ПОДКЛЮЧЕНИЕ К БАЗЕ ДАННЫХ ==========
// Параметры подключения к БД
define('DB_HOST', 'localhost');
define('DB_NAME', 'umbra_db');  // замени на имя твоей БД
define('DB_USER', 'root');        // пользователь MySQL
define('DB_PASS', '');            // пароль (у XAMPP/WAMP обычно пустой)

// Создаем подключение к БД
try {
    $db = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch(PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}
// ========== КОНЕЦ ПОДКЛЮЧЕНИЯ К БД ==========

// Автозагрузка классов
spl_autoload_register(function ($class) {
    $paths = [
        'controllers/',
        'models/',
        'config/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Получаем URL
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';

// ========== МАРШРУТЫ АДМИН-ПАНЕЛИ ==========
// Проверяем, начинается ли URL с admin
if (strpos($url, 'admin') === 0) {
    // Разбираем URL админ-панели
    $urlParts = explode('/', $url);
    
    // Определяем подстраницу админ-панели
    $admin_page = isset($urlParts[1]) ? $urlParts[1] : 'dashboard';
    
    // Подключаем контроллер админ-панели и передаем БД
    require_once 'controllers/AdminController.php';
    $admin = new AdminController($db);
    
    // Маршрутизация внутри админ-панели
    switch($admin_page) {
        case 'dashboard':
            $admin->dashboard();
            break;
        case 'users':
            $admin->users();
            break;
        case 'cars':
            $admin->cars();
            break;
        case 'requests':
            $admin->requests();
            break;
        case 'settings':
            $admin->settings();
            break;
        case 'news':
            $admin->news();
            break;
        default:
            // Если страница не найдена - 404
            header("HTTP/1.0 404 Not Found");
            require_once 'views/errors/404.php';
            exit();
    }
    exit();
}
// ========== КОНЕЦ МАРШРУТОВ АДМИН-ПАНЕЛИ ==========

// ========== МАРШРУТЫ АУТЕНТИФИКАЦИИ ==========
if (strpos($url, 'auth') === 0) {
    $urlParts = explode('/', $url);
    $auth_action = isset($urlParts[1]) ? $urlParts[1] : 'login';
    
    require_once 'controllers/AuthController.php';
    $auth = new AuthController($db);
    
    switch($auth_action) {
        case 'login':
            $auth->login();
            break;
        case 'register':
            $auth->register();
            break;
        case 'logout':
            $auth->logout();
            break;
        default:
            header("HTTP/1.0 404 Not Found");
            require_once 'views/errors/404.php';
            exit();
    }
    exit();
}
// ========== КОНЕЦ МАРШРУТОВ АУТЕНТИФИКАЦИИ ==========

// ========== ОБРАБОТКА КОНТАКТНОЙ ФОРМЫ ==========
// Обработка контактной формы (ДОБАВЛЕННЫЙ МАРШРУТ)
if ($url == 'submit-contact') {
    require_once 'models/Request.php';
    
    // Валидация
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    
    if (empty($name) || empty($phone)) {
        $_SESSION['error'] = 'Пожалуйста, заполните имя и телефон';
        header('Location: /');
        exit();
    }
    
    $request = new Request($db);
    
    // Формируем сообщение
    $message = trim($_POST['message'] ?? '');
    
    $request_data = [
        'type' => $_POST['request_type'] ?? 'showroom',
        'user_id' => $_SESSION['user_id'] ?? null,
        'car_id' => null,
        'name' => $name,
        'phone' => $phone,
        'email' => trim($_POST['email'] ?? ''),
        'date' => null,
        'time' => null,
        'message' => $message,
        'status' => 'new',
        'assigned_to' => null,
        'comment' => null
    ];
    
    $request_id = $request->create($request_data);
    
    if ($request_id) {
        // Отправляем уведомление сотрудникам
        $request->notifyStaff($request_id, $request_data);
        $_SESSION['success'] = 'Заявка успешно отправлена! Наш менеджер свяжется с вами.';
    } else {
        $_SESSION['error'] = 'Ошибка при отправке заявки. Пожалуйста, попробуйте позже или позвоните нам.';
    }
    
    header('Location: /');
    exit();
}
// ========== КОНЕЦ ОБРАБОТКИ КОНТАКТНОЙ ФОРМЫ ==========

// ========== МАРШРУТЫ НОВОСТЕЙ ==========
// Маршруты для новостей (пользовательская часть)
if (strpos($url, 'news') === 0) {
    $urlParts = explode('/', $url);
    $news_action = isset($urlParts[1]) ? $urlParts[1] : 'index';
    
    require_once 'controllers/NewsController.php';
    $newsController = new NewsController($db);
    
    if($news_action == 'index' || empty($news_action)) {
        // Страница со списком новостей
        require_once 'views/home/news.php';
        exit();
    } elseif(is_numeric($news_action)) {
        // Просмотр конкретной новости
        $newsController->view($news_action);
        exit();
    }
}

// Маршруты админ-панели для новостей
if (strpos($url, 'admin/news') === 0) {
    require_once 'views/admin/news.php';
    exit();
}
// ========== КОНЕЦ МАРШРУТОВ НОВОСТЕЙ ==========

// Разбираем URL
$urlParts = explode('/', $url);

// СПЕЦИАЛЬНЫЕ ПРАВИЛА ДЛЯ РАЗНЫХ СТРАНИЦ
$page = $urlParts[0] ?? '';

// ЕСЛИ ЭТО ГЛАВНАЯ СТРАНИЦА СЕРВИСА (просто /services)
if ($page === 'services' && !isset($urlParts[1])) {
    $controllerName = 'ServiceController';
    $methodName = 'index';
    $params = [];
}
// Если это страницы "services" с дополнительными сегментами (services/leasing, services/insurance и т.д.)
elseif ($page === 'services' && isset($urlParts[1]) && !empty($urlParts[1])) {
    $controllerName = 'ServiceController';
    $methodName = $urlParts[1];
    $params = array_slice($urlParts, 2);
}
// Если это одна из страниц (about, news, contacts, reviews, privacy, terms)
elseif (in_array($page, ['about', 'news', 'contacts', 'reviews', 'privacy', 'terms'])) {
    $controllerName = 'HomeController';
    $methodName = $page;
    $params = array_slice($urlParts, 1);
}
// Для остальных URL - стандартная логика
else {
    $controllerName = !empty($page) ? ucfirst($page) . 'Controller' : 'HomeController';
    $methodName = isset($urlParts[1]) && !empty($urlParts[1]) ? $urlParts[1] : 'index';
    $params = array_slice($urlParts, 2);
}

// Путь к файлу контроллера
$controllerFile = 'controllers/' . $controllerName . '.php';

// Проверяем существование файла контроллера
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    
    if (class_exists($controllerName)) {
        // ПЕРЕДАЕМ ПОДКЛЮЧЕНИЕ К БД В КОНСТРУКТОР КОНТРОЛЛЕРА
        $controller = new $controllerName($db);
        
        if (method_exists($controller, $methodName)) {
            // Вызываем метод с параметрами
            call_user_func_array([$controller, $methodName], $params);
        } else {
            // Метод не найден - показываем 404
            header("HTTP/1.0 404 Not Found");
            require_once 'views/errors/404.php';
        }
    } else {
        // Класс не найден в файле
        header("HTTP/1.0 404 Not Found");
        require_once 'views/errors/404.php';
    }
} else {
    // Контроллер не найден
    header("HTTP/1.0 404 Not Found");
    require_once 'views/errors/404.php';
}
?>