<div class="profile-sidebar card">
    <div class="card-body text-center">
        <div class="profile-avatar mb-3">
            <i class="fas fa-user-circle fa-5x text-gold"></i>
        </div>
        <h4><?php echo $_SESSION['user_name']; ?></h4>
        <p class="text-muted"><?php echo $_SESSION['user_email']; ?></p>
        <hr>
        <div class="profile-menu">
            <a href="/profile" class="profile-menu-item <?php echo ($_SERVER['REQUEST_URI'] == '/profile') ? 'active' : ''; ?>">
                <i class="fas fa-id-card"></i> Личные данные
            </a>
            <a href="/profile/testdrives" class="profile-menu-item <?php echo (strpos($_SERVER['REQUEST_URI'], '/profile/testdrives') !== false) ? 'active' : ''; ?>">
                <i class="fas fa-car"></i> Мои тест-драйвы
            </a>
            <a href="/profile/favorites" class="profile-menu-item <?php echo (strpos($_SERVER['REQUEST_URI'], '/profile/favorites') !== false) ? 'active' : ''; ?>">
                <i class="fas fa-heart"></i> Избранное
            </a>
            <a href="/profile/services" class="profile-menu-item <?php echo (strpos($_SERVER['REQUEST_URI'], '/profile/services') !== false) ? 'active' : ''; ?>">
                <i class="fas fa-tools"></i> Сервисное обслуживание
            </a>
            <a href="/profile/settings" class="profile-menu-item <?php echo (strpos($_SERVER['REQUEST_URI'], '/profile/settings') !== false) ? 'active' : ''; ?>">
                <i class="fas fa-cog"></i> Настройки
            </a>
        </div>
    </div>
</div>

<style>
.profile-sidebar {
    border-radius: 15px;
    overflow: hidden;
}
.profile-avatar i {
    font-size: 80px;
}
.profile-menu {
    display: flex;
    flex-direction: column;
    gap: 5px;
}
.profile-menu-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 15px;
    border-radius: 10px;
    color: var(--text-dark);
    text-decoration: none;
    transition: all 0.3s;
}
.profile-menu-item i {
    width: 24px;
    color: var(--accent-gold);
}
.profile-menu-item:hover,
.profile-menu-item.active {
    background: rgba(196, 167, 71, 0.1);
    color: var(--accent-gold);
}
</style>