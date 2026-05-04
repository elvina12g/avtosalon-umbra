<?php
// header.php - Цельная минималистичная версия с акцентным цветом #4CAFC3
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Umbra - Премиальный автосалон'; ?></title>
    <meta name="description" content="<?php echo $metaDescription ?? 'Официальный дилер премиальных автомобилей'; ?>">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
    
    <style>
        /* Цельная минималистичная шапка */
        :root {
            --bg-primary: #F9F6F0;
            --accent: #4CAFC3;
            --accent-hover: #3a8a9e;
            --text-primary: #1A1A1A;
            --text-secondary: #4A4A4A;
            --border-light: rgba(76, 175, 195, 0.15);
        }
        
        .navbar {
            background: var(--bg-primary);
            padding: 14px 0;
            transition: all 0.3s ease;
            border-bottom: 1px solid var(--border-light);
        }
        
        .navbar.scrolled {
            padding: 10px 0;
            box-shadow: 0 2px 20px rgba(0,0,0,0.06);
            border-bottom-color: transparent;
        }
        
        /* Логотип */
        .navbar-brand {
            margin-right: 35px;
            padding: 0;
        }
        
        .navbar-brand img {
            height: 40px;
            width: auto;
            transition: height 0.3s ease;
        }
        
        .navbar.scrolled .navbar-brand img {
            height: 36px;
        }
        
        /* Навигационные ссылки */
        .navbar-nav {
            gap: 2px;
        }
        
        .nav-link {
            color: var(--text-primary) !important;
            font-weight: 400;
            font-size: 14px;
            padding: 8px 14px !important;
            transition: all 0.2s ease;
            letter-spacing: 0.2px;
            position: relative;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 4px;
            left: 14px;
            right: 14px;
            height: 1px;
            background: var(--accent);
            transform: scaleX(0);
            transition: transform 0.2s ease;
        }
        
        .nav-link:hover,
        .nav-link.active {
            color: var(--accent) !important;
        }
        
        .nav-link:hover::after,
        .nav-link.active::after {
            transform: scaleX(1);
        }
        
        /* Выпадающее меню */
        .dropdown-menu {
            background: #FFFFFF;
            border: 1px solid var(--border-light);
            border-radius: 10px;
            padding: 10px 0;
            min-width: 210px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.08);
            margin-top: 10px;
        }
        
        .dropdown-item {
            padding: 10px 22px;
            font-size: 13px;
            color: var(--text-secondary);
            transition: all 0.2s ease;
        }
        
        .dropdown-item:hover {
            background: rgba(76, 175, 195, 0.06);
            color: var(--accent);
            padding-left: 28px;
        }
        
        .dropdown-toggle::after {
            margin-left: 5px;
            vertical-align: middle;
            border-top-color: var(--accent);
        }
        
        /* Иконка пользователя */
        .user-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: transparent;
            border-radius: 50%;
            color: var(--text-primary);
            font-size: 19px;
            transition: all 0.2s ease;
            cursor: pointer;
            text-decoration: none;
            margin-left: 15px;
            border: 1px solid var(--border-light);
        }
        
        .user-icon:hover {
            color: var(--accent);
            border-color: var(--accent);
            transform: translateY(-1px);
        }
        
        /* Контактная информация */
        .header-contacts {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-left: auto;
        }
        
        .contact-phone-wrapper {
            text-align: right;
            line-height: 1.3;
        }
        
        .contact-phone {
            font-weight: 600;
            color: var(--text-primary);
            text-decoration: none;
            font-size: 15px;
            transition: color 0.2s;
            display: block;
        }
        
        .contact-phone:hover {
            color: var(--accent);
        }
        
        .contact-subtitle {
            font-size: 10px;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 500;
        }
        
        .contact-btn {
            background: var(--accent);
            color: #FFFFFF;
            padding: 9px 22px;
            border-radius: 25px;
            font-weight: 500;
            font-size: 13px;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        
        .contact-btn:hover {
            background: var(--accent-hover);
            color: #FFFFFF;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(76, 175, 195, 0.3);
        }
        
        /* Бургер-кнопка */
        .navbar-toggler {
            border: none;
            padding: 8px 10px;
            box-shadow: none;
        }
        
        .navbar-toggler:focus {
            box-shadow: none;
            outline: none;
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%231A1A1A' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
            width: 22px;
            height: 22px;
        }
        
        /* Адаптивность */
        @media (max-width: 991px) {
            .navbar-brand img {
                height: 36px;
            }
            
            .header-contacts {
                display: none;
            }
            
            .navbar-collapse {
                background: #FFFFFF;
                padding: 20px;
                margin-top: 12px;
                box-shadow: 0 15px 40px rgba(0,0,0,0.08);
                border-radius: 14px;
                border: 1px solid var(--border-light);
            }
            
            .nav-link {
                padding: 10px 0 !important;
                font-size: 15px;
            }
            
            .nav-link::after {
                display: none;
            }
            
            .dropdown-menu {
                background: transparent;
                box-shadow: none;
                border: none;
                padding-left: 16px;
                margin-top: 0;
            }
            
            .user-icon {
                margin-left: 0;
                margin-top: 12px;
                justify-content: flex-start;
                width: auto;
                gap: 10px;
                border: none;
            }
            
            .user-icon i {
                font-size: 18px;
            }
            
            .user-icon span {
                display: inline;
                font-size: 14px;
                font-weight: 400;
                color: var(--text-primary);
            }
            
            .mobile-contact-info {
                display: block !important;
                padding-top: 15px;
                margin-top: 15px;
                border-top: 1px solid #eee;
            }
        }
        
        @media (min-width: 992px) {
            .user-icon span {
                display: none;
            }
            
            .mobile-contact-info {
                display: none !important;
            }
        }
        
        /* Скрываем стандартные разделители */
        hr, .dropdown-divider {
            display: block;
            margin: 8px 0;
            border-color: #eee;
        }
    </style>
</head>
<body>

<!-- Навигация -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <!-- Логотип -->
        <a class="navbar-brand" href="/">
            <img src="assets/images/logo/Group 5.png" alt="UMBRA Logo">
        </a>
        
        <!-- Бургер-кнопка для мобильных -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-label="Меню">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Основное меню -->
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($_SERVER['REQUEST_URI'] == '/') ? 'active' : ''; ?>" href="/">Главная</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/cars') !== false && strpos($_SERVER['REQUEST_URI'], '/detail') === false) ? 'active' : ''; ?>" href="/cars">Каталог</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($_SERVER['REQUEST_URI'] == '/about') ? 'active' : ''; ?>" href="/about">О компании</a>
                </li>
                
                <!-- Услуги -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Услуги
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/profile/tradein">Trade-in</a></li>
                        <li><a class="dropdown-item" href="/services">ТО (Техобслуживание)</a></li>
                        <li><a class="dropdown-item" href="/services/detailing">Детейлинг</a></li>
                        <li><a class="dropdown-item" href="/cars/testdrive">Тест-драйв</a></li>
                        <li><a class="dropdown-item" href="/services/credit">Кредит</a></li>
                        <li><a class="dropdown-item" href="/services/leasing">Лизинг</a></li>
                        <li><a class="dropdown-item" href="/services/insurance">Страхование</a></li>
                    </ul>
                </li>
                
                <!-- Помощь -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Помощь
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/faq"><i class="far fa-question-circle me-2"></i>Вопрос-ответ</a></li>
                        <li><a class="dropdown-item" href="/contacts"><i class="far fa-envelope me-2"></i>Обратная связь</a></li>
                    </ul>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?php echo ($_SERVER['REQUEST_URI'] == '/contacts') ? 'active' : ''; ?>" href="/contacts">Контакты</a>
                </li>
            </ul>
            
            <!-- Мобильная версия контактов -->
            <div class="mobile-contact-info">
                <a href="tel:88002228405" class="contact-phone d-block mb-1">8 (800) 222-84-05</a>
                <small class="contact-subtitle d-block mb-3">Автосалон в Уфе</small>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="/profile" class="btn btn-sm w-100 mb-2" style="background: var(--accent); color: #fff;">Личный кабинет</a>
                    <a href="/auth/logout" class="btn btn-sm btn-outline-secondary w-100">Выйти</a>
                <?php else: ?>
                    <a href="/auth/login" class="btn btn-sm w-100" style="background: var(--accent); color: #fff;">Войти / Регистрация</a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Десктопная контактная информация -->
        <div class="header-contacts">
            <div class="contact-phone-wrapper">
                <a href="tel:88002228405" class="contact-phone">8 (800) 222-84-05</a>
                <span class="contact-subtitle">Автосалон в Уфе</span>
            </div>
            <a href="#contactForm" class="contact-btn" id="headerContactBtn">Связаться</a>
        </div>
        
        <!-- Иконка пользователя -->
        <?php if(isset($_SESSION['user_id'])): ?>
            <div class="dropdown d-none d-lg-block">
                <a href="#" class="user-icon" data-bs-toggle="dropdown" aria-expanded="false" title="<?php echo htmlspecialchars($_SESSION['user_name']); ?>">
                    <i class="fas fa-user-circle"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="/profile"><i class="fas fa-id-card me-2"></i>Личный кабинет</a></li>
                    <li><a class="dropdown-item" href="/profile/favorites"><i class="fas fa-heart me-2"></i>Избранное</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="/auth/logout"><i class="fas fa-sign-out-alt me-2"></i>Выйти</a></li>
                </ul>
            </div>
        <?php else: ?>
            <a href="/auth/login" class="user-icon d-none d-lg-flex" title="Войти или зарегистрироваться">
                <i class="fas fa-user-plus"></i>
            </a>
        <?php endif; ?>
    </div>
</nav>

<!-- Flash Messages -->
<?php if(isset($_SESSION['success'])): ?>
    <div class="flash-message success">
        <?php
            echo $_SESSION['success'];
            unset($_SESSION['success']);
        ?>
    </div>
<?php endif; ?>

<?php if(isset($_SESSION['error'])): ?>
    <div class="flash-message error">
        <?php
            echo $_SESSION['error'];
            unset($_SESSION['error']);
        ?>
    </div>
<?php endif; ?>

<main>
    <script>
        // Скролл-эффект для навбара
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if(navbar) {
                if(window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            }
        });
        
        // Обработчик кнопки "Связаться"
        const headerContactBtn = document.getElementById('headerContactBtn');
        
        function scrollToContact() {
            // Пробуем найти форму на странице
            const contactForm = document.getElementById('mainContactForm');
            if(contactForm) {
                contactForm.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                // Если формы нет — переходим на главную с якорем
                window.location.href = '/#contactForm';
            }
        }
        
        if(headerContactBtn) {
            headerContactBtn.addEventListener('click', function(e) {
                e.preventDefault();
                scrollToContact();
            });
        }
        
        // Поддержка выпадающего меню на мобильных
        document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                if(window.innerWidth <= 991) {
                    e.preventDefault();
                    const parent = this.closest('.dropdown');
                    const menu = parent.querySelector('.dropdown-menu');
                    if(menu) {
                        const isOpen = menu.classList.contains('show');
                        // Закрываем все открытые меню
                        document.querySelectorAll('.dropdown-menu.show').forEach(m => m.classList.remove('show'));
                        // Открываем/закрываем текущее
                        if(!isOpen) {
                            menu.classList.add('show');
                        }
                    }
                }
            });
        });
        
        // Закрытие мобильного меню при клике на ссылку
        document.querySelectorAll('.navbar-nav .nav-link:not(.dropdown-toggle)').forEach(link => {
            link.addEventListener('click', function() {
                const navbarCollapse = document.getElementById('navbarMain');
                if(navbarCollapse && navbarCollapse.classList.contains('show')) {
                    const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
                    if(bsCollapse) bsCollapse.hide();
                }
            });
        });
        
        // Прелоадер (если есть)
        window.addEventListener('load', function() {
            const preloader = document.querySelector('.pre-loader');
            if(preloader) {
                setTimeout(() => {
                    preloader.classList.add('hidden');
                    setTimeout(() => {
                        preloader.style.display = 'none';
                    }, 500);
                }, 500);
            }
        });
    </script>