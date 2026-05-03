<?php
require_once 'controllers/AuthController.php';
$auth = new AuthController();
$auth->checkAuth();

$user_role = $_SESSION['user_role'];
$user_name = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель | Автосалон</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        .admin-sidebar {
            width: 280px;
            background: #1a1a2e;
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        
        .admin-content {
            flex: 1;
            margin-left: 280px;
            background: #f8f8f8;
        }
        
        .admin-header {
            background: white;
            padding: 20px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .admin-main {
            padding: 30px;
        }
        
        .sidebar-logo {
            padding: 30px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-logo h3 {
            color: #c4a747;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .sidebar-nav {
            padding: 20px 0;
        }
        
        .sidebar-nav a {
            display: flex;
            align-items: center;
            padding: 12px 25px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background: rgba(196, 167, 71, 0.1);
            color: #c4a747;
            border-left: 3px solid #c4a747;
        }
        
        .sidebar-nav a i {
            width: 25px;
            margin-right: 10px;
        }
        
        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .stats-icon {
            font-size: 40px;
            color: #c4a747;
        }
        
        .stats-number {
            font-size: 32px;
            font-weight: 700;
            margin: 10px 0;
        }
        
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
                z-index: 1000;
            }
            
            .admin-sidebar.open {
                transform: translateX(0);
            }
            
            .admin-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <div class="admin-sidebar">
            <div class="sidebar-logo">
                <i class="fas fa-car" style="font-size: 40px; color: #c4a747;"></i>
                <h3>Автосалон</h3>
                <p style="font-size: 12px; color: #999;">Админ-панель</p>
            </div>
            
            <div class="sidebar-nav">
                <a href="/admin/dashboard" class="active">
                    <i class="fas fa-tachometer-alt"></i> Главная
                </a>
                
                <?php if($user_role == 'admin'): ?>
                <a href="/admin/users">
                    <i class="fas fa-users"></i> Пользователи
                </a>
                <?php endif; ?>
                
                <?php if(in_array($user_role, ['admin', 'manager'])): ?>
                <a href="/admin/cars">
                    <i class="fas fa-car"></i> Автомобили
                </a>
                <?php endif; ?>
                
                <a href="/admin/requests">
                    <i class="fas fa-clipboard-list"></i> Заявки
                </a>
                
                <?php if($user_role == 'admin'): ?>
                <a href="/admin/settings">
                    <i class="fas fa-cog"></i> Настройки
                </a>
                <?php endif; ?>
                
                <hr style="margin: 20px 0; border-color: rgba(255,255,255,0.1);">
                
                <a href="/auth/logout">
                    <i class="fas fa-sign-out-alt"></i> Выход
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="admin-content">
            <div class="admin-header">
                <button class="btn btn-link d-md-none" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div>
                    <h4 class="mb-0">Добро пожаловать, <?php echo htmlspecialchars($user_name); ?>!</h4>
                    <small class="text-muted">
                        Роль: 
                        <?php 
                        switch($user_role) {
                            case 'admin': echo 'Администратор'; break;
                            case 'manager': echo 'Менеджер по продажам'; break;
                            case 'consultant': echo 'Сервисный консультант'; break;
                        }
                        ?>
                    </small>
                </div>
                <div>
                    <span class="badge bg-gold"><?php echo date('d.m.Y H:i'); ?></span>
                </div>
            </div>
            
            <div class="admin-main">
                <div class="row">
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div class="stats-number">156</div>
                                    <div>Всего автомобилей</div>
                                </div>
                                <div class="stats-icon">
                                    <i class="fas fa-car"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div class="stats-number">24</div>
                                    <div>Новых заявок</div>
                                </div>
                                <div class="stats-icon">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div class="stats-number">12</div>
                                    <div>Тест-драйвов</div>
                                </div>
                                <div class="stats-icon">
                                    <i class="fas fa-key"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div class="stats-number">8</div>
                                    <div>Записей в сервис</div>
                                </div>
                                <div class="stats-icon">
                                    <i class="fas fa-wrench"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Блок последних заявок -->
                <div class="stats-card mt-4">
                    <h5><i class="fas fa-clock"></i> Последние заявки</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Дата</th>
                                    <th>Тип</th>
                                    <th>Клиент</th>
                                    <th>Статус</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2024-01-15</td>
                                    <td>Тест-драйв</td>
                                    <td>Иван Петров</td>
                                    <td><span class="badge bg-warning">Новая</span></td>
                                    <td><button class="btn btn-sm btn-gold">Обработать</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.getElementById('menuToggle')?.addEventListener('click', function() {
            document.querySelector('.admin-sidebar').classList.toggle('open');
        });
    </script>
</body>
</html>