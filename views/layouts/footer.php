<?php
// footer.php - ИСПРАВЛЕННАЯ ВЕРСИЯ
?>

    <!-- Футер -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="footer-logo">
                        <span class="brand-text">UMBRA</span>
                        <span class="brand-subtext">Premium Auto</span>
                    </div>
                    <p class="footer-description">
                        Официальный дилер премиальных автомобилей. 
                        Эксклюзивное обслуживание и индивидуальный подход к каждому клиенту.
                    </p>
                    <div class="social-links">
                        <a href="https://t.me/umbra_auto" class="social-link" target="_blank" rel="noopener noreferrer"><i class="fab fa-telegram"></i></a>
                        <a href="https://wa.me/79270000000" class="social-link" target="_blank" rel="noopener noreferrer"><i class="fab fa-whatsapp"></i></a>
                        <a href="https://www.instagram.com/umbra_auto" class="social-link" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.youtube.com/@umbra_auto" class="social-link" target="_blank" rel="noopener noreferrer"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Навигация</h5>
                    <ul class="footer-links">
                        <li><a href="/">Главная</a></li>
                        <li><a href="/cars">Каталог</a></li>
                        <li><a href="/about">О компании</a></li>
                        <li><a href="/news">Новости</a></li>
                        <li><a href="/contacts">Контакты</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Услуги</h5>
                    <ul class="footer-links">
                        <li><a href="/cars/testdrive">Тест-драйв</a></li>
                        <li><a href="/services">Сервис</a></li>
                        <li><a href="/profile/tradein">Trade-in</a></li>
                        <li><a href="/services/leasing">Лизинг</a></li>
                        <li><a href="/services/insurance">Страхование</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5>Контакты</h5>
                    <ul class="footer-contacts">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span>г. Москва, Кутузовский проспект, 48</span>
                        </li>
                        <li>
                            <i class="fas fa-phone"></i>
                            <a href="tel:+74951234567">+7 (495) 123-45-67</a>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <a href="mailto:info@umbra.ru">info@umbra.ru</a>
                        </li>
                        <li>
                            <i class="fas fa-clock"></i>
                            <span>Ежедневно: 10:00 - 22:00</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="row">
                    <div class="col-md-6">
                        <p>&copy; <?php echo date('Y'); ?> Umbra Premium Auto. Все права защищены.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="/privacy">Политика конфиденциальности</a>
                        <a href="/terms">Пользовательское соглашение</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom JS -->
    <script src="/assets/js/main.js"></script>
    
    <script>
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });
    </script>
</body>
</html>