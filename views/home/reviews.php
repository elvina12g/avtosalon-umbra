<div class="container py-5 mt-5">
    <!-- Заголовок -->
    <div class="row mb-5">
        <div class="col-12 text-center" data-aos="fade-up">
            <h1 class="section-title mb-4">Отзывы наших клиентов</h1>
            <p class="lead mx-auto" style="max-width: 700px;">Более 5000 довольных клиентов доверили нам свой выбор. Вот что они говорят о нас</p>
        </div>
    </div>

    <!-- Фильтр по рейтингу (опционально) -->
    <div class="row mb-4">
        <div class="col-12 text-center" data-aos="fade-up">
            <div class="btn-group" role="group">
                <button class="btn btn-outline-gold active" data-rating="all">Все отзывы</button>
                <button class="btn btn-outline-gold" data-rating="5">5 звезд</button>
                <button class="btn btn-outline-gold" data-rating="4">4 звезды</button>
                <button class="btn btn-outline-gold" data-rating="3">3 звезды</button>
            </div>
        </div>
    </div>

    <!-- Список отзывов -->
    <div class="row" id="reviews-container">
        <?php
        // Здесь должны быть реальные данные из базы
        // Для примера показываем расширенный список отзывов
        $all_reviews = [
            [
                'id' => 1,
                'name' => 'Александр Петров',
                'car' => 'Mercedes-Benz S-Class',
                'rating' => 5,
                'text' => 'Превосходный сервис! Приобрел Mercedes-Benz S-Class. Особо хочу отметить индивидуальный подход и внимание к деталям. Менеджер подробно рассказал обо всех опциях, помог с выбором комплектации. Оформление заняло минимум времени. Обязательно вернусь за следующим автомобилем!',
                'avatar' => 'avatar1.jpg',
                'date' => '2024-02-15'
            ],
            [
                'id' => 2,
                'name' => 'Дмитрий Иванов',
                'car' => 'BMW X5',
                'rating' => 5,
                'text' => 'Отличный салон! Обслуживаю свой BMW уже 3 года. Всегда качественно и в срок. Рекомендую! Сервисный центр работает безупречно, всегда предупреждают о необходимых работах, цены адекватные. Отдельное спасибо за комфортную зону ожидания.',
                'avatar' => 'avatar2.jpg',
                'date' => '2024-02-10'
            ],
            [
                'id' => 3,
                'name' => 'Елена Соколова',
                'car' => 'Audi Q7',
                'rating' => 4,
                'text' => 'Взяла Audi Q7 в лизинг. Очень удобные условия, помогли с оформлением. Персональный менеджер всегда на связи. Единственное - пришлось немного подождать доставку, но в целом все отлично. Автомобиль полностью соответствует ожиданиям.',
                'avatar' => 'avatar3.jpg',
                'date' => '2024-02-05'
            ],
            [
                'id' => 4,
                'name' => 'Михаил Волков',
                'car' => 'Porsche Cayenne',
                'rating' => 5,
                'text' => 'Купил Porsche Cayenne. Профессиональный подход, отличная организация тест-драйва. Менеджер знает все о своем продукте. Очень доволен покупкой и обслуживанием. Рекомендую этот салон всем, кто ценит качество и комфорт.',
                'avatar' => 'avatar4.jpg',
                'date' => '2024-01-28'
            ],
            [
                'id' => 5,
                'name' => 'Анна Смирнова',
                'car' => 'Mercedes-Benz GLE',
                'rating' => 5,
                'text' => 'Отличный выбор автомобилей, вежливый персонал. Помогли с выбором, учли все пожелания. Процесс покупки был максимально комфортным и прозрачным. Спасибо команде Umbra!',
                'avatar' => 'avatar5.jpg',
                'date' => '2024-01-20'
            ],
            [
                'id' => 6,
                'name' => 'Сергей Николаев',
                'car' => 'BMW 7 Series',
                'rating' => 5,
                'text' => 'Заказывал BMW 7 Series. Все прошло гладко, автомобиль подготовили идеально. Отдельное спасибо за welcome-пакет для нового авто. Приятно удивлен уровнем сервиса.',
                'avatar' => 'avatar6.jpg',
                'date' => '2024-01-15'
            ],
            [
                'id' => 7,
                'name' => 'Ольга Морозова',
                'car' => 'Audi A8',
                'rating' => 4,
                'text' => 'Покупала Audi A8. Хороший салон, профессиональные консультанты. Немного затянулось оформление документов, но в целом все хорошо. Автомобилем довольна, спасибо!',
                'avatar' => 'avatar7.jpg',
                'date' => '2024-01-10'
            ],
            [
                'id' => 8,
                'name' => 'Игорь Федоров',
                'car' => 'Porsche Panamera',
                'rating' => 5,
                'text' => 'Лучший автосалон в Москве! Брал Panamera, все на высшем уровне. От тест-драйва до выдачи авто. Буду рекомендовать друзьям и знакомым. Спасибо!',
                'avatar' => 'avatar8.jpg',
                'date' => '2024-01-05'
            ],
            [
                'id' => 9,
                'name' => 'Татьяна Кузнецова',
                'car' => 'Mercedes-Benz E-Class',
                'rating' => 5,
                'text' => 'Отличный салон! Приятная атмосфера, внимательные менеджеры. Помогли выбрать идеальный вариант в рамках бюджета. Очень довольна покупкой!',
                'avatar' => 'avatar9.jpg',
                'date' => '2023-12-28'
            ]
        ];

        foreach($all_reviews as $review):
        ?>
        <div class="col-lg-4 col-md-6 mb-4 review-item" data-rating="<?php echo $review['rating']; ?>" data-aos="fade-up">
            <div class="review-card h-100">
                <div class="review-rating">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <?php if($i <= $review['rating']): ?>
                            <i class="fas fa-star"></i>
                        <?php else: ?>
                            <i class="far fa-star"></i>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
                <p class="review-text">"<?php echo $review['text']; ?>"</p>
                <div class="review-author">
                    <img src="/assets/images/reviews/<?php echo $review['avatar']; ?>" alt="<?php echo $review['name']; ?>" class="review-avatar" onerror="this.src='/assets/images/reviews/default-avatar.jpg'">
                    <div>
                        <h5><?php echo $review['name']; ?></h5>
                        <span><?php echo $review['car']; ?></span>
                        <small class="text-muted d-block"><?php echo date('d.m.Y', strtotime($review['date'])); ?></small>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Пагинация -->
    <div class="row mt-4">
        <div class="col-12 text-center" data-aos="fade-up">
            <nav aria-label="Навигация по страницам">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Предыдущая</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Следующая</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <!-- Форма добавления отзыва (если пользователь авторизован) -->
    <?php if(isset($_SESSION['user_id'])): ?>
    <div class="row mt-5">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow" data-aos="fade-up">
                <div class="card-body p-5">
                    <h3 class="text-center mb-4">Оставить свой отзыв</h3>
                    
                    <form action="/submit-review" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Оценка</label>
                            <div class="rating-input">
                                <?php for($i = 5; $i >= 1; $i--): ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rating" id="rating<?php echo $i; ?>" value="<?php echo $i; ?>" required>
                                    <label class="form-check-label" for="rating<?php echo $i; ?>"><?php echo $i; ?> звезд</label>
                                </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Ваш отзыв</label>
                            <textarea class="form-control" name="review" rows="5" placeholder="Расскажите о своем опыте..." required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-gold w-100">Отправить отзыв</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="row mt-5">
        <div class="col-lg-8 mx-auto text-center">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Чтобы оставить отзыв, пожалуйста, <a href="/auth/login" class="alert-link">войдите</a> или <a href="/auth/register" class="alert-link">зарегистрируйтесь</a>.
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
.rating-input {
    display: flex;
    gap: 15px;
    justify-content: center;
    padding: 10px 0;
}
.review-card {
    transition: var(--transition);
    height: 100%;
    display: flex;
    flex-direction: column;
}
.review-card:hover {
    transform: translateY(-5px);
}
.review-text {
    flex-grow: 1;
    display: -webkit-box;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.pagination .page-link {
    color: var(--primary-dark);
    border: none;
    margin: 0 5px;
    border-radius: 5px;
}
.pagination .page-link:hover {
    background: var(--accent-gold);
    color: var(--primary-dark);
}
.pagination .page-item.active .page-link {
    background: var(--accent-gold);
    border-color: var(--accent-gold);
    color: var(--primary-dark);
}
</style>

<script>
$(document).ready(function() {
    // Фильтрация отзывов по рейтингу
    $('.btn-group .btn').on('click', function() {
        $('.btn-group .btn').removeClass('active');
        $(this).addClass('active');
        
        var rating = $(this).data('rating');
        
        if(rating === 'all') {
            $('.review-item').fadeIn();
        } else {
            $('.review-item').hide();
            $('.review-item[data-rating="' + rating + '"]').fadeIn();
        }
    });
});
</script>