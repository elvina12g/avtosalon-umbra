<div class="container py-5 mt-5">
    <div class="row">
        <div class="col-lg-3 mb-4">
            <?php include 'views/profile/sidebar.php'; ?>
        </div>
        
        <div class="col-lg-9">
            <div class="profile-content card">
                <div class="card-body">
                    <h3 class="mb-4">Мои тест-драйвы</h3>
                    
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
                                    <?php foreach($testDrives as $td): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo $td['brand'] . ' ' . $td['model']; ?></strong>
                                        </td>
                                        <td><?php echo date('d.m.Y', strtotime($td['date'])); ?></td>
                                        <td><?php echo $td['time']; ?></td>
                                        <td>
                                            <?php if($td['status'] == 'pending'): ?>
                                                <span class="badge bg-warning">Ожидает подтверждения</span>
                                            <?php elseif($td['status'] == 'confirmed'): ?>
                                                <span class="badge bg-success">Подтвержден</span>
                                            <?php elseif($td['status'] == 'completed'): ?>
                                                <span class="badge bg-info">Завершен</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Отменен</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-car fa-4x text-muted mb-3"></i>
                            <p class="text-muted">У вас пока нет заявок на тест-драйв</p>
                            <a href="/cars/testdrive" class="btn btn-gold">Записаться на тест-драйв</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>