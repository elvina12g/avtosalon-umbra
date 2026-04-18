<?php
require_once 'controllers/AuthController.php';
$auth = new AuthController();
$auth->checkAuth();

// Проверяем права (только админ)
if($_SESSION['user_role'] != 'admin') {
    header('Location: /admin/dashboard');
    exit();
}

require_once 'config/database.php';
require_once 'models/User.php';

$database = new Database();
$db = $database->getConnection();
$userModel = new User($db);

// Обработка действий
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['action'])) {
        switch($_POST['action']) {
            case 'create':
                $userModel->createUser($_POST);
                $_SESSION['success'] = 'Пользователь успешно создан';
                break;
            case 'update':
                $userModel->updateUser($_POST['id'], $_POST);
                $_SESSION['success'] = 'Пользователь успешно обновлен';
                break;
            case 'delete':
                $userModel->deleteUser($_POST['id']);
                $_SESSION['success'] = 'Пользователь удален';
                break;
        }
        header('Location: /admin/users');
        exit();
    }
}

$users = $userModel->getAllUsers();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление пользователями | Админ-панель</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
        .role-badge { padding: 5px 10px; border-radius: 5px; font-size: 12px; font-weight: 600; }
        .role-admin { background: #dc3545; color: white; }
        .role-manager { background: #ffc107; color: #1a1a2e; }
        .role-consultant { background: #17a2b8; color: white; }
        .role-user { background: #6c757d; color: white; }
        @media (max-width: 768px) { .admin-sidebar { transform: translateX(-100%); transition: transform 0.3s; z-index: 1000; } .admin-sidebar.open { transform: translateX(0); } .admin-content { margin-left: 0; } }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <?php include 'views/admin/sidebar.php'; ?>
        
        <div class="admin-content">
            <div class="admin-header">
                <button class="btn btn-link d-md-none" id="menuToggle"><i class="fas fa-bars"></i></button>
                <div><h4 class="mb-0">Управление пользователями</h4></div>
                <button class="btn btn-gold" data-bs-toggle="modal" data-bs-target="#createUserModal">
                    <i class="fas fa-plus"></i> Добавить пользователя
                </button>
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
                                        <th>Логин</th>
                                        <th>Имя</th>
                                        <th>Email</th>
                                        <th>Телефон</th>
                                        <th>Роль</th>
                                        <th>Статус</th>
                                        <th>Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = $users->fetch(PDO::FETCH_ASSOC)): ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                                        <td><?php echo htmlspecialchars($row['full_name'] ?? $row['name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                        <td>
                                            <span class="role-badge role-<?php echo $row['role']; ?>">
                                                <?php 
                                                switch($row['role']) {
                                                    case 'admin': echo 'Администратор'; break;
                                                    case 'manager': echo 'Менеджер'; break;
                                                    case 'consultant': echo 'Консультант'; break;
                                                    default: echo 'Пользователь';
                                                }
                                                ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if($row['is_active']): ?>
                                                <span class="badge bg-success">Активен</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Заблокирован</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-info" onclick="editUser(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteUser(<?php echo $row['id']; ?>)">
                                                <i class="fas fa-trash"></i>
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
    
    <!-- Modal Создание пользователя -->
    <div class="modal fade" id="createUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Добавить пользователя</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="create">
                        <div class="mb-3">
                            <label>Логин *</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Имя *</label>
                            <input type="text" name="full_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Email *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Телефон</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Пароль *</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Роль *</label>
                            <select name="role" class="form-control" required>
                                <option value="user">Пользователь</option>
                                <option value="manager">Менеджер</option>
                                <option value="consultant">Сервисный консультант</option>
                                <option value="admin">Администратор</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-gold">Создать</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('menuToggle')?.addEventListener('click', function() {
            document.querySelector('.admin-sidebar').classList.toggle('open');
        });
        
        function editUser(user) {
            // Реализация редактирования
            alert('Редактирование пользователя: ' + user.username);
        }
        
        function deleteUser(id) {
            if(confirm('Вы уверены, что хотите удалить этого пользователя?')) {
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