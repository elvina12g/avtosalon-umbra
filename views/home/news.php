<?php
// views/home/news.php
// Получаем новости из базы данных
require_once 'config/database.php';
require_once 'controllers/NewsController.php';

$database = new Database();
$db = $database->getConnection();

$newsController = new NewsController($db);

// Получаем параметры фильтрации
$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 6;
$offset = ($page - 1) * $limit;

// Получаем новости
if($category != 'all') {
    $news = $newsController->getByCategory($category, $limit, $offset);
    $total = $newsController->getCountByCategory($category);
} else {
    $news = $newsController->getAll($limit, $offset);
    $total = $newsController->getTotalCount();
}

$totalPages = ceil($total / $limit);

// Получаем популярные новости для боковой панели
$popularNews = $newsController->getPopular(5);

// Получаем категории для фильтра
$categories = $newsController->getCategoriesWithCount();

$pageTitle = 'Новости и блог - Umbra Premium Auto';
$metaDescription = 'Актуальные новости, обзоры и события мира премиальных автомобилей. Будьте в курсе всех новинок автосалона Umbra.';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <meta name="description" content="<?php echo $metaDescription; ?>">
    <meta name="keywords" content="новости автосалона, обзоры автомобилей, Mercedes-Benz, BMW, Audi, Porsche">
    
    <!-- Open Graph для соцсетей -->
    <meta property="og:title" content="Новости и блог - Umbra Premium Auto">
    <meta property="og:description" content="<?php echo $metaDescription; ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://<?php echo $_SERVER['HTTP_HOST']; ?>/news">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-dark: #1a1a2e;
            --accent-gold: #c4a747;
            --bg-light: #f8f8f8;
            --text-dark: #333333;
            --text-light: #ffffff;
            --shadow: 0 10px 30px rgba(0,0,0,0.1);
            --transition: all 0.3s ease;
        }
        
        /* Стили для новостей */
        .news-filters {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 12px;
            margin-bottom: 40px;
        }
        
        .filter-btn {
            background: transparent;
            border: 2px solid var(--accent-gold);
            color: var(--accent-gold);
            padding: 10px 24px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .filter-btn:hover,
        .filter-btn.active {
            background: var(--accent-gold);
            color: var(--primary-dark);
        }
        
        .news-card {
            display: flex;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transition: all 0.4s ease;
            height: 320px;
            margin-bottom: 30px;
        }
        
        .news-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .news-card-image {
            flex: 0 0 380px;
            position: relative;
            overflow: hidden;
        }
        
        .news-card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }
        
        .news-card:hover .news-card-image img {
            transform: scale(1.05);
        }
        
        .news-category-badge {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            z-index: 2;
        }
        
        .news-category-badge.category-news { background: #c4a747; color: #1a1a2e; }
        .news-category-badge.category-events { background: #e67e22; color: white; }
        .news-category-badge.category-articles { background: #3498db; color: white; }
        .news-category-badge.category-reviews { background: #9b59b6; color: white; }
        
        .news-read-more-btn {
            position: absolute;
            bottom: 20px;
            left: 20px;
            width: 50px;
            height: 50px;
            background: var(--accent-gold);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-dark);
            font-size: 20px;
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateX(-20px);
            z-index: 2;
            text-decoration: none;
        }
        
        .news-card:hover .news-read-more-btn {
            opacity: 1;
            transform: translateX(0);
        }
        
        .news-read-more-btn:hover {
            background: white;
            color: var(--accent-gold);
            transform: scale(1.1);
        }
        
        .news-card-content {
            flex: 1;
            padding: 30px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .news-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
            font-size: 13px;
            color: #888;
        }
        
        .news-meta i {
            margin-right: 6px;
            color: var(--accent-gold);
        }
        
        .news-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 15px;
            line-height: 1.3;
        }
        
        .news-excerpt {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .news-link {
            color: var(--accent-gold);
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        
        .news-link:hover {
            gap: 12px;
            color: #d4b55c;
        }
        
        .news-link i {
            transition: transform 0.3s ease;
        }
        
        .news-link:hover i {
            transform: translateX(5px);
        }
        
        /* Боковая панель */
        .sidebar-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        
        .sidebar-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--accent-gold);
            display: inline-block;
        }
        
        .popular-news-item {
            display: flex;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
            transition: var(--transition);
        }
        
        .popular-news-item:hover {
            transform: translateX(5px);
        }
        
        .popular-news-img {
            width: 80px;
            height: 80px;
            border-radius: 10px;
            object-fit: cover;
        }
        
        .popular-news-content h6 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 5px;
            line-height: 1.4;
        }
        
        .popular-news-content a {
            color: var(--text-dark);
            text-decoration: none;
            transition: var(--transition);
        }
        
        .popular-news-content a:hover {
            color: var(--accent-gold);
        }
        
        .popular-news-date {
            font-size: 11px;
            color: #999;
        }
        
        .category-list {
            list-style: none;
            padding: 0;
        }
        
        .category-list li {
            margin-bottom: 12px;
        }
        
        .category-list a {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--text-dark);
            text-decoration: none;
            padding: 8px 0;
            transition: var(--transition);
        }
        
        .category-list a:hover {
            color: var(--accent-gold);
            padding-left: 10px;
        }
        
        .category-count {
            background: #f0f0f0;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 12px;
        }
        
        /* Пагинация */
        .pagination {
            margin-top: 40px;
        }
        
        .pagination .page-link {
            color: var(--primary-dark);
            border: none;
            margin: 0 5px;
            border-radius: 8px;
            padding: 10px 18px;
            font-weight: 500;
            transition: all 0.3s ease;
            background: white;
        }
        
        .pagination .page-link:hover {
            background: var(--accent-gold);
            color: var(--primary-dark);
        }
        
        .pagination .page-item.active .page-link {
            background: var(--accent-gold);
            color: var(--primary-dark);
        }
        
        .pagination .page-item.disabled .page-link {
            color: #ccc;
            cursor: not-allowed;
        }
        
        /* Пустое состояние */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 20px;
        }
        
        .empty-state i {
            font-size: 80px;
            color: var(--accent-gold);
            margin-bottom: 20px;
            opacity: 0.5;
        }
        
        .empty-state h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        /* Адаптивность */
        @media (max-width: 992px) {
            .news-card {
                flex-direction: column;
                height: auto;
            }
            
            .news-card-image {
                flex: 0 0 250px;
            }
            
            .news-card-content {
                padding: 25px;
            }
            
            .news-title {
                font-size: 20px;
            }
        }
        
        @media (max-width: 768px) {
            .news-card-image {
                flex: 0 0 200px;
            }
            
            .news-filters {
                gap: 8px;
            }
            
            .filter-btn {
                padding: 6px 16px;
                font-size: 12px;
            }
        }
        
        .section-title {
            font-size: 36px;
            font-weight: 700;
            color: var(--primary-dark);
            position: relative;
            padding-bottom: 20px;
            margin-bottom: 40px;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: var(--accent-gold);
        }
    </style>
</head>
<body>
    <?php require_once 'views/layouts/header.php'; ?>

    <div class="container py-5 mt-5">
        <!-- Hero секция -->
        <div class="row mb-5">
            <div class="col-12 text-center" data-aos="fade-up">
                <h1 class="section-title">Новости и блог</h1>
                <p class="lead mx-auto" style="max-width: 700px;">Будьте в курсе последних событий мира премиальных автомобилей и новостей нашего салона</p>
            </div>
        </div>

        <div class="row">
            <!-- Основной контент -->
            <div class="col-lg-8">
                <!-- Фильтры -->
                <div class="news-filters">
                    <button class="filter-btn <?php echo $category == 'all' ? 'active' : ''; ?>" data-category="all">Все новости</button>
                    <button class="filter-btn <?php echo $category == 'news' ? 'active' : ''; ?>" data-category="news">Новости</button>
                    <button class="filter-btn <?php echo $category == 'events' ? 'active' : ''; ?>" data-category="events">Мероприятия</button>
                    <button class="filter-btn <?php echo $category == 'articles' ? 'active' : ''; ?>" data-category="articles">Статьи</button>
                    <button class="filter-btn <?php echo $category == 'reviews' ? 'active' : ''; ?>" data-category="reviews">Обзоры</button>
                </div>
                
                <!-- Список новостей -->
                <?php if($news->rowCount() > 0): ?>
                    <?php while($item = $news->fetch(PDO::FETCH_ASSOC)): ?>
                        <div class="news-card" data-aos="fade-up">
                            <div class="news-card-image">
                                <?php 
                                $imagePath = !empty($item['image']) && file_exists('uploads/news/' . $item['image']) 
                                    ? '/uploads/news/' . $item['image'] 
                                    : '/assets/images/news/default-news.jpg';
                                ?>
                                <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                                <span class="news-category-badge category-<?php echo $item['category']; ?>">
                                    <?php 
                                    $categoryLabels = [
                                        'news' => 'Новость',
                                        'events' => 'Мероприятие',
                                        'articles' => 'Статья',
                                        'reviews' => 'Обзор'
                                    ];
                                    echo $categoryLabels[$item['category']] ?? 'Новость';
                                    ?>
                                </span>
                                <a href="/news/<?php echo $item['id']; ?>" class="news-read-more-btn">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                            <div class="news-card-content">
                                <div class="news-meta">
                                    <span><i class="far fa-calendar-alt"></i> <?php echo date('d.m.Y', strtotime($item['created_at'])); ?></span>
                                    <span><i class="far fa-eye"></i> <?php echo number_format($item['views'], 0, '', ' '); ?></span>
                                </div>
                                <h2 class="news-title"><?php echo htmlspecialchars($item['title']); ?></h2>
                                <p class="news-excerpt"><?php echo htmlspecialchars(mb_substr(strip_tags($item['excerpt'] ?: $item['content']), 0, 180)); ?>...</p>
                                <a href="/news/<?php echo $item['id']; ?>" class="news-link">
                                    Читать подробнее <i class="fas fa-long-arrow-alt-right"></i>
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                    
                    <!-- Пагинация -->
                    <?php if($totalPages > 1): ?>
                    <nav aria-label="Навигация по страницам">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>&category=<?php echo $category; ?>">← Предыдущая</a>
                            </li>
                            <?php for($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&category=<?php echo $category; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?>&category=<?php echo $category; ?>">Следующая →</a>
                            </li>
                        </ul>
                    </nav>
                    <?php endif; ?>
                    
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-newspaper"></i>
                        <h3>Новостей пока нет</h3>
                        <p class="text-muted">Следите за обновлениями, скоро появятся новые материалы!</p>
                        <a href="/" class="btn btn-gold mt-3">На главную</a>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Боковая панель -->
            <div class="col-lg-4">
                <!-- Популярные новости -->
                <?php if(!empty($popularNews)): ?>
                <div class="sidebar-card" data-aos="fade-left">
                    <h3 class="sidebar-title">🔥 Популярное</h3>
                    <?php foreach($popularNews as $popular): ?>
                    <div class="popular-news-item">
                        <?php 
                        $popularImage = !empty($popular['image']) && file_exists('uploads/news/' . $popular['image']) 
                            ? '/uploads/news/' . $popular['image'] 
                            : '/assets/images/news/default-news.jpg';
                        ?>
                        <img src="<?php echo $popularImage; ?>" alt="<?php echo htmlspecialchars($popular['title']); ?>" class="popular-news-img">
                        <div class="popular-news-content">
                            <h6><a href="/news/<?php echo $popular['id']; ?>"><?php echo htmlspecialchars(mb_substr($popular['title'], 0, 50)); ?></a></h6>
                            <div class="popular-news-date">
                                <i class="far fa-calendar-alt"></i> <?php echo date('d.m.Y', strtotime($popular['created_at'])); ?>
                                <span class="ms-2"><i class="far fa-eye"></i> <?php echo $popular['views']; ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                
                <!-- Категории -->
                <?php if(!empty($categories)): ?>
                <div class="sidebar-card" data-aos="fade-left" data-aos-delay="100">
                    <h3 class="sidebar-title">📁 Категории</h3>
                    <ul class="category-list">
                        <li>
                            <a href="?category=all">
                                <span>Все новости</span>
                                <span class="category-count"><?php echo $total; ?></span>
                            </a>
                        </li>
                        <?php foreach($categories as $cat): ?>
                        <li>
                            <a href="?category=<?php echo $cat['category']; ?>">
                                <span>
                                    <?php 
                                    $catLabels = [
                                        'news' => '📰 Новости',
                                        'events' => '🎉 Мероприятия',
                                        'articles' => '📝 Статьи',
                                        'reviews' => '⭐ Обзоры'
                                    ];
                                    echo $catLabels[$cat['category']] ?? $cat['category'];
                                    ?>
                                </span>
                                <span class="category-count"><?php echo $cat['count']; ?></span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                
                <!-- Подписка на новости -->
                <div class="sidebar-card" data-aos="fade-left" data-aos-delay="200">
                    <h3 class="sidebar-title">📧 Подписка</h3>
                    <p class="text-muted small">Будьте в курсе всех новинок и спецпредложений</p>
                    <form id="subscribe-form" class="mt-3">
                        <div class="input-group">
                            <input type="email" class="form-control" id="subscribe-email" placeholder="Ваш email" required>
                            <button class="btn btn-gold" type="submit">Подписаться</button>
                        </div>
                        <small class="text-muted mt-2 d-block">Нажимая кнопку, вы соглашаетесь с политикой конфиденциальности</small>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php require_once 'views/layouts/footer.php'; ?>
    
    <script>
        // Фильтрация новостей
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const category = this.dataset.category;
                window.location.href = '/news?category=' + category;
            });
        });
        
        // Подписка на новости
        document.getElementById('subscribe-form')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = document.getElementById('subscribe-email').value;
            if(email) {
                alert('Спасибо за подписку! Новости будут приходить на ' + email);
                this.reset();
            }
        });
    </script>
</body>
</html>