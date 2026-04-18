<?php
$user_role = $_SESSION['user_role'];
$current_page = basename($_SERVER['REQUEST_URI']);
?>
<div class="admin-sidebar">
    <div class="sidebar-logo">
        <i class="fas fa-car" style="font-size: 40px; color: #c4a747;"></i>
        <h3>Автосалон</h3>
        <p style="font-size: 12px; color: #999;">Админ-панель</p>
    </div>
    
    <div class="sidebar-nav">
        <a href="/admin/dashboard" class="<?php echo strpos($current_page, 'dashboard') !== false ? 'active' : ''; ?>">
            <i class="fas fa-tachometer-alt"></i> Главная
        </a>
        
        <?php if($user_role == 'admin'): ?>
        <a href="/admin/users" class="<?php echo strpos($current_page, 'users') !== false ? 'active' : ''; ?>">
            <i class="fas fa-users"></i> Пользователи
        </a>
        <?php endif; ?>
        
        <?php if(in_array($user_role, ['admin', 'manager'])): ?>
        <a href="/admin/cars" class="<?php echo strpos($current_page, 'cars') !== false ? 'active' : ''; ?>">
            <i class="fas fa-car"></i> Автомобили
        </a>
        <?php endif; ?>
        
        <a href="/admin/requests" class="<?php echo strpos($current_page, 'requests') !== false ? 'active' : ''; ?>">
            <i class="fas fa-clipboard-list"></i> Заявки
        </a>
        
        <a href="/admin/news" class="<?php echo strpos($current_page, 'news') !== false ? 'active' : ''; ?>">
            <i class="fas fa-newspaper"></i> Новости
        </a>

        <?php if($user_role == 'admin'): ?>
        <a href="/admin/settings" class="<?php echo strpos($current_page, 'settings') !== false ? 'active' : ''; ?>">
            <i class="fas fa-cog"></i> Настройки
        </a>
        <?php endif; ?>
        
        <hr style="margin: 20px 0; border-color: rgba(255,255,255,0.1);">
        
        <a href="/">
            <i class="fas fa-globe"></i> Перейти на сайт
        </a>
        <a href="/auth/logout">
            <i class="fas fa-sign-out-alt"></i> Выход
        </a>
    </div>
</div>
