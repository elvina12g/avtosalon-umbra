<?php
require_once 'controllers/AuthController.php';
$auth = new AuthController();
$auth->checkAuth();

// Проверяем права
if(!in_array($_SESSION['user_role'], ['admin', 'manager'])) {
    header('Location: /admin/dashboard');
    exit();
}

require_once 'config/database.php';
require_once 'models/Car.php';

$database = new Database();
$db = $database->getConnection();
$carModel = new Car($db);

// Обработка действий
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['action'])) {
        $image_name = null;
        
        // Обработка загрузки изображения
        if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $upload_dir = 'uploads/cars/';
            if(!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $image_name = time() . '_' . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name);
        }
        
        $car_data = $_POST;
        if($image_name) {
            $car_data['image'] = $image_name;
        }
        
        switch($_POST['action']) {
            case 'create':
                if($carModel->create($car_data)) {
                    $_SESSION['success'] = 'Автомобиль успешно добавлен';
                } else {
                    $_SESSION['error'] = 'Ошибка при добавлении автомобиля';
                }
                break;
            case 'update':
                if($carModel->update($_POST['id'], $car_data)) {
                    $_SESSION['success'] = 'Автомобиль успешно обновлен';
                } else {
                    $_SESSION['error'] = 'Ошибка при обновлении автомобиля';
                }
                break;
            case 'delete':
                if($carModel->delete($_POST['id'])) {
                    $_SESSION['success'] = 'Автомобиль удален';
                } else {
                    $_SESSION['error'] = 'Ошибка при удалении';
                }
                break;
        }
        header('Location: /admin/cars');
        exit();
    }
}

$cars = $carModel->getAll();
$brands = $carModel->getBrands();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление автомобилями | Админ-панель</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <style>
        .admin-wrapper { display: flex; min-height: 100vh; }
        .admin-sidebar { width: 280px; background: #1a1a2e; color: white; position: fixed; height: 100vh; overflow-y: auto; }
        .admin-content { flex: 1; margin-left: 280px; background: #f8f8f8; }
        .admin-header { background: white; padding: 20px 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; }
        .admin-main { padding: 30px; }
        .sidebar-logo { padding: 30px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-logo h3 { color: #c4a747; font-weight: 700; }
        .sidebar-nav a { display: flex; align-items: center; padding: 12px 25px; color: rgba(255,255,255,0.8); text-decoration: none; transition: all 0.3s; }
        .sidebar-nav a:hover, .sidebar-nav a.active { background: rgba(196, 167, 71, 0.1); color: #c4a747; border-left: 3px solid #c4a747; }
        .sidebar-nav a i { width: 25px; margin-right: 10px; }
        .btn-gold { background: #c4a747; color: #1a1a2e; border: none; }
        .btn-gold:hover { background: #d4b55c; color: #1a1a2e; }
        .car-image-preview { width: 80px; height: 60px; object-fit: cover; border-radius: 5px; }
        .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; }
        .status-available { background: #28a745; color: white; }
        .status-reserved { background: #ffc107; color: #1a1a2e; }
        .status-sold { background: #dc3545; color: white; }
        @media (max-width: 768px) { .admin-sidebar { transform: translateX(-100%); transition: transform 0.3s; z-index: 1000; } .admin-sidebar.open { transform: translateX(0); } .admin-content { margin-left: 0; } }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <?php include 'views/admin/sidebar.php'; ?>
        
        <div class="admin-content">
            <div class="admin-header">
                <button class="btn btn-link d-md-none" id="menuToggle"><i class="fas fa-bars"></i></button>
                <div><h4 class="mb-0">Управление автомобилями</h4></div>
                <button class="btn btn-gold" data-bs-toggle="modal" data-bs-target="#createCarModal">
                    <i class="fas fa-plus"></i> Добавить автомобиль
                </button>
            </div>
            
            <div class="admin-main">
                <?php if(isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if(isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Фото</th>
                                        <th>ID</th>
                                        <th>Марка/Модель</th>
                                        <th>Год</th>
                                        <th>Цена</th>
                                        <th>Двигатель</th>
                                        <th>Статус</th>
                                        <th>Популярный</th>
                                        <th>Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($cars as $row): ?>
                                    <tr>
                                        <td>
                                            <?php if(!empty($row['image']) && file_exists('uploads/cars/' . $row['image'])): ?>
                                                <img src="/uploads/cars/<?php echo $row['image']; ?>" class="car-image-preview" alt="">
                                            <?php else: ?>
                                                <i class="fas fa-car fa-2x text-muted"></i>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $row['id']; ?></td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($row['brand']); ?></strong><br>
                                            <small><?php echo htmlspecialchars($row['model']); ?></small>
                                         </td>
                                         <td><?php echo $row['year']; ?></td>
                                         <td><?php echo number_format($row['price'], 0, '', ' '); ?> ₽</td>
                                         <td><?php echo htmlspecialchars($row['engine'] ?? '—'); ?></td>
                                         <td>
                                            <span class="status-badge status-<?php echo $row['status']; ?>">
                                                <?php 
                                                switch($row['status']) {
                                                    case 'available': echo 'В наличии'; break;
                                                    case 'reserved': echo 'Забронирован'; break;
                                                    case 'sold': echo 'Продан'; break;
                                                    default: echo $row['status'];
                                                }
                                                ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if($row['is_popular']): ?>
                                                <span class="badge bg-gold text-dark"><i class="fas fa-star"></i> Да</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Нет</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-info" onclick="editCar(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteCar(<?php echo $row['id']; ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Создание автомобиля -->
    <div class="modal fade" id="createCarModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Добавить автомобиль</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="create">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Марка *</label>
                                <input type="text" name="brand" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Модель *</label>
                                <input type="text" name="model" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Год выпуска</label>
                                <input type="number" name="year" class="form-control" min="1990" max="<?php echo date('Y')+1; ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Цена *</label>
                                <input type="number" name="price" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Пробег (км)</label>
                                <input type="number" name="mileage" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Двигатель</label>
                                <input type="text" name="engine" class="form-control" placeholder="3.0 V6">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Мощность (л.с.)</label>
                                <input type="number" name="power" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Коробка передач</label>
                                <select name="transmission" class="form-control">
                                    <option value="automatic">Автомат</option>
                                    <option value="manual">Механика</option>
                                    <option value="robot">Робот</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Привод</label>
                                <select name="drive" class="form-control">
                                    <option value="rear">Задний</option>
                                    <option value="front">Передний</option>
                                    <option value="all">Полный</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Цвет</label>
                                <input type="text" name="color" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Статус</label>
                                <select name="status" class="form-control">
                                    <option value="available">В наличии</option>
                                    <option value="reserved">Забронирован</option>
                                    <option value="sold">Продан</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Популярный</label>
                                <select name="is_popular" class="form-control">
                                    <option value="0">Нет</option>
                                    <option value="1">Да</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label>Фото</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                                <small class="text-muted">Рекомендуемый размер: 800x600px</small>
                            </div>
                            <div class="col-12 mb-3">
                                <label>Описание</label>
                                <textarea name="description" class="form-control summernote" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-gold">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Modal Редактирования автомобиля -->
    <div class="modal fade" id="editCarModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Редактировать автомобиль</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Марка *</label>
                                <input type="text" name="brand" id="edit_brand" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Модель *</label>
                                <input type="text" name="model" id="edit_model" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Год выпуска</label>
                                <input type="number" name="year" id="edit_year" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Цена *</label>
                                <input type="number" name="price" id="edit_price" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Пробег (км)</label>
                                <input type="number" name="mileage" id="edit_mileage" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Двигатель</label>
                                <input type="text" name="engine" id="edit_engine" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Мощность (л.с.)</label>
                                <input type="number" name="power" id="edit_power" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Коробка передач</label>
                                <select name="transmission" id="edit_transmission" class="form-control">
                                    <option value="automatic">Автомат</option>
                                    <option value="manual">Механика</option>
                                    <option value="robot">Робот</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Привод</label>
                                <select name="drive" id="edit_drive" class="form-control">
                                    <option value="rear">Задний</option>
                                    <option value="front">Передний</option>
                                    <option value="all">Полный</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Цвет</label>
                                <input type="text" name="color" id="edit_color" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Статус</label>
                                <select name="status" id="edit_status" class="form-control">
                                    <option value="available">В наличии</option>
                                    <option value="reserved">Забронирован</option>
                                    <option value="sold">Продан</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Популярный</label>
                                <select name="is_popular" id="edit_is_popular" class="form-control">
                                    <option value="0">Нет</option>
                                    <option value="1">Да</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label>Новое фото (оставьте пустым, чтобы сохранить текущее)</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                                <div id="current_image_preview" class="mt-2"></div>
                            </div>
                            <div class="col-12 mb-3">
                                <label>Описание</label>
                                <textarea name="description" id="edit_description" class="form-control summernote" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-gold">Сохранить изменения</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>
        $('.summernote').summernote({
            height: 200,
            placeholder: 'Введите описание автомобиля...',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']],
                ['view', ['codeview']]
            ]
        });
        
        document.getElementById('menuToggle')?.addEventListener('click', function() {
            document.querySelector('.admin-sidebar').classList.toggle('open');
        });
        
        function editCar(car) {
            $('#edit_id').val(car.id);
            $('#edit_brand').val(car.brand);
            $('#edit_model').val(car.model);
            $('#edit_year').val(car.year);
            $('#edit_price').val(car.price);
            $('#edit_mileage').val(car.mileage || '');
            $('#edit_engine').val(car.engine || '');
            $('#edit_power').val(car.power || '');
            $('#edit_transmission').val(car.transmission || 'automatic');
            $('#edit_drive').val(car.drive || 'rear');
            $('#edit_color').val(car.color || '');
            $('#edit_status').val(car.status || 'available');
            $('#edit_is_popular').val(car.is_popular || '0');
            
            // Для Summernote
            $('#edit_description').summernote('code', car.description || '');
            
            // Показываем текущее изображение
            if(car.image) {
                $('#current_image_preview').html('<div class="alert alert-info">Текущее фото: <img src="/uploads/cars/' + car.image + '" style="max-width: 100px; border-radius: 5px;"></div>');
            } else {
                $('#current_image_preview').html('');
            }
            
            $('#editCarModal').modal('show');
        }
        
        function deleteCar(id) {
            if(confirm('Вы уверены, что хотите удалить этот автомобиль?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = '<input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="' + id + '">';
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>