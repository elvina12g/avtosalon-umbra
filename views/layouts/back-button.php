<?php
// Кнопка возврата на предыдущую страницу
$referer = $_SERVER['HTTP_REFERER'] ?? '';
$showBackButton = !empty($referer) && strpos($referer, $_SERVER['HTTP_HOST']) !== false;
?>

<?php if($showBackButton): ?>
<div class="back-button-wrapper">
    <button onclick="history.back()" class="back-button">
        <i class="fas fa-arrow-left"></i> Назад
    </button>
</div>
<?php endif; ?>

<style>
.back-button-wrapper {
    position: fixed;
    bottom: 30px;
    left: 30px;
    z-index: 999;
}

.back-button {
    background: var(--primary-dark);
    border: 1px solid var(--accent-gold);
    color: var(--accent-gold);
    padding: 12px 20px;
    border-radius: 50px;
    cursor: pointer;
    transition: var(--transition);
    font-weight: 600;
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.back-button:hover {
    background: var(--accent-gold);
    color: var(--primary-dark);
    transform: translateX(-5px);
}

@media (max-width: 768px) {
    .back-button-wrapper {
        bottom: 20px;
        left: 20px;
    }
    
    .back-button {
        padding: 8px 15px;
        font-size: 14px;
    }
}
</style>