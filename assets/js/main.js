// main.js - обновленная версия

document.addEventListener('DOMContentLoaded', function() {
    // Инициализация AOS анимаций
    AOS.init({
        duration: 1000,
        once: true,
        offset: 100
    });

    // Слайдер для популярных моделей
    const carsSlider = document.getElementById('carsSlider');
    const prevSlide = document.getElementById('prevSlide');
    const nextSlide = document.getElementById('nextSlide');

    if (carsSlider && prevSlide && nextSlide) {
        const slideWidth = document.querySelector('.car-slide')?.offsetWidth || 320;
        const gap = 30;
        
        prevSlide.addEventListener('click', () => {
            carsSlider.scrollBy({
                left: -(slideWidth + gap),
                behavior: 'smooth'
            });
        });
        
        nextSlide.addEventListener('click', () => {
            carsSlider.scrollBy({
                left: slideWidth + gap,
                behavior: 'smooth'
            });
        });
    }

    // Карусель отзывов с частично видимыми соседями
    const testimonialsCarousel = document.getElementById('testimonialsCarousel');
    const prevTestimonial = document.getElementById('prevTestimonial');
    const nextTestimonial = document.getElementById('nextTestimonial');
    const testimonialDots = document.querySelectorAll('#testimonialsDots .dot');
    
    if (testimonialsCarousel && prevTestimonial && nextTestimonial) {
        let currentIndex = 0;
        let autoScrollInterval;
        
        function getVisibleItemsCount() {
            if (window.innerWidth >= 992) return 3;
            if (window.innerWidth >= 768) return 2;
            return 1;
        }
        
        function getTotalItems() {
            return document.querySelectorAll('.testimonial-item').length;
        }
        
        function getItemWidth() {
            const item = document.querySelector('.testimonial-item');
            if (!item) return 320;
            const style = getComputedStyle(item);
            const width = item.offsetWidth;
            const gap = 30;
            return width + gap;
        }
        
        function updateCarousel() {
            const itemWidth = getItemWidth();
            const scrollPosition = currentIndex * itemWidth;
            testimonialsCarousel.scrollTo({
                left: scrollPosition,
                behavior: 'smooth'
            });
            
            // Обновляем точки
            testimonialDots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentIndex);
            });
        }
        
        function goToNext() {
            const visibleItems = getVisibleItemsCount();
            const totalItems = getTotalItems();
            const maxIndex = Math.max(0, totalItems - visibleItems);
            
            if (currentIndex < maxIndex) {
                currentIndex++;
            } else {
                currentIndex = 0;
            }
            updateCarousel();
        }
        
        function goToPrev() {
            const visibleItems = getVisibleItemsCount();
            const totalItems = getTotalItems();
            const maxIndex = Math.max(0, totalItems - visibleItems);
            
            if (currentIndex > 0) {
                currentIndex--;
            } else {
                currentIndex = maxIndex;
            }
            updateCarousel();
        }
        
        // Обработчики кнопок
        prevTestimonial.addEventListener('click', () => {
            goToPrev();
            resetAutoScroll();
        });
        
        nextTestimonial.addEventListener('click', () => {
            goToNext();
            resetAutoScroll();
        });
        
        // Обработчики точек
        testimonialDots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentIndex = index;
                updateCarousel();
                resetAutoScroll();
            });
        });
        
        // Автоматическая прокрутка
        function startAutoScroll() {
            autoScrollInterval = setInterval(() => {
                goToNext();
            }, 5000);
        }
        
        function resetAutoScroll() {
            clearInterval(autoScrollInterval);
            startAutoScroll();
        }
        
        // Приостанавливаем автопрокрутку при наведении
        testimonialsCarousel.addEventListener('mouseenter', () => {
            clearInterval(autoScrollInterval);
        });
        
        testimonialsCarousel.addEventListener('mouseleave', () => {
            startAutoScroll();
        });
        
        // Запускаем автопрокрутку
        startAutoScroll();
        
        // Обновляем при изменении размера окна
        window.addEventListener('resize', () => {
            const visibleItems = getVisibleItemsCount();
            const totalItems = getTotalItems();
            const maxIndex = Math.max(0, totalItems - visibleItems);
            
            if (currentIndex > maxIndex) {
                currentIndex = maxIndex;
                updateCarousel();
            }
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