<?php
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация | Umbra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        body {
            background: var(--primary-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .auth-container {
            max-width: 480px;
            width: 100%;
        }
        
        .auth-card {
            background: white;
            border-radius: 12px;
            padding: 25px 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        
        .auth-logo {
            text-align: center;
            margin-bottom: 32px;
        }
        
                .auth-logo-img {
            height: 80px;
            width: auto;
        }
        
        .auth-title {
            font-size: 24px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
        }
        
        .auth-subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 28px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 8px;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s;
            background: white;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--accent-gold);
            box-shadow: 0 0 0 3px rgba(196,167,71,0.1);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        
        .checkbox-group {
            margin: 20px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .checkbox-group input {
            width: 16px;
            height: 16px;
            margin: 0;
            cursor: pointer;
            accent-color: var(--accent-gold);
        }
        
        .checkbox-group label {
            font-size: 13px;
            color: #666;
            margin: 0;
            cursor: pointer;
        }
        
        .checkbox-group a {
            color: var(--accent-gold);
            text-decoration: none;
        }
        
        .checkbox-group a:hover {
            text-decoration: underline;
        }
        
        .btn-auth {
            width: 100%;
            padding: 12px;
            background: var(--accent-gold);
            border: none;
            border-radius: 8px;
            color: var(--text-dark);
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 8px;
        }
        
        .btn-auth:hover {
            background: #d4b55c;
            transform: translateY(-1px);
        }
        
        .auth-footer {
            margin-top: 24px;
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .auth-footer a {
            color: var(--accent-gold);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }
        
        .auth-footer a:hover {
            text-decoration: underline;
        }
        
        .back-link {
            display: inline-block;
            margin-top: 16px;
            color: #888;
            font-size: 13px;
            text-decoration: none;
        }
        
        .back-link:hover {
            color: var(--accent-gold);
        }
        
        .alert-custom {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-error {
            background: #fee2e2;
            border-left: 3px solid #dc2626;
            color: #991b1b;
        }
        
        @media (max-width: 480px) {
            .auth-card {
                padding: 32px 24px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
            
            .auth-title {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-logo">
                <img src="/assets/images/logo/Group.png" alt="UMBRA Logo" class="auth-logo-img">
            </div>
            
            <h1 class="auth-title">Регистрация</h1>
            <p class="auth-subtitle">Создайте новый аккаунт</p>
            
            <?php if($error): ?>
                <div class="alert-custom alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="/auth/register">
                <div class="form-row">
                    <div class="form-group">
                        <label>Имя *</label>
                        <input type="text" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Телефон</label>
                    <input type="tel" name="phone" placeholder="+7 (___) ___-__-__">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Пароль *</label>
                        <input type="password" name="password" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Подтверждение *</label>
                        <input type="password" name="confirm_password" required>
                    </div>
                </div>
                
                <div class="checkbox-group">
                    <input type="checkbox" name="agree" id="agree" required>
                    <label for="agree">
                        Я согласен с <a href="/terms" target="_blank">условиями использования</a>
                    </label>
                </div>
                
                <button type="submit" class="btn-auth">
                    <i class="fas fa-user-plus me-2"></i> Зарегистрироваться
                </button>
            </form>
            
            <div class="auth-footer">
                <a href="/auth/login">Уже есть аккаунт? Войти</a>
            </div>
            
            <div class="text-center">
                <a href="/" class="back-link">
                    <i class="fas fa-arrow-left me-1"></i> На главную
                </a>
            </div>
        </div>
    </div>
</body>
</html>