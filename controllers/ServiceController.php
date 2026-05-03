<?php
class ServiceController {
    
    public function submitLeasing() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            require_once 'models/Request.php';
            
            global $db;
            $request = new Request($db);
            
            // Формируем сообщение
            $car_brand = $_POST['car_brand'] ?? '';
            $car_model = $_POST['car_model'] ?? '';
            $amount = $_POST['amount'] ?? '';
            $term = $_POST['term'] ?? '';
            
            $message = "Заявка на лизинг\n";
            if ($car_brand || $car_model) {
                $message .= "Автомобиль: {$car_brand} {$car_model}\n";
            }
            if ($amount) {
                $message .= "Сумма: {$amount} ₽\n";
            }
            if ($term) {
                $message .= "Срок: {$term} мес.\n";
            }
            if (!empty($_POST['comment'])) {
                $message .= "Комментарий: " . $_POST['comment'];
            }
            
            $request_data = [
                'type' => 'leasing',
                'user_id' => $_SESSION['user_id'] ?? null,
                'car_id' => null,
                'name' => trim($_POST['name'] ?? $_SESSION['user_name'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'email' => trim($_POST['email'] ?? $_SESSION['user_email'] ?? ''),
                'date' => null,
                'time' => null,
                'message' => $message,
                'status' => 'new',
                'assigned_to' => null,
                'comment' => null
            ];
            
            if (empty($request_data['name']) || empty($request_data['phone'])) {
                $_SESSION['error'] = 'Пожалуйста, заполните имя и телефон';
                header('Location: /services/leasing');
                exit;
            }
            
            $request_id = $request->create($request_data);
            
            if ($request_id) {
                $request->notifyStaff($request_id, $request_data);
                $_SESSION['success'] = 'Заявка на лизинг успешно отправлена! Менеджер свяжется с вами.';
            } else {
                $_SESSION['error'] = 'Ошибка при отправке заявки. Пожалуйста, попробуйте позже.';
            }
            
            header('Location: /services/leasing');
            exit;
        }
    }

    public function calculateInsurance() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            require_once 'models/Request.php';
            
            global $db;
            $request = new Request($db);
            
            $insurance_type = $_POST['insurance_type'] ?? '';
            $insurance_types = [
                'osago' => 'ОСАГО',
                'casco' => 'КАСКО',
                'comprehensive' => 'Комплексное страхование'
            ];
            $insurance_type_text = $insurance_types[$insurance_type] ?? $insurance_type;
            
            $car_brand = $_POST['car_brand'] ?? '';
            $car_model = $_POST['car_model'] ?? '';
            $car_year = $_POST['car_year'] ?? '';
            
            $message = "Расчет страховки\n";
            $message .= "Тип: {$insurance_type_text}\n";
            if ($car_brand || $car_model) {
                $message .= "Автомобиль: {$car_brand} {$car_model}";
                if ($car_year) $message .= " ({$car_year})";
                $message .= "\n";
            }
            
            $request_data = [
                'type' => 'insurance',
                'user_id' => $_SESSION['user_id'] ?? null,
                'car_id' => null,
                'name' => trim($_POST['name'] ?? $_SESSION['user_name'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'email' => trim($_POST['email'] ?? $_SESSION['user_email'] ?? ''),
                'date' => null,
                'time' => null,
                'message' => $message,
                'status' => 'new',
                'assigned_to' => null,
                'comment' => null
            ];
            
            if (empty($request_data['name']) || empty($request_data['phone'])) {
                $_SESSION['error'] = 'Пожалуйста, заполните имя и телефон';
                header('Location: /services/insurance');
                exit;
            }
            
            $request_id = $request->create($request_data);
            
            if ($request_id) {
                $request->notifyStaff($request_id, $request_data);
                $_SESSION['success'] = 'Запрос на расчет страховки отправлен! Менеджер свяжется с вами.';
            } else {
                $_SESSION['error'] = 'Ошибка при отправке запроса. Пожалуйста, попробуйте позже.';
            }
            
            header('Location: /services/insurance');
            exit;
        }
    }

    public function submitCredit() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            require_once 'models/Request.php';
            
            global $db;
            $request = new Request($db);
            
            $car_brand = $_POST['car_brand'] ?? '';
            $car_model = $_POST['car_model'] ?? '';
            $amount = $_POST['amount'] ?? '';
            $down_payment = $_POST['down_payment'] ?? '';
            $term = $_POST['term'] ?? '';
            
            $message = "Заявка на кредит\n";
            if ($car_brand || $car_model) {
                $message .= "Автомобиль: {$car_brand} {$car_model}\n";
            }
            if ($amount) {
                $message .= "Сумма кредита: {$amount} ₽\n";
            }
            if ($down_payment) {
                $message .= "Первоначальный взнос: {$down_payment} ₽\n";
            }
            if ($term) {
                $message .= "Срок: {$term} мес.\n";
            }
            
            $request_data = [
                'type' => 'credit',
                'user_id' => $_SESSION['user_id'] ?? null,
                'car_id' => null,
                'name' => trim($_POST['name'] ?? $_SESSION['user_name'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'email' => trim($_POST['email'] ?? $_SESSION['user_email'] ?? ''),
                'date' => null,
                'time' => null,
                'message' => $message,
                'status' => 'new',
                'assigned_to' => null,
                'comment' => null
            ];
            
            if (empty($request_data['name']) || empty($request_data['phone'])) {
                $_SESSION['error'] = 'Пожалуйста, заполните имя и телефон';
                header('Location: /services/credit');
                exit;
            }
            
            $request_id = $request->create($request_data);
            
            if ($request_id) {
                $request->notifyStaff($request_id, $request_data);
                $_SESSION['success'] = 'Заявка на кредит успешно отправлена! Менеджер свяжется с вами.';
            } else {
                $_SESSION['error'] = 'Ошибка при отправке заявки. Пожалуйста, попробуйте позже.';
            }
            
            header('Location: /services/credit');
            exit;
        }
    }
}
