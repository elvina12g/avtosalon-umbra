// main.js - обновленная версия с плавными анимациями

document.addEventListener('DOMContentLoaded', function() {
    // Инициализация AOS анимаций
    AOS.init({
        duration: 1000,
        once: true,
        offset: 100,
        easing: 'ease-out-cubic'
    });

    // Слайдер для популярных моделей
    const carsSlider = document.getElementById('carsSlider');
    const prevSlide = document.getElementById('prevSlide');
    const nextSlide = document.getElementById('nextSlide');

    if (carsSlider && prevSlide && nextSlide) {
        let scrollAmount = 0;
        const slideWidth = document.querySelector('.car-slide')?.offsetWidth || 320;
        const gap = 30;

        prevSlide.addEventListener('click', () => {
            scrollAmount = Math.max(0, scrollAmount - (slideWidth + gap));
            carsSlider.scrollTo({
                left: scrollAmount,
                behavior: 'smooth'
            });
        });

        nextSlide.addEventListener('click', () => {
            const maxScroll = carsSlider.scrollWidth - carsSlider.clientWidth;
            scrollAmount = Math.min(maxScroll, scrollAmount + (slideWidth + gap));
            carsSlider.scrollTo({
                left: scrollAmount,
                behavior: 'smooth'
            });
        });

        // Обновляем scrollAmount при ручном скролле
        carsSlider.addEventListener('scroll', () => {
            scrollAmount = carsSlider.scrollLeft;
        });
    }

    // Плавная прокрутка для скролл-индикатора
    const scrollIndicator = document.querySelector('.scroll-indicator');
    if (scrollIndicator) {
        scrollIndicator.addEventListener('click', () => {
            window.scrollTo({
                top: window.innerHeight,
                behavior: 'smooth'
            });
        });
    }

    // Анимация чисел при скролле
    function animateValue(element, start, end, duration) {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            const current = Math.floor(progress * (end - start) + start);
            const originalText = element.innerText;
            const hasPlus = originalText.includes('+');
            element.innerText = current + (hasPlus ? '+' : '');
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    }

    // Запускаем анимацию чисел при появлении в поле зрения
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const stats = entry.target.querySelectorAll('.stat-number');
                stats.forEach(stat => {
                    const target = parseInt(stat.innerText);
                    if (!isNaN(target)) {
                        animateValue(stat, 0, target, 2000);
                    }
                });
                observer.unobserve(entry.target);
            }
        });
    });

    const heroStats = document.querySelector('.hero-stats');
    if (heroStats) observer.observe(heroStats);

    // Эффект для навигации при скролле
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    }

    // Валидация формы обратной связи
    const contactForm = document.getElementById('mainContactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const name = this.querySelector('[name="name"]').value.trim();
            const phone = this.querySelector('[name="phone"]').value.trim();

            if (!name || !phone) {
                showNotification('Пожалуйста, заполните обязательные поля', 'error');
                return;
            }

            // Имитация отправки
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Отправка...';
            submitBtn.disabled = true;

            setTimeout(() => {
                showNotification('Заявка успешно отправлена! Менеджер свяжется с вами.', 'success');
                this.reset();
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 1500);
        });
    }

    // Функция уведомлений
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `flash-message ${type}`;
        notification.innerHTML = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Ленивая загрузка изображений
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                imageObserver.unobserve(img);
            }
        });
    });

    images.forEach(img => imageObserver.observe(img));
});