<?php
require_once 'controllers/AuthController.php';
$auth = new AuthController();
$auth->checkAuth();

// Проверяем права (только админ)
if($_SESSION['user_role'] != 'admin') {
    header('Location: /admin/dashboard');
    exit();
}

// Обработка сохранения настроек
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Здесь будет сохранение настроек в БД или файл
    $_SESSION['success'] = 'Настройки сохранены';
    header('Location: /admin/settings');
    exit();
}

// Текущие настройки (можно вынести в отдельную таблицу БД)
$settings = [
    'site_name' => 'Автосалон Премиум',
    'site_email' => 'info@avtosalon.ru',
    'site_phone' => '+7 (999) 123-45-67',
    'site_address' => 'г. Москва, ул. Автомобильная, д. 1',
    'work_hours' => 'Пн-Пт: 9:00-20:00, Сб-Вс: 10:00-18:00',
    'maintenance_mode' => '0'
];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Настройки сайта | Админ-панель</title>
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
        .settings-card { background: white; border-radius: 10px; padding: 25px; margin-bottom: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .settings-card h5 { margin-bottom: 20px; color: #c4a747; }
        @media (max-width: 768px) { .admin-sidebar { transform: translateX(-100%); transition: transform 0.3s; z-index: 1000; } .admin-sidebar.open { transform: translateX(0); } .admin-content { margin-left: 0; } }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <?php include 'views/admin/sidebar.php'; ?>
        
        <div class="admin-content">
            <div class="admin-header">
                <button class="btn btn-link d-md-none" id="menuToggle"><i class="fas fa-bars"></i></button>
                <div><h4 class="mb-0">Настройки сайта</h4></div>
            </div>
            
            <div class="admin-main">
                <?php if(isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="settings-card">
                        <h5><i class="fas fa-globe"></i> Основные настройки</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Название сайта</label>
                                <input type="text" name="site_name" class="form-control" value="<?php echo $settings['site_name']; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Email сайта</label>
                                <input type="email" name="site_email" class="form-control" value="<?php echo $settings['site_email']; ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="settings-card">
                        <h5><i class="fas fa-phone"></i> Контактная информация</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Телефон</label>
                                <input type="text" name="site_phone" class="form-control" value="<?php echo $settings['site_phone']; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Адрес</label>
                                <input type="text" name="site_address" class="form-control" value="<?php echo $settings['site_address']; ?>">
                            </div>
                            <div class="col-12 mb-3">
                                <label>Режим работы</label>
                                <input type="text" name="work_hours" class="form-control" value="<?php echo $settings['work_hours']; ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="settings-card">
                        <h5><i class="fas fa-shield-alt"></i> Безопасность</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="maintenance_mode" value="1" id="maintenanceMode" <?php echo $settings['maintenance_mode'] == '1' ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="maintenanceMode">
                                        Режим обслуживания сайта
                                    </label>
                                </div>
                                <small class="text-muted">При включении сайт будет доступен только администраторам</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="settings-card">
                        <h5><i class="fas fa-database"></i> Управление БД</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <button type="button" class="btn btn-warning" onclick="backupDatabase()">
                                    <i class="fas fa-download"></i> Резервное копирование
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-danger" onclick="clearCache()">
                                    <i class="fas fa-trash"></i> Очистить кэш
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-end">
                        <button type="submit" class="btn btn-gold btn-lg">
                            <i class="fas fa-save"></i> Сохранить настройки
                        </button>
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
        
        function backupDatabase() {
            if(confirm('Создать резервную копию базы данных?')) {
                window.location.href = '/admin/backup';
            }
        }
        
        function clearCache() {
            if(confirm('Очистить кэш сайта?')) {
                $.post('/admin/clear-cache', function(response) {
                    alert('Кэш очищен');
                });
            }
        }
    </script>
</body>
</html>