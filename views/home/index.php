<?php
// views/home/index.php
// Получаем популярные автомобили для главной страницы
$popularCars = [];
if(isset($db)) {
    require_once 'models/Car.php';
    $carModel = new Car($db);
    $popularCars = $carModel->getPopular(4);
}
?>
    <!-- ========== HERO СЕКЦИЯ ========== -->
    <section class="hero-section">
        <div class="hero-bg-image">
            <img src="assets/images/brands/фон2.jpg" alt="Luxury Cars Showroom" class="hero-bg-img">
            <div class="hero-overlay-gradient"></div>
        </div>

        <div class="hero-content">
            <div class="container">
                <div class="row min-vh-100 align-items-center">
                    <div class="col-lg-7" data-aos="fade-right" data-aos-duration="1000">

                        <h1 class="hero-title">
                            <span class="title-line">ИСКУССТВО</span>
                            <span class="title-line gold-text">АВТОМОБИЛЬНОЙ</span>
                            <span class="title-line">РОСКОШИ</span>
                        </h1>
                        <div class="hero-divider"></div>
                        <p class="hero-description">
                            Umbra Premium Auto — где безупречный стиль встречается с абсолютным комфортом.
                            Эксклюзивные автомобили премиум-класса с персональным подходом.
                            Официальный дилер Mercedes-Benz, BMW, Audi, Porsche.
                        </p>
                        <div class="hero-buttons">
                            <a href="/cars" class="btn btn-gold btn-lg">Исследовать каталог</a>
                            <a href="/cars/testdrive" class="btn btn-outline-gold btn-lg">
                                <i class="fas fa-key me-2"></i>Тест-драйв
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-5 d-none d-lg-block" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                        <div class="hero-floating-card">
                            <div class="floating-car-image">
                                <img src="assets/images/brands/машина1.png" alt="Mercedes-Benz S-Class" class="img-fluid">
                            </div>
                            <div class="floating-badge">
                                <span class="badge-new">Новая модель 2026 года</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== О КОМПАНИИ (КРАТКО) ========== -->
    <section class="about-short-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="about-short-image">
                        <img src="assets/images/brands/шоу рум.jpg" alt="Шоу-рум Umbra" class="img-fluid rounded">
                        <div class="experience-badge">
                            <span class="years">5</span>
                            <span class="text">лет<br>безупречной<br>работы</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="about-short-content">
                        <span class="section-subtitle">О компании</span>
                        <h2 class="section-title mb-4">Umbra — <span class="gold-text">премиальный</span> автосалон</h2>
                        <p class="about-text">Мы дарим нашим клиентам возможность владеть автомобилями премиум-класса с безупречным сервисом. Наша миссия — быть надежным партнером на всем пути владения автомобилем.</p>
                        <div class="about-features">
                            <div class="feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Официальный дилер 4 брендов</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Собственный сервисный центр</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Персональный консьерж-сервис</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Trade-in и кредитование</span>
                            </div>
                        </div>
                        <a href="/about" class="btn btn-outline-gold mt-3">Подробнее о компании <i class="fas fa-arrow-right ms-2"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== ОФИЦИАЛЬНЫЕ БРЕНДЫ ========== -->
    <section class="brands-section">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <span class="section-subtitle">Партнеры</span>
                <h2 class="section-title">Официальные <span class="gold-text">дилеры</span></h2>
                <p class="section-description">Только оригинальные автомобили с полной заводской гарантией</p>
            </div>

            <div class="brands-grid">
                <div class="brand-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="brand-logo-wrapper">
                        <img src="assets/images/cars/мерседес лого.png" alt="Mercedes-Benz">
                    </div>
                    <span class="brand-name">Mercedes-Benz</span>
                </div>
                <div class="brand-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="brand-logo-wrapper">
                        <img src="assets/images/cars/бмв лого.png" alt="BMW">
                    </div>
                    <span class="brand-name">BMW</span>
                </div>
                <div class="brand-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="brand-logo-wrapper">
                        <img src="assets/images/cars/ауди лого.png" alt="Audi">
                    </div>
                    <span class="brand-name">Audi</span>
                </div>
                <div class="brand-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="brand-logo-wrapper">
                        <img src="assets/images/cars/порш лого.png" alt="Porsche">
                    </div>
                    <span class="brand-name">Porsche</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== ПРЕИМУЩЕСТВА ========== -->
    <section class="advantages-section">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <span class="section-subtitle">Почему Umbra</span>
                <h2 class="section-title">Преимущества <span class="gold-text">работы с нами</span></h2>
            </div>

            <div class="advantages-grid">
                <div class="advantage-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="advantage-icon">
                        <i class="fas fa-gem"></i>
                    </div>
                    <h3>Эксклюзивность</h3>
                    <p>Лимитированные серии и редкие модели из первых рук</p>
                </div>

                <div class="advantage-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="advantage-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h3>Персональный подход</h3>
                    <p>Индивидуальный консьерж-сервис 24/7 для каждого клиента</p>
                </div>

                <div class="advantage-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="advantage-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3>Оперативность</h3>
                    <p>Быстрое оформление покупки любой модели авто</p>
                </div>

                <div class="advantage-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="advantage-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Гарантия качества</h3>
                    <p>Официальная гарантия и сервисное обслуживание</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== ПОПУЛЯРНЫЕ МОДЕЛИ ========== -->
    <section class="featured-cars-section">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <span class="section-subtitle">Для истинных ценителей</span>
                <h2 class="section-title">Популярные <span class="gold-text">модели</span></h2>
                <p class="section-description">Автомобили, которые выбирают наши клиенты</p>
            </div>

            <div class="cars-slider-wrapper">
                <div class="cars-slider" id="carsSlider">
                    <?php if(!empty($popularCars)): ?>
                        <?php foreach($popularCars as $index => $car): ?>
                        <div class="car-slide" data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 100; ?>">
                            <div class="car-card-luxury">
                                <div class="car-image">
                                    <?php
                                    $imagePath = !empty($car['image']) && file_exists('uploads/cars/' . $car['image'])
                                        ? '/uploads/cars/' . $car['image']
                                        : '/assets/images/cars/default-car.jpg';
                                    ?>
                                    <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?>">
                                    <?php if($car['is_popular'] ?? false): ?>
                                        <div class="car-badge">
                                            <i class="fas fa-fire"></i> Хит продаж
                                        </div>
                                    <?php endif; ?>
                                    <div class="car-overlay">
                                        <div class="car-actions">
                                            <a href="/cars/detail/<?php echo $car['id']; ?>" class="action-btn">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="/cars/testdrive/<?php echo $car['id']; ?>" class="action-btn">
                                                <i class="fas fa-key"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="car-info">
                                    <div class="car-title">
                                        <h3><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></h3>
                                        <div class="car-price"><?php echo number_format($car['price'], 0, '.', ' '); ?> ₽</div>
                                    </div>
                                    <div class="car-specs">
                                        <span><i class="fas fa-calendar-alt"></i> <?php echo $car['year']; ?></span>
                                        <span><i class="fas fa-tachometer-alt"></i> <?php echo number_format($car['mileage'] ?? 0, 0, '.', ' '); ?> км</span>
                                        <span><i class="fas fa-gas-pump"></i> <?php echo htmlspecialchars($car['engine'] ?? '—'); ?></span>
                                    </div>
                                    <a href="/cars/detail/<?php echo $car['id']; ?>" class="btn-detail-link">
                                        Подробнее <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center">
                            <p>Автомобили загружаются...</p>
                        </div>
                    <?php endif; ?>
                </div>

                <button class="slider-nav prev-slide" id="prevSlide">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="slider-nav next-slide" id="nextSlide">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <div class="text-center mt-5">
                <a href="/cars" class="btn btn-outline-gold btn-lg">Весь каталог <i class="fas fa-arrow-right ms-2"></i></a>
            </div>
        </div>
    </section>

    <!-- ========== УСЛУГИ ========== -->
    <section class="services-section">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <span class="section-subtitle">Сервис и поддержка</span>
                <h2 class="section-title">Наши <span class="gold-text">услуги</span></h2>
            </div>

            <div class="services-grid">
                <div class="service-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-icon">
                        <i class="fas fa-key"></i>
                    </div>
                    <h3>Тест-драйв</h3>
                    <p>Почувствуйте мощь и комфорт автомобиля перед покупкой</p>
                    <a href="/cars/testdrive" class="service-link">Записаться <i class="fas fa-arrow-right"></i></a>
                </div>

                <div class="service-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h3>Сервисное обслуживание</h3>
                    <p>Профессиональное обслуживание премиальных автомобилей</p>
                    <a href="/services" class="service-link">Подробнее <i class="fas fa-arrow-right"></i></a>
                </div>

                <div class="service-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="service-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <h3>Trade-in</h3>
                    <p>Выгодный обмен вашего автомобиля на новый</p>
                    <a href="/profile/tradein" class="service-link">Оценить <i class="fas fa-arrow-right"></i></a>
                </div>

                <div class="service-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="service-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Кредит и лизинг</h3>
                    <p>Выгодные условия финансирования для бизнеса и частных лиц</p>
                    <a href="/services/leasing" class="service-link">Рассчитать <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== КОНТАКТНАЯ СЕКЦИЯ ========== -->
    <section class="contact-section">
        <div class="container">
            <div class="contact-wrapper">
                <div class="row g-0">
                    <div class="col-lg-6 contact-info" data-aos="fade-right">
                        <div class="contact-info-content">
                            <span class="section-subtitle">Свяжитесь с нами</span>
                            <h2>Начните свой путь<br>к автомобилю <span class="gold-text">мечты</span></h2>
                            <p>Оставьте заявку, и наш персональный консультант свяжется с вами в течение 15 минут</p>

                            <div class="contact-details">
                                <div class="contact-detail-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <div>
                                        <h6>Адрес</h6>
                                        <p>г. Уфа, ул. Адмирала Макарова, 32</p>
                                    </div>
                                </div>
                                <div class="contact-detail-item">
                                    <i class="fas fa-phone-alt"></i>
                                    <div>
                                        <h6>Телефон</h6>
                                        <p><a href="tel:+73471234567">+7 (347) 123-45-67</a></p>
                                    </div>
                                </div>
                                <div class="contact-detail-item">
                                    <i class="fas fa-clock"></i>
                                    <div>
                                        <h6>Режим работы</h6>
                                        <p>Ежедневно: 10:00 — 21:00</p>
                                    </div>
                                </div>
                            </div>

                            <div class="contact-features">
                                <div class="feature-item">
                                    <i class="fas fa-clock"></i>
                                    <span>Консультация 24/7</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-car"></i>
                                    <span>Тест-драйв без выходных</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-percent"></i>
                                    <span>Специальные условия</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-handshake"></i>
                                    <span>Trade-in программа</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 contact-form-wrapper" data-aos="fade-left">
                        <div class="contact-form-card">
                            <h3>Оставьте заявку</h3>
                            <form action="/submit-contact" method="POST" class="contact-form" id="mainContactForm">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="name" placeholder="Ваше имя" required>
                                    <i class="fas fa-user input-icon"></i>
                                </div>

                                <div class="form-group">
                                    <input type="tel" class="form-control" name="phone" placeholder="Телефон" required>
                                    <i class="fas fa-phone input-icon"></i>
                                </div>

                                <div class="form-group">
                                    <input type="email" class="form-control" name="email" placeholder="Email">
                                    <i class="fas fa-envelope input-icon"></i>
                                </div>

                                <div class="form-group">
                                    <select class="form-control" name="request_type" required>
                                        <option value="" selected disabled>Тип заявки</option>
                                        <option value="showroom">Визит в шоу-рум</option>
                                        <option value="test_drive">Тест-драйв</option>
                                        <option value="service">Сервисное обслуживание</option>
                                        <option value="trade_in">Trade-in</option>
                                        <option value="credit">Кредит/Лизинг</option>
                                    </select>
                                    <i class="fas fa-chevron-down input-icon"></i>
                                </div>

                                <div class="form-group">
                                    <textarea class="form-control" name="message" rows="3" placeholder="Сообщение"></textarea>
                                </div>

                                <button type="submit" class="btn btn-gold btn-block">Отправить заявку</button>

                                <p class="form-note">Нажимая кнопку, вы соглашаетесь с <a href="/privacy">политикой конфиденциальности</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== НОВОСТИ ========== -->
    <section class="news-section">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <span class="section-subtitle">Будьте в курсе</span>
                <h2 class="section-title">Последние <span class="gold-text">новости</span></h2>
            </div>

            <div class="news-grid">
                <div class="news-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="news-image">
                        <img src="/assets/images/news/mercedes-s-class.jpg" alt="Mercedes-Benz S-Class">
                        <span class="news-category">Новинка</span>
                    </div>
                    <div class="news-content">
                        <div class="news-date">
                            <i class="far fa-calendar-alt"></i> 15 марта 2026
                        </div>
                        <h4>Новый Mercedes-Benz S-Class 2026 уже в салоне</h4>
                        <p>Флагманский седан нового поколения с инновационными технологиями и непревзойденным комфортом.</p>
                        <a href="/news/1" class="news-link">Подробнее <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <div class="news-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="news-image">
                        <img src="/assets/images/news/bmw-7series.jpg" alt="BMW 7 Series">
                        <span class="news-category">Мероприятие</span>
                    </div>
                    <div class="news-content">
                        <div class="news-date">
                            <i class="far fa-calendar-alt"></i> 10 марта 2026
                        </div>
                        <h4>Закрытый показ BMW 7 Series</h4>
                        <p>Приглашаем на эксклюзивное мероприятие, где вы первыми увидите новый флагман BMW.</p>
                        <a href="/news/2" class="news-link">Подробнее <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <div class="news-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="news-image">
                        <img src="/assets/images/news/porsche-panamera.jpg" alt="Porsche Panamera">
                        <span class="news-category">Обзор</span>
                    </div>
                    <div class="news-content">
                        <div class="news-date">
                            <i class="far fa-calendar-alt"></i> 5 марта 2026
                        </div>
                        <h4>Porsche Panamera: обновленная версия с гибридной установкой</h4>
                        <p>Мощность 680 л.с., разгон до 100 км/ч за 3.3 секунды. Принимаем предзаказы.</p>
                        <a href="/news/3" class="news-link">Подробнее <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="/news" class="btn btn-outline-gold">Все новости <i class="fas fa-arrow-right ms-2"></i></a>
            </div>
        </div>
    </section>