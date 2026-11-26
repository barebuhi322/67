<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
require_once dirname(__DIR__) . '/config.php';
$userId = $_SESSION['user_id'];
$result = $mysql->query("SELECT login, email, avatar FROM users WHERE id = $userId");
$user = $result->fetch_assoc();
$email = $user['email'];
$atPos = strpos($email, '@');
if ($atPos !== false) {
    $localPart = substr($email, 0, $atPos);
    $domain = substr($email, $atPos);
    
    if (strlen($localPart) > 3) {
        $maskedLocal = substr($localPart, 0, 3) . str_repeat('*', strlen($localPart) - 3);
    } else {
        $maskedLocal = $localPart . str_repeat('*', 3 - strlen($localPart));
    }
    
    $maskedEmail = $maskedLocal . $domain;
} else {
    $maskedEmail = $email;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>handMadeone</title>
    <link rel="stylesheet" href="../styles/index.css">
    <link rel="stylesheet" href="../styles/profile.css">
</head>
<body>
<div class="container">
    <div class="header-row">
        <a href="../index.php" class="logo-link">
            <img src="../img/logo.png" alt="handMadeone" class="logo-img">
        </a>
        <div class="nav">
            <a href="catalog.php">Каталог</a>
            <a href="about-us.php">О нас</a>
            <div class="profile-block" style="display: flex;">
                <span class="profile-login" onclick="window.location.href='profile.php'" style="cursor: pointer;"><?php echo htmlspecialchars($_SESSION['user_login']); ?></span>
                <img src="<?php echo $user['avatar'] ?? '../img/default-avatar.png'; ?>" alt="Аватар" class="avatar-img" onclick="window.location.href='profile.php'" style="cursor: pointer;">
                <button class="logout-btn" onclick="logout()">Выйти</button>
            </div>
        </div>
    </div>
</div>
<div class="profile-page">
    <div class="container">
        <div class="profile-header">
            <div class="profile-avatar-section">
                <div class="profile-avatar-frame">
                    <img src="<?php echo $user['avatar'] ?? '../img/default-avatar.png'; ?>" alt="Аватар" class="profile-avatar-img" id="profileAvatar">
                </div>
                <button class="change-avatar-btn" id="changeAvatarBtn">Изменить аватар</button>
            </div>
            
            <div class="profile-info-section">
                <div class="profile-login-large"><?php echo htmlspecialchars($user['login']); ?></div>
                <div class="profile-email"><?php echo htmlspecialchars($maskedEmail); ?></div>
            </div>
        </div>
        <div class="cart-section">
            <div class="cart-header">
                <h2 class="cart-title">Корзина</h2>
                <img src="../img/basket.png" alt="Корзина" class="cart-icon">
            </div>
            <div class="cart-container" id="cartContainer">
                <div class="cart-empty">
                    <p>Ваша корзина пуста</p>
                    <p class="cart-empty-hint">Перейдите в <a href="catalog.php">каталог</a>, чтобы добавить товары</p>
                </div>
            </div>
            <button class="checkout-btn" id="checkoutBtn" disabled style="opacity: 0.5; cursor: not-allowed;">Оплатить</button>
        </div>
    </div>
</div>
<div class="footer">
    <div class="container">
        <div class="footer-blocks">
            <div class="footer-col">
                <p>handMadeone 2026</p>
                <a href="#">О компании</a>
                <a href="#">Поддержка</a>
                <a href="#">Сотрудничество</a>
            </div>
            <div class="footer-col">
                <p>handMadeone</p>
                <a href="catalog.php">Каталог</a>
                <a href="about.php">О нас</a>
            </div>
            <div class="footer-col">
                <p>Мы есть также</p>
                <div class="social-images">
                    <img src="../img/vk.png" alt="social" class="social-img">
                    <img src="../img/yt.png" alt="social" class="social-img">
                    <img src="../img/tg.png" alt="social" class="social-img">
                </div>
            </div>
        </div>
        <div class="copyright">handMadeone 2026</div>
    </div>
</div>
<div class="modal" id="cartCheckoutModal">
    <div class="modal-content cart-checkout-modal-content">
        <div class="cart-checkout-modal-header">
            <h2 class="cart-checkout-modal-title">Оформление заказа</h2>
            <button class="cart-checkout-modal-close" onclick="closeCartCheckoutModal()">×</button>
        </div>
        <div class="cart-checkout-items-section">
            <h3 class="cart-checkout-items-title">Ваши товары</h3>
            <div class="cart-checkout-items-list" id="cartCheckoutItemsList">
            </div>
            <div class="cart-checkout-total" id="cartCheckoutTotal">
                Итого: <span class="cart-checkout-total-price">0 руб.</span>
            </div>
        </div>
        <div class="cart-checkout-payment-section">
            <h3 class="cart-checkout-payment-title">Выберите способ оплаты</h3>
            <div class="cart-checkout-payment-options">
                <label class="cart-checkout-payment-option">
                    <input type="radio" name="cartPayment" value="card" checked>
                    <span class="cart-checkout-payment-radio"></span>
                    <span class="cart-checkout-payment-label">Банковская карта</span>
                </label>
                
                <label class="cart-checkout-payment-option">
                    <input type="radio" name="cartPayment" value="qiwi">
                    <span class="cart-checkout-payment-radio"></span>
                    <span class="cart-checkout-payment-label">QIWI</span>
                </label>
                
                <label class="cart-checkout-payment-option">
                    <input type="radio" name="cartPayment" value="yoomoney">
                    <span class="cart-checkout-payment-radio"></span>
                    <span class="cart-checkout-payment-label">ЮMoney</span>
                </label>
            </div>
        </div>
        <div class="cart-checkout-action">
            <button class="cart-checkout-btn" id="cartCheckoutBtn" onclick="processCartPayment()">
                Оплатить заказ
            </button>
        </div>
    </div>
</div>
<script src="../scripts/index.js"></script>
<script src="../scripts/profile.js"></script>
</body>
</html>