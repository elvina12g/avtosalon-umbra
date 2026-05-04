<?php
// header.php - Минималистичная версия
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
        /* Минималистичная шапка */
        :root {
            --bg-primary: #F9F6F0;
            --accent-gold: #C9A961;
            --text-primary: #1A1A1A;
            --text-secondary: #4A4A4A;
        }
        
        .navbar {
            background: var(--bg-primary);
            padding: 12px 0;
            transition: all 0.3s ease;
            border-bottom: none;
            box-shadow: none;
        }
        
        .navbar.scrolled {
            padding: 8px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        /* Логотип слева */
        .navbar-brand {
            margin-right: 40px;
        }
        
        .navbar-brand img {
            height: 42px;
            width: auto;
        }
        
        /* Навигация */
        .navbar-nav {
            gap: 4px;
        }
        
        .nav-link {
            color: var(--text-primary) !important;
            font-weight: 400;
            font-size: 14px;
            padding: 8px 16px !important;
            transition: all 0.2s ease;
            letter-spacing: 0.3px;
        }
        
        .nav-link:hover,
        .nav-link.active {
            color: var(--accent-gold) !important;
            background: transparent;
        }
        
        /* Выпадающее меню - минималистичное */
        .dropdown-menu {
            background: var(--bg-primary);
            border: none;
            border-radius: 8px;
            padding: 8px 0;
            min-width: 200px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            margin-top: 8px;
        }
        
        .dropdown-item {
            padding: 8px 20px;
            font-size: 13px;
            color: var(--text-secondary);
            transition: all 0.2s ease;
        }
        
        .dropdown-item:hover {
            background: rgba(201, 169, 97, 0.08);
            color: var(--accent-gold);
            padding-left: 24px;
        }
        
        .dropdown-toggle::after {
            margin-left: 6px;
            vertical-align: middle;
            border-top-color: var(--accent-gold);
        }
        
        /* Иконка пользователя */
        .user-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            background: transparent;
            border-radius: 50%;
            color: var(--text-primary);
            font-size: 20px;
            transition: all 0.2s ease;
            cursor: pointer;
            text-decoration: none;
            margin-left: 20px;
        }
        
        .user-icon:hover {
            color: var(--accent-gold);
            transform: translateY(-1px);
        }
        
        /* Контактная информация - минималистично */
        .header-contacts {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-left: auto;
        }
        
        .contact-phone {
            font-weight: 500;
            color: var(--text-primary);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s;
        }
        
        .contact-phone:hover {
            color: var(--accent-gold);
        }
        
        .contact-email {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 13px;
            transition: color 0.2s;
        }
        
        .contact-email:hover {
            color: var(--accent-gold);
        }
        
        .contact-btn {
            background: transparent;
            color: var(--accent-gold);
            padding: 6px 16px;
            border-radius: 30px;
            font-weight: 500;
            font-size: 13px;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid var(--accent-gold);
        }
        
        .contact-btn:hover {
            background: var(--accent-gold);
            color: var(--bg-primary);
            transform: translateY(-1px);
        }
        
        /* Бургер-кнопка (адаптив) */
        .navbar-toggler {
            border: none;
            padding: 6px 10px;
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
        
        /* Адаптив */
        @media (max-width: 991px) {
            .navbar-brand img {
                height: 38px;
            }
            
            .header-contacts {
                display: none;
            }
            
            .navbar-collapse {
                background: var(--bg-primary);
                padding: 20px;
                margin-top: 12px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.05);
                border-radius: 12px;
            }
            
            .nav-link {
                padding: 10px 0 !important;
            }
            
            .dropdown-menu {
                background: transparent;
                box-shadow: none;
                padding-left: 20px;
            }
            
            .user-icon {
                margin-left: 0;
                margin-top: 15px;
                justify-content: flex-start;
                width: auto;
                gap: 10px;
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
        }
        
        @media (min-width: 992px) {
            .user-icon span {
                display: none;
            }
        }
        
        /* Убираем лишние разделители */
        hr {
            display: none;
        }
        
        .dropdown-divider {
            display: none;
        }
    </style>
</head>
<body>

<!-- Навигация -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <!-- Логотип слева -->
        <a class="navbar-brand" href="/">
            <img src="assets/images/logo/Group 5.png" alt="UMBRA Logo">
        </a>
        
        <!-- Бургер-кнопка -->
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
                
                <!-- Услуги - выпадающее меню -->
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
                
                <li class="nav-item">
                    <a class="nav-link <?php echo ($_SERVER['REQUEST_URI'] == '/faq') ? 'active' : ''; ?>" href="/faq">Ваши вопросы</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($_SERVER['REQUEST_URI'] == '/contacts') ? 'active' : ''; ?>" href="/contacts">Контакты</a>
                </li>
            </ul>
        </div>
        
        <!-- Контактная информация -->
        <div class="header-contacts">
            <div>
                <a href="tel:+74951234567" class="contact-phone">8 (800) 222-84-05</a>
                <br>
                <a href="mailto:info@umbra.ru" class="contact-email">info@umbra.ru</a>
            </div>
            <a href="#contactForm" class="contact-btn" id="headerContactBtn">Связаться</a>
        </div>
        
        <!-- Иконка пользователя с плюсом -->
        <?php if(isset($_SESSION['user_id'])): ?>
            <div class="dropdown">
                <a href="#" class="user-icon" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle"></i>
                    <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="/profile"><i class="fas fa-id-card me-2"></i> Личный кабинет</a></li>
                    <li><a class="dropdown-item" href="/profile/favorites"><i class="fas fa-heart me-2"></i> Избранное</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="/auth/logout"><i class="fas fa-sign-out-alt me-2"></i> Выйти</a></li>
                </ul>
            </div>
        <?php else: ?>
            <a href="/auth/login" class="user-icon" title="Войти или зарегистрироваться">
                <i class="fas fa-user-plus"></i>
                <span>Войти</span>
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
        
        // Плавная прокрутка к контактной форме
        const headerContactBtn = document.getElementById('headerContactBtn');
        
        function scrollToContact() {
            const contactForm = document.getElementById('mainContactForm');
            if(contactForm) {
                contactForm.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                window.location.href = '/#contactForm';
            }
        }
        
        if(headerContactBtn) {
            headerContactBtn.addEventListener('click', function(e) {
                e.preventDefault();
                scrollToContact();
            });
        }
        
        // Для мобильной версии - поддержка выпадающего меню
        document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                if(window.innerWidth <= 991) {
                    e.preventDefault();
                    const parent = this.closest('.dropdown');
                    const menu = parent.querySelector('.dropdown-menu');
                    if(menu) {
                        menu.classList.toggle('show');
                    }
                }
            });
        });
        
        // Прелоадер
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