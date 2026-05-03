<?php
class CarsController {
    
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function index() {
        // Получаем фильтры из GET параметров
        $filters = [
            'brand' => $_GET['brand'] ?? '',
            'min_price' => $_GET['min_price'] ?? '',
            'max_price' => $_GET['max_price'] ?? '',
            'year' => $_GET['year'] ?? ''
        ];
        
        require_once 'models/Car.php';
        $carModel = new Car($this->db);
        $cars = $carModel->getAll($filters);
        $brands = $carModel->getBrands();
        
        $pageTitle = 'Каталог автомобилей';
        $metaDescription = 'Премиальные автомобили в наличии. Mercedes-Benz, BMW, Audi, Porsche. Выгодные условия покупки.';
        
        require_once 'views/layouts/header.php';
        require_once 'views/cars/catalog.php';
        require_once 'views/layouts/footer.php';
    }
    
    public function detail($id) {
        require_once 'models/Car.php';
        $carModel = new Car($this->db);
        $car = $carModel->getById($id);
        
        if(!$car) {
            header("HTTP/1.0 404 Not Found");
            require_once 'views/errors/404.php';
            return;
        }
        
        $pageTitle = $car['brand'] . ' ' . $car['model'] . ' - характеристики и цена';
        $metaDescription = $car['brand'] . ' ' . $car['model'] . ' ' . $car['year'] . ' года. ' . 
                          'Двигатель: ' . $car['engine'] . ', цена: ' . number_format($car['price'], 0, '.', ' ') . ' ₽';
        
        require_once 'views/layouts/header.php';
        require_once 'views/cars/detail.php';
        require_once 'views/layouts/footer.php';
    }
    
    public function testdrive($car_id = null) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->bookTestDrive();
            return;
        }
        
        require_once 'models/Car.php';
        $carModel = new Car($this->db);
        $cars = $carModel->getAll();
        
        $selectedCar = $car_id ? $carModel->getById($car_id) : null;
        
        $pageTitle = 'Запись на тест-драйв';
        $metaDescription = 'Запишитесь на тест-драйв любого автомобиля из нашего каталога. Профессиональные консультанты помогут с выбором.';
        
        require_once 'views/layouts/header.php';
        require_once 'views/cars/testdrive.php';
        require_once 'views/layouts/footer.php';
    }
    
    private function bookTestDrive() {
        if(!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Необходимо авторизоваться для записи на тест-драйв';
            header('Location: /auth/login');
            return;
        }
        
        require_once 'models/TestDrive.php';
        $testDrive = new TestDrive($this->db);
        
        $testDrive->user_id = $_SESSION['user_id'];
        $testDrive->car_id = $_POST['car_id'];
        $testDrive->date = $_POST['date'];
        $testDrive->time = $_POST['time'];
        
        if($testDrive->create()) {
            $_SESSION['success'] = 'Заявка на тест-драйв успешно отправлена! Менеджер свяжется с вами для подтверждения.';
        } else {
            $_SESSION['error'] = 'Ошибка при отправке заявки. Пожалуйста, попробуйте позже.';
        }
        
        header('Location: /profile');
        exit();
    }
    
    public function favorite($action, $car_id) {
        header('Content-Type: application/json');
        
        if(!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Необходима авторизация']);
            return;
        }
        
        require_once 'models/Car.php';
        $carModel = new Car($this->db);
        
        if($action == 'add') {
            $result = $carModel->addToFavorites($_SESSION['user_id'], $car_id);
        } else if($action == 'remove') {
            $result = $carModel->removeFromFavorites($_SESSION['user_id'], $car_id);
        } else {
            $result = false;
        }
        
        echo json_encode(['success' => $result]);
    }
}
?>