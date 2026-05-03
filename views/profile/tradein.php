<div class="container py-5 mt-5">
    <div class="row">
        <!-- Боковое меню профиля -->
        <div class="col-lg-3 mb-4">
            <div class="profile-sidebar card">
                <div class="card-body text-center">
                    <div class="profile-avatar mb-3">
                        <i class="fas fa-user-circle fa-5x text-gold"></i>
                    </div>
                    <h4><?php echo $_SESSION['user_name'] ?? 'Пользователь'; ?></h4>
                    <p class="text-muted"><?php echo $_SESSION['user_email'] ?? ''; ?></p>
                    <hr>
                    <div class="profile-menu">
                        <a href="/profile" class="profile-menu-item">
                            <i class="fas fa-id-card"></i> Личные данные
                        </a>
                        <a href="/profile/testdrives" class="profile-menu-item">
                            <i class="fas fa-car"></i> Мои тест-драйвы
                        </a>
                        <a href="/profile/favorites" class="profile-menu-item">
                            <i class="fas fa-heart"></i> Избранное
                        </a>
                        <a href="/profile/services" class="profile-menu-item">
                            <i class="fas fa-tools"></i> Сервисное обслуживание
                        </a>
                        <a href="/profile/tradein" class="profile-menu-item active">
                            <i class="fas fa-exchange-alt"></i> Trade-in
                        </a>
                        <a href="/profile/settings" class="profile-menu-item">
                            <i class="fas fa-cog"></i> Настройки
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Основной контент -->
        <div class="col-lg-9">
            <div class="profile-content card">
                <div class="card-body">
                    <h3 class="mb-4">Trade-in</h3>
                    <p class="lead mb-4">Обменяйте ваш старый автомобиль на новый с доплатой</p>
                    
                    <form action="/profile/tradein/submit" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Марка автомобиля</label>
                                <input type="text" class="form-control" name="car_brand" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Модель</label>
                                <input type="text" class="form-control" name="car_model" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Год выпуска</label>
                                <input type="number" class="form-control" name="car_year" min="1990" max="2025" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Пробег (км)</label>
                                <input type="number" class="form-control" name="mileage" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Желаемый автомобиль</label>
                                <input type="text" class="form-control" name="desired_car" placeholder="Например: Mercedes-Benz S-Class">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Состояние автомобиля</label>
                                <textarea class="form-control" name="condition" rows="3" placeholder="Опишите состояние, комплектацию, особенности..."></textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Фотографии автомобиля</label>
                                <input type="file" class="form-control" name="photos[]" multiple accept="image/*">
                                <small class="text-muted">Можно загрузить несколько фото</small>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-gold">Отправить заявку на оценку</button>
                            </div>
                        </div>
                    </form>
                    
                    <hr class="my-4">
                    
                    <h4 class="mb-3">Преимущества Trade-in в Umbra</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <i class="fas fa-check-circle text-gold me-3 mt-1"></i>
                                <div>
                                    <h6>Быстрая оценка</h6>
                                    <p class="text-muted small">Оценка автомобиля за 30 минут</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <i class="fas fa-check-circle text-gold me-3 mt-1"></i>
                                <div>
                                    <h6>Выгодная цена</h6>
                                    <p class="text-muted small">Максимальная стоимость вашего авто</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <i class="fas fa-check-circle text-gold me-3 mt-1"></i>
                                <div>
                                    <h6>Все документы</h6>
                                    <p class="text-muted small">Поможем с оформлением</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <i class="fas fa-check-circle text-gold me-3 mt-1"></i>
                                <div>
                                    <h6>Экономия времени</h6>
                                    <p class="text-muted small">Не нужно искать покупателя</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>