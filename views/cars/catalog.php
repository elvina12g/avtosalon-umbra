<div class="container py-5 mt-5">
    <div class="row mb-5">
        <div class="col-12 text-center" data-aos="fade-up">
            <h1 class="section-title mb-4">Каталог автомобилей</h1>
            <p class="lead mx-auto" style="max-width: 700px;">
                Выберите свой идеальный автомобиль из нашей коллекции премиальных моделей
            </p>
        </div>
    </div>
    
    <div class="row">
        <!-- Фильтры (боковая панель) -->
        <div class="col-lg-3 mb-4">
            <div class="filters-sidebar">
                <h4 class="mb-4">Фильтры</h4>
                <form id="filter-form" method="GET" action="/cars">
                    <div class="filter-group">
                        <h5><i class="fas fa-car me-2"></i> Марка</h5>
                        <select class="form-select" name="brand" id="filter-brand">
                            <option value="">Все марки</option>
                            <?php foreach($brands as $brand): ?>
                                <option value="<?php echo htmlspecialchars($brand); ?>" 
                                    <?php echo ($_GET['brand'] ?? '') == $brand ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($brand); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <h5><i class="fas fa-ruble-sign me-2"></i> Цена</h5>
                        <div class="price-range">
                            <input type="number" class="form-control" name="min_price" 
                                   placeholder="от" value="<?php echo htmlspecialchars($_GET['min_price'] ?? ''); ?>">
                            <input type="number" class="form-control" name="max_price" 
                                   placeholder="до" value="<?php echo htmlspecialchars($_GET['max_price'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="filter-group">
                        <h5><i class="fas fa-calendar-alt me-2"></i> Год выпуска</h5>
                        <select class="form-select" name="year" id="filter-year">
                            <option value="">Все года</option>
                            <?php 
                            $currentYear = date('Y');
                            for($y = $currentYear; $y >= 2015; $y--):
                            ?>
                                <option value="<?php echo $y; ?>" 
                                    <?php echo ($_GET['year'] ?? '') == $y ? 'selected' : ''; ?>>
                                    <?php echo $y; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-gold w-100 mb-2">
                        <i class="fas fa-search me-2"></i> Применить фильтры
                    </button>
                    <a href="/cars" class="btn btn-outline-gold w-100">
                        <i class="fas fa-undo-alt me-2"></i> Сбросить
                    </a>
                </form>
            </div>
        </div>
        
        <!-- Список автомобилей -->
        <div class="col-lg-9">
            <div class="row g-4" id="cars-list">
                <?php if(!empty($cars)): ?>
                    <?php foreach($cars as $index => $car): ?>
                        <div class="col-md-6 col-xl-4" data-aos="fade-up" data-aos-delay="<?php echo $index * 50; ?>">
                            <div class="car-card">
                                <div class="car-image">
                                    <?php 
                                    $imagePath = !empty($car['image']) && file_exists('uploads/cars/' . $car['image']) 
                                        ? '/uploads/cars/' . $car['image'] 
                                        : '/assets/images/cars/default-car.jpg';
                                    ?>
                                    <img src="<?php echo $imagePath; ?>" 
                                         alt="<?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?>" 
                                         class="img-fluid">
                                    <div class="car-overlay">
                                        <a href="/cars/testdrive/<?php echo $car['id']; ?>" class="btn btn-gold btn-sm">
                                            <i class="fas fa-key"></i> Тест-драйв
                                        </a>
                                        <a href="/cars/detail/<?php echo $car['id']; ?>" class="btn btn-outline-gold btn-sm">
                                            <i class="fas fa-info-circle"></i> Подробнее
                                        </a>
                                    </div>
                                    <?php if($car['is_popular']): ?>
                                        <span class="badge-popular">Популярный</span>
                                    <?php endif; ?>
                                </div>
                                <div class="car-info">
                                    <h4><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></h4>
                                    <p class="car-price"><?php echo number_format($car['price'], 0, '.', ' '); ?> ₽</p>
                                    <div class="car-specs">
                                        <span><i class="fas fa-calendar"></i> <?php echo $car['year']; ?></span>
                                        <span><i class="fas fa-tachometer-alt"></i> <?php echo number_format($car['mileage'] ?? 0, 0, '.', ' '); ?> км</span>
                                        <span><i class="fas fa-gas-pump"></i> <?php echo htmlspecialchars($car['engine'] ?? '—'); ?></span>
                                    </div>
                                    <div class="car-specs mt-2">
                                        <span><i class="fas fa-cogs"></i> 
                                            <?php 
                                            $trans = $car['transmission'] ?? 'automatic';
                                            if($trans == 'automatic') echo 'Автомат';
                                            elseif($trans == 'manual') echo 'Механика';
                                            else echo 'Робот';
                                            ?>
                                        </span>
                                        <span><i class="fas fa-palette"></i> <?php echo htmlspecialchars($car['color'] ?? '—'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="empty-state text-center py-5">
                            <i class="fas fa-car-side fa-4x text-muted mb-3"></i>
                            <h3>Автомобили не найдены</h3>
                            <p class="text-muted">Попробуйте изменить параметры фильтрации</p>
                            <a href="/cars" class="btn btn-gold mt-3">Сбросить фильтры</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.filters-sidebar {
    background: white;
    padding: 25px;
    border-radius: 16px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    position: sticky;
    top: 100px;
}

.filter-group {
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.filter-group:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.filter-group h5 {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 15px;
    color: var(--primary-dark);
}

.price-range {
    display: flex;
    gap: 10px;
}

.price-range input {
    width: 50%;
    text-align: center;
}

.badge-popular {
    position: absolute;
    top: 15px;
    right: 15px;
    background: var(--accent-gold);
    color: var(--primary-dark);
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    z-index: 2;
}

.empty-state {
    background: white;
    border-radius: 16px;
    padding: 60px 20px;
}

@media (max-width: 992px) {
    .filters-sidebar {
        position: static;
    }
}
</style>

<script>
$(document).ready(function() {
    // AJAX фильтрация (опционально)
    $('#filter-form').on('submit', function(e) {
        // Можно оставить обычную отправку или добавить AJAX
        // Пока оставляем обычную
    });
});
</script>