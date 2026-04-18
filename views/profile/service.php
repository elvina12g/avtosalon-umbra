<div class="container py-5 mt-5">
    <div class="row">
        <div class="col-lg-3 mb-4">
            <?php include 'views/profile/sidebar.php'; ?>
        </div>
        
        <div class="col-lg-9">
            <div class="profile-content card">
                <div class="card-body">
                    <h3 class="mb-4">Запись на сервис</h3>
                    
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
                            <input type="number" class="form-control" name="car_year" min="1990" max="<?php echo date('Y'); ?>" required>
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
                </div>
            </div>
        </div>
    </div>
</div>