<?php
require_once 'controllers/AuthController.php';
$auth = new AuthController();
$auth->checkAuth();

require_once 'config/database.php';
require_once 'models/Request.php';
require_once 'models/User.php';

$database = new Database();
$db = $database->getConnection();
$requestModel = new Request($db);
$userModel = new User($db);

$user_role = $_SESSION['user_role'];
$user_id = $_SESSION['user_id'];

// Получаем заявки в зависимости от роли
if($user_role == 'admin') {
    $requests = $requestModel->getAll();
} elseif($user_role == 'manager') {
    // Менеджер видит заявки на тест-драйв, trade-in, showroom
    $requests = $requestModel->getByType('test_drive');
    // Здесь можно добавить объединение нескольких типов
} elseif($user_role == 'consultant') {
    // Консультант видит только сервисные заявки
    $requests = $requestModel->getByType('service');
} else {
    $requests = $requestModel->getAll();
}

// Обработка обновления статуса
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_status') {
    $requestModel->update($_POST['id'], [
        'status' => $_POST['status'],
        'assigned_to' => $_POST['assigned_to'],
        'comment' => $_POST['comment']
    ]);
    $_SESSION['success'] = 'Статус заявки обновлен';
    header('Location: /admin/requests');
    exit();
}

$users = $userModel->getAllUsers();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление заявками | Админ-панель</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Стили как в предыдущих файлах */
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
        .status-badge { padding: 5px 10px; border-radius: 5px; font-size: 12px; font-weight: 600; }
        .status-new { background: #17a2b8; color: white; }
        .status-in_progress { background: #ffc107; color: #1a1a2e; }
        .status-completed { background: #28a745; color: white; }
        .status-cancelled { background: #dc3545; color: white; }
        .type-badge { padding: 5px 10px; border-radius: 5px; font-size: 12px; }
        .type-test_drive { background: #6f42c1; color: white; }
        .type-service { background: #fd7e14; color: white; }
        .type-trade_in { background: #20c997; color: white; }
        .type-showroom { background: #e83e8c; color: white; }
        @media (max-width: 768px) { .admin-sidebar { transform: translateX(-100%); transition: transform 0.3s; z-index: 1000; } .admin-sidebar.open { transform: translateX(0); } .admin-content { margin-left: 0; } }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <?php include 'views/admin/sidebar.php'; ?>
        
        <div class="admin-content">
            <div class="admin-header">
                <button class="btn btn-link d-md-none" id="menuToggle"><i class="fas fa-bars"></i></button>
                <div><h4 class="mb-0">Управление заявками</h4></div>
            </div>
            
            <div class="admin-main">
                <?php if(isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Дата</th>
                                        <th>Тип</th>
                                        <th>Клиент</th>
                                        <th>Телефон</th>
                                        <th>Статус</th>
                                        <th>Ответственный</th>
                                        <th>Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = $requests->fetch(PDO::FETCH_ASSOC)): ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo date('d.m.Y', strtotime($row['created_at'])); ?></td>
                                        <td>
                                            <span class="type-badge type-<?php echo $row['type']; ?>">
                                                <?php echo $requestModel->getTypeText($row['type']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($row['name']); ?></strong>
                                            <?php if($row['email']): ?><br><small><?php echo $row['email']; ?></small><?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                        <td>
                                            <span class="status-badge status-<?php echo $row['status']; ?>">
                                                <?php echo $requestModel->getStatusText($row['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo $row['assigned_to_name'] ?? 'Не назначен'; ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-info" onclick="viewRequest(<?php echo $row['id']; ?>)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning" onclick="editRequest(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Редактирования заявки -->
    <div class="modal fade" id="editRequestModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Редактирование заявки</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update_status">
                        <input type="hidden" name="id" id="request_id">
                        <div class="mb-3">
                            <label>Статус</label>
                            <select name="status" id="request_status" class="form-control">
                                <option value="new">Новая</option>
                                <option value="in_progress">В работе</option>
                                <option value="completed">Выполнена</option>
                                <option value="cancelled">Отменена</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Назначить ответственного</label>
                            <select name="assigned_to" class="form-control">
                                <option value="">Не назначен</option>
                                <?php while($user = $users->fetch(PDO::FETCH_ASSOC)): ?>
                                    <?php if(in_array($user['role'], ['admin', 'manager', 'consultant'])): ?>
                                    <option value="<?php echo $user['id']; ?>">
                                        <?php echo htmlspecialchars($user['full_name'] ?? $user['name']); ?> 
                                        (<?php echo $user['role']; ?>)
                                    </option>
                                    <?php endif; ?>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Комментарий</label>
                            <textarea name="comment" id="request_comment" class="form-control" rows="4"></textarea>
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
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('menuToggle')?.addEventListener('click', function() {
            document.querySelector('.admin-sidebar').classList.toggle('open');
        });
        
        function viewRequest(id) {
            alert('Просмотр заявки #' + id);
        }
        
        function editRequest(request) {
            $('#request_id').val(request.id);
            $('#request_status').val(request.status);
            $('#request_comment').val(request.comment || '');
            $('#editRequestModal').modal('show');
        }
    </script>
</body>
</html>