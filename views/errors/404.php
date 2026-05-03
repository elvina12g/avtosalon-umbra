<?php
// views/errors/404.php
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Страница не найдена - Umbra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <?php require_once 'views/layouts/header.php'; ?>
    
    <div class="container py-5 mt-5 text-center">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h1 class="display-1 text-gold">404</h1>
                <h2 class="mb-4">Страница не найдена</h2>
                <p class="lead mb-5">Извините, запрошенная страница не существует или была перемещена.</p>
                <a href="/" class="btn btn-gold btn-lg">Вернуться на главную</a>
            </div>
        </div>
    </div>
    
    <?php require_once 'views/layouts/footer.php'; ?>
</body>
</html>