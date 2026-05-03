<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Umbra - Премиальный автосалон'; ?></title>
    <meta name="description" content="<?php echo $metaDescription ?? 'Официальный дилер премиальных автомобилей'; ?>">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nico+Moji&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
    <style>
        /* Прелоадер стили */
        .pre-loader {
            background-color: #fff;
            position: fixed;
            height: 100%;
            width: 100%;
            left: 0;
            top: 0;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s ease;
        }

        .pre-loader.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .pre-loader .loader svg {
            width: 80px;
            height: 80px;
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.7; }
        }
    </style>
<body class="<?php echo ($_SERVER['REQUEST_URI'] == '/' || $_SERVER['REQUEST_URI'] == '/index.php') ? 'home-page' : ''; ?>">
    <!-- ПРЕЛОУДЕР -->
    <div class="pre-loader">
        <div class="loader">
            <svg viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="45" fill="none" stroke="#7b7b7b" stroke-width="3"/>
                <circle cx="50" cy="50" r="45" fill="none" stroke="#cfcfcf" stroke-width="3" stroke-dasharray="200" stroke-dashoffset="0">
                    <animateTransform attributeName="transform" type="rotate" from="0 50 50" to="360 50 50" dur="1.5s" repeatCount="indefinite"/>
                </circle>
            </svg>
        </div>
    </div>

    <!-- Навигация -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <span class="brand-text">UMBRA</span>
                <span class="brand-subtext">Premium Auto</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($_SERVER['REQUEST_URI'] == '/') ? 'active' : ''; ?>" href="/">Главная</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/cars') !== false && strpos($_SERVER['REQUEST_URI'], '/detail') === false) ? 'active' : ''; ?>" href="/cars">Каталог</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Услуги
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/cars/testdrive">Тест-драйв</a></li>
                            <li><a class="dropdown-item" href="/services">Сервисное обслуживание</a></li>
                            <li><a class="dropdown-item" href="/profile/tradein">Trade-in</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/services/leasing">Лизинг</a></li>
                            <li><a class="dropdown-item" href="/services/insurance">Страхование</a></li>
                            <li><a class="dropdown-item" href="/services/credit">Кредитование</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($_SERVER['REQUEST_URI'] == '/about') ? 'active' : ''; ?>" href="/about">О компании</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/news') !== false) ? 'active' : ''; ?>" href="/news">Новости</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($_SERVER['REQUEST_URI'] == '/contacts') ? 'active' : ''; ?>" href="/contacts">Контакты</a>
                    </li>
                </ul>
                <div class="navbar-actions">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <div class="dropdown">
                            <button class="btn btn-outline-gold dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> <?php echo $_SESSION['user_name']; ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="/profile"><i class="fas fa-id-card"></i> Личный кабинет</a></li>
                                <li><a class="dropdown-item" href="/profile/favorites"><i class="fas fa-heart"></i> Избранное</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/auth/logout"><i class="fas fa-sign-out-alt"></i> Выйти</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="/auth/login" class="btn btn-outline-gold me-2">Войти</a>
                        <a href="/auth/register" class="btn btn-gold">Регистрация</a>
                    <?php endif; ?>
                </div>
            </div>
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
        <!-- Скрипт для скрытия прелоадера -->
        <script>
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