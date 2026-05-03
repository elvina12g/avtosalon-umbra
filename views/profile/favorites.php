<div class="container py-5 mt-5">
    <div class="row">
        <div class="col-lg-3 mb-4">
            <?php include 'views/profile/sidebar.php'; ?>
        </div>
        
        <div class="col-lg-9">
            <div class="profile-content card">
                <div class="card-body">
                    <h3 class="mb-4">Избранные автомобили</h3>
                    
                    <?php if(!empty($favorites)): ?>
                        <div class="row">
                            <?php foreach($favorites as $car): ?>
                            <div class="col-md-4 mb-4">
                                <div class="favorite-card">
                                    <?php 
                                    $imagePath = !empty($car['image']) && file_exists('uploads/cars/' . $car['image']) 
                                        ? '/uploads/cars/' . $car['image'] 
                                        : '/assets/images/cars/default-car.jpg';
                                    ?>
                                    <img src="<?php echo $imagePath; ?>" class="img-fluid" alt="<?php echo $car['brand'] . ' ' . $car['model']; ?>">
                                    <h5><?php echo $car['brand'] . ' ' . $car['model']; ?></h5>
                                    <p class="price"><?php echo number_format($car['price'], 0, '.', ' '); ?> ₽</p>
                                    <div class="d-flex gap-2">
                                        <a href="/cars/detail/<?php echo $car['id']; ?>" class="btn btn-outline-gold btn-sm">Подробнее</a>
                                        <button class="btn btn-outline-danger btn-sm remove-favorite" data-car-id="<?php echo $car['id']; ?>">
                                            <i class="fas fa-trash"></i> Удалить
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-heart-broken fa-4x text-muted mb-3"></i>
                            <p class="text-muted">У вас пока нет избранных автомобилей</p>
                            <a href="/cars" class="btn btn-gold">Перейти в каталог</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.favorite-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    transition: transform 0.3s;
}
.favorite-card:hover {
    transform: translateY(-5px);
}
.favorite-card img {
    height: 180px;
    width: 100%;
    object-fit: cover;
}
.favorite-card h5 {
    padding: 15px 15px 5px;
    margin: 0;
    font-size: 16px;
}
.favorite-card .price {
    padding: 0 15px;
    color: var(--accent-gold);
    font-weight: bold;
    margin-bottom: 15px;
}
.favorite-card .d-flex {
    padding: 0 15px 15px;
}
</style>

<script>
document.querySelectorAll('.remove-favorite').forEach(btn => {
    btn.addEventListener('click', function() {
        const carId = this.dataset.carId;
        if(confirm('Удалить автомобиль из избранного?')) {
            fetch(`/cars/favorite/remove/${carId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json())
              .then(data => {
                  if(data.success) {
                      location.reload();
                  }
              });
        }
    });
});
</script>