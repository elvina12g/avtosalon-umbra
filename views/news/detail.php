<?php
// views/news/detail.php
if(!isset($news)) {
    header("HTTP/1.0 404 Not Found");
    require_once 'views/errors/404.php';
    return;
}

$categoryLabels = [
    'news' => 'Новость',
    'events' => 'Мероприятие',
    'articles' => 'Статья',
    'reviews' => 'Обзор'
];
?>
<div class="container py-5 mt-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <article class="news-article">
                <div class="news-article-header mb-4">
                    <div class="news-meta mb-3">
                        <span class="badge bg-gold me-3"><?php echo $categoryLabels[$news['category']] ?? 'Новость'; ?></span>
                        <span><i class="far fa-calendar-alt me-1"></i> <?php echo date('d.m.Y', strtotime($news['created_at'])); ?></span>
                        <span class="ms-3"><i class="far fa-eye me-1"></i> <?php echo number_format($news['views'], 0, '', ' '); ?></span>
                    </div>
                    <h1 class="display-4 mb-4"><?php echo htmlspecialchars($news['title']); ?></h1>
                </div>
                
                <?php 
                $imagePath = !empty($news['image']) && file_exists('uploads/news/' . $news['image']) 
                    ? '/uploads/news/' . $news['image'] 
                    : '/assets/images/news/default-news.jpg';
                ?>
                <div class="news-article-image mb-5">
                    <img src="<?php echo $imagePath; ?>" class="img-fluid rounded shadow" alt="<?php echo htmlspecialchars($news['title']); ?>">
                </div>
                
                <div class="news-article-content">
                    <?php echo $news['content']; ?>
                </div>
                
                <hr class="my-5">
                
                <!-- Кнопки навигации -->
                <div class="news-navigation d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <a href="/news" class="btn btn-outline-gold">
                        <i class="fas fa-arrow-left me-2"></i> Ко всем новостям
                    </a>
                    <div class="social-share">
                        <span class="me-2">Поделиться:</span>
                        <a href="#" class="social-link me-2" onclick="shareOnVK()"><i class="fab fa-vk"></i></a>
                        <a href="#" class="social-link me-2" onclick="shareOnTelegram()"><i class="fab fa-telegram"></i></a>
                        <a href="#" class="social-link" onclick="shareOnWhatsApp()"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </article>
        </div>
    </div>
</div>

<style>
.news-article-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #444;
}

.news-article-content h2 {
    font-size: 1.8rem;
    margin: 1.5rem 0 1rem;
    color: var(--primary-dark);
}

.news-article-content h3 {
    font-size: 1.4rem;
    margin: 1.2rem 0 0.8rem;
    color: var(--primary-dark);
}

.news-article-content p {
    margin-bottom: 1.2rem;
}

.news-article-content img {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
    margin: 1.5rem 0;
}

.news-article-content ul, 
.news-article-content ol {
    margin: 1rem 0;
    padding-left: 1.5rem;
}

.news-article-content li {
    margin-bottom: 0.5rem;
}

.news-article-content blockquote {
    border-left: 4px solid var(--accent-gold);
    padding-left: 1.5rem;
    margin: 1.5rem 0;
    font-style: italic;
    color: #666;
}

.news-article-content table {
    width: 100%;
    margin: 1.5rem 0;
    border-collapse: collapse;
}

.news-article-content th,
.news-article-content td {
    border: 1px solid #ddd;
    padding: 10px;
}

.news-article-content th {
    background: #f5f5f5;
}

.social-share .social-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    background: #f0f0f0;
    border-radius: 50%;
    color: var(--primary-dark);
    transition: all 0.3s ease;
    text-decoration: none;
}

.social-share .social-link:hover {
    background: var(--accent-gold);
    color: white;
    transform: translateY(-3px);
}

.bg-gold {
    background: var(--accent-gold);
    color: var(--primary-dark);
}
</style>

<script>
function shareOnVK() {
    window.open('https://vk.com/share.php?url=' + encodeURIComponent(window.location.href), '_blank', 'width=600,height=400');
}

function shareOnTelegram() {
    window.open('https://t.me/share/url?url=' + encodeURIComponent(window.location.href), '_blank', 'width=600,height=400');
}

function shareOnWhatsApp() {
    window.open('https://wa.me/?text=' + encodeURIComponent(window.location.href), '_blank');
}
</script>