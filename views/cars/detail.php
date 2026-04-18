<div class="container py-5 mt-5">
    <?php if($car): ?>
        <div class="row">
            <!-- Галерея фото -->
            <div class="col-lg-7 mb-4">
                <div class="car-gallery">
                    <div class="car-gallery-main">
                        <?php 
                        $mainImage = '/assets/images/cars/' . ($car['image'] ?? 'default-car.jpg');
                        ?>
                        <img src="<?php echo $mainImage; ?>" class="img-fluid rounded" alt="<?php echo $car['brand'] . ' ' . $car['model']; ?>">
                    </div>
                    
                    <!-- Миниатюры (если есть дополнительные фото) -->
                    <?php if(!empty($car['images'])): ?>
                    <div class="car-gallery-thumbs mt-3">
                        <?php 
                        $images = json_decode($car['images'], true);
                        if(is_array($images)):
                            foreach($images as $img): 
                        ?>
                        <div class="car-gallery-thumb">
                            <img src="/assets/images/cars/<?php echo $img; ?>" class="img-fluid rounded" alt="">
                        </div>
                        <?php 
                            endforeach;
                        endif;
                        ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Информация об авто -->
            <div class="col-lg-5 mb-4">
                <div class="car-info-card bg-white p-4 rounded shadow">
                    <h1 class="mb-3"><?php echo $car['brand'] . ' ' . $car['model']; ?></h1>
                    <div class="car-price-display mb-4">
                        <span class="display-6 text-gold"><?php echo number_format($car['price'], 0, '.', ' '); ?> ₽</span>
                    </div>
                    
                    <table class="car-specs-table mb-4">
                        <tr>
                            <td>Год выпуска:</td>
                            <td><strong><?php echo $car['year']; ?></strong></td>
                        </tr>
                        <tr>
                            <td>Пробег:</td>
                            <td><strong><?php echo number_format($car['mileage'] ?? 0, 0, '.', ' '); ?> км</strong></td>
                        </tr>
                        <tr>
                            <td>Двигатель:</td>
                            <td><strong><?php echo $car['engine'] ?? '—'; ?></strong></td>
                        </tr>
                        <tr>
                            <td>Мощность:</td>
                            <td><strong><?php echo $car['power'] ?? '—'; ?> л.с.</strong></td>
                        </tr>
                        <tr>
                            <td>Коробка передач:</td>
                            <td><strong>
                                <?php 
                                $trans = $car['transmission'] ?? 'automatic';
                                if($trans == 'automatic') echo 'Автомат';
                                elseif($trans == 'manual') echo 'Механика';
                                else echo 'Робот';
                                ?>
                            </strong></td>
                        </tr>
                        <tr>
                            <td>Привод:</td>
                            <td><strong>
                                <?php 
                                $drive = $car['drive'] ?? 'rear';
                                if($drive == 'front') echo 'Передний';
                                elseif($drive == 'rear') echo 'Задний';
                                else echo 'Полный';
                                ?>
                            </strong></td>
                        </tr>
                        <tr>
                            <td>Цвет:</td>
                            <td><strong><?php echo $car['color'] ?? '—'; ?></strong></td>
                        </tr>
                    </table>
                    
                    <div class="car-description mb-4">
                        <h5>Описание:</h5>
                        <p><?php echo nl2br($car['description'] ?? 'Описание отсутствует'); ?></p>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="/cars/testdrive/<?php echo $car['id']; ?>" class="btn btn-gold btn-lg">Записаться на тест-драйв</a>
                        <button class="btn btn-outline-gold btn-lg add-to-favorite" data-car-id="<?php echo $car['id']; ?>">
                            <i class="far fa-heart"></i> Добавить в избранное
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-danger">
            Автомобиль не найден
        </div>
    <?php endif; ?>
</div>