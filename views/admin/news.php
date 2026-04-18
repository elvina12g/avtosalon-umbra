<?php
// views/admin/news.php
require_once 'controllers/AuthController.php';
$auth = new AuthController();
$auth->checkAuth();

// Проверяем права (только админ и менеджер)
if(!in_array($_SESSION['user_role'], ['admin', 'manager'])) {
    header('Location: /admin/dashboard');
    exit();
}

require_once 'controllers/NewsController.php';
$newsController = new NewsController($db);

// Обработка действий
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['action'])) {
        // Обработка загрузки изображения
        $image_name = null;
        if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $upload_dir = 'uploads/news/';
            if(!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $image_name = time() . '_' . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name);
        }
        
        switch($_POST['action']) {
            case 'create':
                $news_data = [
                    'title' => $_POST['title'],
                    'content' => $_POST['content'],
                    'excerpt' => $_POST['excerpt'],
                    'category' => $_POST['category'],
                    'image' => $image_name
                ];
                $newsController->create($news_data);
                $_SESSION['success'] = 'Новость успешно добавлена';
                break;
                
            case 'update':
                $news_data = [
                    'title' => $_POST['title'],
                    'content' => $_POST['content'],
                    'excerpt' => $_POST['excerpt'],
                    'category' => $_POST['category']
                ];
                if($image_name) {
                    $news_data['image'] = $image_name;
                }
                $newsController->update($_POST['id'], $news_data);
                $_SESSION['success'] = 'Новость успешно обновлена';
                break;
                
            case 'delete':
                $newsController->delete($_POST['id']);
                $_SESSION['success'] = 'Новость удалена';
                break;
        }
        header('Location: /admin/news');
        exit();
    }
}

$news = $newsController->getAll();
$stats = $newsController->getStats();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление новостями | Админ-панель</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <style>
        .admin-wrapper { display: flex; min-height: 100vh; }
        .admin-sidebar { width: 280px; background: #1a1a2e; color: white; position: fixed; height: 100vh; overflow-y: auto; }
        .admin-content { flex: 1; margin-left: 280px; background: #f8f8f8; }
        .admin-header { background: white; padding: 20px 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; }
        .admin-main { padding: 30px; }
        .sidebar-logo { padding: 30px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-logo h3 { color: #c4a747; font-weight: 700; }
        .sidebar-nav a { display: flex; align-items: center; padding: 12px 25px; color: rgba(255,255,255,0.8); text-decoration: none; transition: all 0.3s; }
        .sidebar-nav a:hover, .sidebar-nav a.active { background: rgba(196, 167, 71, 0.1); color: #c4a747; border-left: 3px solid #c4a747; }
        .sidebar-nav a i { width: 25px; margin-right: 10px; }
        .btn-gold { background: #c4a747; color: #1a1a2e; border: none; }
        .btn-gold:hover { background: #d4b55c; color: #1a1a2e; }
        .news-image-preview { width: 80px; height: 60px; object-fit: cover; border-radius: 5px; }
        .stats-card { background: white; border-radius: 10px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .stats-number { font-size: 28px; font-weight: 700; color: #c4a747; }
        @media (max-width: 768px) { .admin-sidebar { transform: translateX(-100%); transition: transform 0.3s; z-index: 1000; } .admin-sidebar.open { transform: translateX(0); } .admin-content { margin-left: 0; } }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <?php include 'views/admin/sidebar.php'; ?>
        
        <div class="admin-content">
            <div class="admin-header">
                <button class="btn btn-link d-md-none" id="menuToggle"><i class="fas fa-bars"></i></button>
                <div><h4 class="mb-0">Управление новостями</h4></div>
                <button class="btn btn-gold" data-bs-toggle="modal" data-bs-target="#createNewsModal">
                    <i class="fas fa-plus"></i> Добавить новость
                </button>
            </div>
            
            <div class="admin-main">
                <?php if(isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <!-- Статистика -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="stats-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="stats-number"><?php echo $stats['total']; ?></div>
                                    <div>Всего новостей</div>
                                </div>
                                <i class="fas fa-newspaper fa-3x text-gold"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stats-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="stats-number"><?php echo $stats['most_viewed']['views'] ?? 0; ?></div>
                                    <div>Макс. просмотров</div>
                                    <small class="text-muted"><?php echo $stats['most_viewed']['title'] ?? '-'; ?></small>
                                </div>
                                <i class="fas fa-eye fa-3x text-gold"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stats-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="stats-number"><?php echo count($stats['by_category']); ?></div>
                                    <div>Категорий</div>
                                </div>
                                <i class="fas fa-tags fa-3x text-gold"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Список новостей -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Фото</th>
                                        <th>ID</th>
                                        <th>Заголовок</th>
                                        <th>Категория</th>
                                        <th>Дата</th>
                                        <th>Просмотры</th>
                                        <th>Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = $news->fetch(PDO::FETCH_ASSOC)): ?>
                                    <tr>
                                        <td>
                                            <?php if($row['image']): ?>
                                                <img src="/uploads/news/<?php echo $row['image']; ?>" class="news-image-preview" alt="">
                                            <?php else: ?>
                                                <i class="fas fa-image fa-2x text-muted"></i>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $row['id']; ?></td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($row['title']); ?></strong>
                                            <br>
                                            <small class="text-muted"><?php echo htmlspecialchars(mb_substr(strip_tags($row['content']), 0, 50)); ?>...</small>
                                        </td>
                                        <td>
                                            <?php 
                                            $categoryLabels = [
                                                'news' => 'Новость',
                                                'events' => 'Мероприятие',
                                                'articles' => 'Статья',
                                                'reviews' => 'Обзор'
                                            ];
                                            echo $categoryLabels[$row['category']] ?? $row['category'];
                                            ?>
                                        </td>
                                        <td><?php echo date('d.m.Y', strtotime($row['created_at'])); ?></td>
                                        <td><?php echo number_format($row['views'], 0, '', ' '); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-info" onclick="editNews(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteNews(<?php echo $row['id']; ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Создание новости -->
    <div class="modal fade" id="createNewsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Добавить новость</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="create">
                        <div class="mb-3">
                            <label>Заголовок *</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Категория</label>
                            <select name="category" class="form-control">
                                <option value="news">Новость</option>
                                <option value="events">Мероприятие</option>
                                <option value="articles">Статья</option>
                                <option value="reviews">Обзор</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Краткое описание (анонс)</label>
                            <textarea name="excerpt" class="form-control" rows="3" placeholder="Краткое описание новости..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Полный текст *</label>
                            <textarea name="content" class="form-control summernote" rows="10" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Изображение</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted">Рекомендуемый размер: 800x600px</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-gold">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Modal Редактирования новости -->
    <div class="modal fade" id="editNewsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Редактировать новость</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label>Заголовок *</label>
                            <input type="text" name="title" id="edit_title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Категория</label>
                            <select name="category" id="edit_category" class="form-control">
                                <option value="news">Новость</option>
                                <option value="events">Мероприятие</option>
                                <option value="articles">Статья</option>
                                <option value="reviews">Обзор</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Краткое описание (анонс)</label>
                            <textarea name="excerpt" id="edit_excerpt" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Полный текст *</label>
                            <textarea name="content" id="edit_content" class="form-control summernote" rows="10" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Новое изображение (оставьте пустым, чтобы сохранить текущее)</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-gold">Сохранить изменения</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>
        $('.summernote').summernote({
            height: 300,
            placeholder: 'Введите текст новости...',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
        
        document.getElementById('menuToggle')?.addEventListener('click', function() {
            document.querySelector('.admin-sidebar').classList.toggle('open');
        });
        
        function editNews(news) {
            $('#edit_id').val(news.id);
            $('#edit_title').val(news.title);
            $('#edit_category').val(news.category);
            $('#edit_excerpt').val(news.excerpt);
            
            // Для Summernote нужно обновить содержимое
            $('#edit_content').summernote('code', news.content);
            
            $('#editNewsModal').modal('show');
        }
        
        function deleteNews(id) {
            if(confirm('Вы уверены, что хотите удалить эту новость?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = '<input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="' + id + '">';
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>