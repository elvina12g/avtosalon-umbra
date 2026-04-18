<?php
class HomeController {
    
    public function index() {
        // Подключаемся к базе данных
        require_once 'config/Database.php';
        $database = new Database();
        $db = $database->getConnection();
        
        // Получаем популярные автомобили
        require_once 'models/Car.php';
        $carModel = new Car($db);
        $popularCars = $carModel->getPopular(4);
        
        // Данные для главной страницы
        $pageTitle = 'Автомобильный салон Umbra - Премиальные автомобили';
        $metaDescription = 'Официальный дилер премиальных автомобилей. Mercedes-Benz, BMW, Audi, Porsche. Эксклюзивное обслуживание.';
        
        // Подключаем представление
        require_once 'views/layouts/header.php';
        require_once 'views/home/index.php';
        require_once 'views/layouts/footer.php';
    }
    
    public function about() {
        $pageTitle = 'О компании Umbra';
        require_once 'views/layouts/header.php';
        require_once 'views/home/about.php';
        require_once 'views/layouts/footer.php';
    }
    
    public function contacts() {
        $pageTitle = 'Контакты';
        require_once 'views/layouts/header.php';
        require_once 'views/home/contacts.php';
        require_once 'views/layouts/footer.php';
    }
    
    public function news() {
        $pageTitle = 'Новости и блог';
        require_once 'views/layouts/header.php';
        require_once 'views/home/news.php';
        require_once 'views/layouts/footer.php';
    }
    
    public function reviews() {
        $pageTitle = 'Отзывы клиентов';
        require_once 'views/layouts/header.php';
        require_once 'views/home/reviews.php';
        require_once 'views/layouts/footer.php';
    }
    
    public function submitReview() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Обработка отправки отзыва
            if(isset($_SESSION['user_id'])) {
                // Сохраняем отзыв в БД
                $_SESSION['success'] = 'Спасибо за ваш отзыв!';
            } else {
                $_SESSION['error'] = 'Необходимо авторизоваться';
            }
        }
        header('Location: /reviews');
    }

    public function privacy() {
        $pageTitle = 'Политика конфиденциальности';
        $metaDescription = 'Политика конфиденциальности автосалона Umbra. Условия обработки персональных данных.';
        
        require_once 'views/layouts/header.php';
        require_once 'views/home/privacy.php';
        require_once 'views/layouts/footer.php';
    }

    public function terms() {
        $pageTitle = 'Пользовательское соглашение';
        $metaDescription = 'Пользовательское соглашение автосалона Umbra. Правила использования сайта.';
        
        require_once 'views/layouts/header.php';
        require_once 'views/home/terms.php';
        require_once 'views/layouts/footer.php';
    }
}
?>