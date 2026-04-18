<div class="container py-5 mt-5">
    <div class="row">
        <div class="col-lg-3 mb-4">
            <?php include 'views/profile/sidebar.php'; ?>
        </div>
        
        <div class="col-lg-9">
            <div class="profile-content card">
                <div class="card-body">
                    <h3 class="mb-4">Настройки аккаунта</h3>
                    
                    <form action="/profile/update-password" method="POST">
                        <h5 class="mb-3">Смена пароля</h5>
                        <div class="mb-3">
                            <label class="form-label">Текущий пароль</label>
                            <input type="password" class="form-control" name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Новый пароль</label>
                            <input type="password" class="form-control" name="new_password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Подтверждение пароля</label>
                            <input type="password" class="form-control" name="confirm_password" required>
                        </div>
                        <button type="submit" class="btn btn-gold">Изменить пароль</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>