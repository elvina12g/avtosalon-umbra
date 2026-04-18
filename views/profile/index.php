<div class="container py-5 mt-5">
    <div class="row">
        <!-- Боковое меню -->
        <div class="col-lg-3 mb-4">
            <div class="profile-sidebar card">
                <div class="card-body text-center">
                    <div class="profile-avatar mb-3">
                        <i class="fas fa-user-circle fa-5x text-gold"></i>
                    </div>
                    <h4><?php echo $_SESSION['user_name']; ?></h4>
                    <p class="text-muted"><?php echo $_SESSION['user_email']; ?></p>
                    <hr>
                    <div class="profile-menu">
                        <a href="/profile" class="profile-menu-item active">
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
                        <a href="/profile/tradein" class="profile-menu-item">
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
                    <h3 class="mb-4">Личные данные</h3>
                    
                    <form action="/profile/update" method="POST" class="profile-form">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Имя</label>
                                <input type="text" class="form-control" name="name" value="<?php echo $_SESSION['user_name']; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="<?php echo $_SESSION['user_email']; ?>" disabled>
                                <small class="text-muted">Email нельзя изменить</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Телефон</label>
                                <input type="tel" class="form-control" name="phone" value="<?php echo $userData['phone'] ?? ''; ?>">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-gold">Сохранить изменения</button>
                            </div>
                        </div>
                    </form>
                    
                    <hr class="my-4">
                    
                    <h4 class="mb-3">Последние заявки</h4>
                    <?php if(!empty($testDrives)): ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Автомобиль</th>
                                        <th>Дата</th>
                                        <th>Время</th>
                                        <th>Статус</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach(array_slice($testDrives, 0, 3) as $td): ?>
                                    <tr>
                                        <td><?php echo $td['brand'] . ' ' . $td['model']; ?></td>
                                        <td><?php echo date('d.m.Y', strtotime($td['date'])); ?></td>
                                        <td><?php echo $td['time']; ?></td>
                                        <td>
                                            <?php if($td['status'] == 'pending'): ?>
                                                <span class="badge bg-warning">Ожидает подтверждения</span>
                                            <?php elseif($td['status'] == 'confirmed'): ?>
                                                <span class="badge bg-success">Подтвержден</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Завершен</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <a href="/profile/testdrives" class="btn btn-outline-gold">Все заявки</a>
                    <?php else: ?>
                        <p class="text-muted">У вас пока нет заявок на тест-драйв</p>
                        <a href="/cars/testdrive" class="btn btn-gold">Записаться на тест-драйв</a>
                    <?php endif; ?>
                    
                    <hr class="my-4">
                    
                    <h4 class="mb-3">Избранные автомобили</h4>
                    <?php if(!empty($favorites)): ?>
                        <div class="row">
                            <?php foreach(array_slice($favorites, 0, 3) as $fav): ?>
                            <div class="col-md-4 mb-3">
                                <div class="favorite-card">
                                    <img src="/assets/images/cars/<?php echo $fav['image']; ?>" class="img-fluid" alt="<?php echo $fav['brand'] . ' ' . $fav['model']; ?>">
                                    <h5><?php echo $fav['brand'] . ' ' . $fav['model']; ?></h5>
                                    <p class="price"><?php echo number_format($fav['price'], 0, '.', ' '); ?> ₽</p>
                                    <a href="/cars/detail/<?php echo $fav['id']; ?>" class="btn btn-outline-gold btn-sm">Подробнее</a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <a href="/profile/favorites" class="btn btn-outline-gold">Все избранное</a>
                    <?php else: ?>
                        <p class="text-muted">У вас пока нет избранных автомобилей</p>
                        <a href="/cars" class="btn btn-gold">Перейти в каталог</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>