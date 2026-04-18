<div class="container py-5 mt-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body p-5">
                    <h1 class="text-center mb-4">Запись на тест-драйв</h1>
                    
                    <?php if($selectedCar): ?>
                        <div class="alert alert-info text-center">
                            Вы записываетесь на тест-драйв: <strong><?php echo $selectedCar['brand'] . ' ' . $selectedCar['model']; ?></strong>
                        </div>
                    <?php endif; ?>
                    
                    <form id="test-drive-form" method="POST" action="/cars/testdrive">
                        <?php if(!isset($_SESSION['user_id'])): ?>
                            <div class="alert alert-warning">
                                Для записи на тест-драйв необходимо <a href="/auth/login" class="alert-link">войти</a> или <a href="/auth/register" class="alert-link">зарегистрироваться</a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label class="form-label">Выберите автомобиль</label>
                            <select class="form-select" name="car_id" required>
                                <option value="">-- Выберите автомобиль --</option>
                                <?php foreach($cars as $car): ?>
                                    <option value="<?php echo $car['id']; ?>" <?php echo ($selectedCar && $selectedCar['id'] == $car['id']) ? 'selected' : ''; ?>>
                                        <?php echo $car['brand'] . ' ' . $car['model'] . ' (' . $car['year'] . ')'; ?>
                                    </option>
                                <?php endforeach; ?>
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
                                    <option value="">-- Выберите время --</option>
                                    <option value="10:00">10:00</option>
                                    <option value="11:00">11:00</option>
                                    <option value="12:00">12:00</option>
                                    <option value="14:00">14:00</option>
                                    <option value="15:00">15:00</option>
                                    <option value="16:00">16:00</option>
                                    <option value="17:00">17:00</option>
                                    <option value="18:00">18:00</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Ваш телефон</label>
                            <input type="tel" class="form-control" name="phone" value="<?php echo $_SESSION['user_phone'] ?? ''; ?>" placeholder="+7 (___) ___-__-__" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Комментарий (необязательно)</label>
                            <textarea class="form-control" name="comment" rows="3" placeholder="Особые пожелания..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-gold btn-lg w-100" <?php echo !isset($_SESSION['user_id']) ? 'disabled' : ''; ?>>
                            Записаться на тест-драйв
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>