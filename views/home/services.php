<div class="container py-5 mt-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="section-title text-center mb-5">Сервисное обслуживание</h1>
            
            <div class="card border-0 shadow mb-4">
                <div class="card-body p-5">
                    <h3 class="mb-4">Запись на сервис</h3>
                    
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <form action="/services/book" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Марка автомобиля</label>
                                <input type="text" class="form-control" name="car_brand" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Модель</label>
                                <input type="text" class="form-control" name="car_model" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Год выпуска</label>
                                <input type="number" class="form-control" name="car_year" min="1990" max="2024" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Тип обслуживания</label>
                                <select class="form-select" name="service_type" required>
                                    <option value="">Выберите тип</option>
                                    <option value="to">Техническое обслуживание</option>
                                    <option value="repair">Ремонт</option>
                                    <option value="diagnostics">Диагностика</option>
                                    <option value="other">Другое</option>
                                </select>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Дата</label>
                                    <input type="date" class="form-control" name="date" min="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Время</label>
                                    <select class="form-select" name="time" required>
                                        <option value="">Выберите время</option>
                                        <option value="09:00">09:00</option>
                                        <option value="10:00">10:00</option>
                                        <option value="11:00">11:00</option>
                                        <option value="12:00">12:00</option>
                                        <option value="14:00">14:00</option>
                                        <option value="15:00">15:00</option>
                                        <option value="16:00">16:00</option>
                                        <option value="17:00">17:00</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Описание проблемы</label>
                                <textarea class="form-control" name="description" rows="4" placeholder="Опишите проблему или вид работ..."></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-gold btn-lg w-100">Записаться на сервис</button>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            Для записи на сервис необходимо <a href="/auth/login" class="alert-link">войти</a> или <a href="/auth/register" class="alert-link">зарегистрироваться</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Информация о сервисе -->
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card text-center p-3">
                        <i class="fas fa-clock fa-2x text-gold mb-2"></i>
                        <h6>Время работы</h6>
                        <p class="small">Пн-Пт: 9:00-21:00<br>Сб-Вс: 10:00-19:00</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card text-center p-3">
                        <i class="fas fa-phone-alt fa-2x text-gold mb-2"></i>
                        <h6>Телефон сервиса</h6>
                        <p class="small">+7 (495) 123-45-67<br>+7 (495) 765-43-21</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card text-center p-3">
                        <i class="fas fa-tools fa-2x text-gold mb-2"></i>
                        <h6>Специалисты</h6>
                        <p class="small">Сертифицированные мастера<br>с опытом от 5 лет</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>