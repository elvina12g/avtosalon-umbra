<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в админ-панель | Автосалон</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Montserrat', sans-serif;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            max-width: 450px;
            margin: 20px auto;
        }
        
        .login-header {
            background: #1a1a2e;
            color: white;
            text-align: center;
            padding: 30px;
        }
        
        .login-header i {
            font-size: 60px;
            color: #c4a747;
            margin-bottom: 20px;
        }
        
        .login-header h2 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .login-header p {
            color: #999;
            font-size: 14px;
        }
        
        .login-body {
            padding: 40px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e1e1;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .form-group input:focus {
            border-color: #c4a747;
            outline: none;
            box-shadow: 0 0 0 3px rgba(196, 167, 71, 0.1);
        }
        
        .btn-login {
            background: #c4a747;
            color: #1a1a2e;
            border: none;
            padding: 14px;
            width: 100%;
            border-radius: 10px;
            font-weight: 700;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            background: #d4b55c;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(196, 167, 71, 0.3);
        }
        
        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .role-info {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #999;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-card">
            <div class="login-header">
                <i class="fas fa-car"></i>
                <h2>Автосалон Премиум</h2>
                <p>Вход в админ-панель</p>
            </div>
            <div class="login-body">
                <?php if(isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> 
                        <?php 
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                        ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="/auth/login">
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Логин или Email</label>
                        <input type="text" name="username" required autofocus placeholder="Введите логин или email">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-lock"></i> Пароль</label>
                        <input type="password" name="password" required placeholder="Введите пароль">
                    </div>
                    <button type="submit" class="btn-login">
                        <i class="fas fa-sign-in-alt"></i> Войти
                    </button>
                </form>
                
                <div class="role-info">
                    <i class="fas fa-info-circle"></i> Тестовые аккаунты:<br>
                    <strong>Админ:</strong> admin / admin123<br>
                    <strong>Менеджер:</strong> manager / manager123<br>
                    <strong>Консультант:</strong> consultant / consultant123
                </div>
            </div>
        </div>
    </div>
</body>
</html>