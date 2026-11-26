<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>handMadeone</title>
    <link rel="stylesheet" href="../styles/index.css">
    <link rel="stylesheet" href="../styles/catalog.css">
</head>
<body>
<?php if (!$isLoggedIn): ?>
<div class="modal active" id="authRequiredModal">
    <div class="modal-content">
        <div class="modal-message">
            Для доступа к каталогу необходимо<br>
            <strong>войти или зарегистрироваться</strong>
        </div>
        <div class="modal-buttons">
            <button class="modal-btn" onclick="redirectToHome()">Вернуться</button>
            <button class="modal-btn primary" onclick="openRegisterModal()">Регистрация / Вход</button>
        </div>
    </div>
</div>
<?php endif; ?>
<div class="container">
    <div class="header-row">
        <a href="../index.php" class="logo-link">
            <img src="../img/logo.png" alt="handMadeone" class="logo-img">
        </a>
        <div class="nav">
            <a href="catalog.php">Каталог</a>
            <a href="about-us.php">О нас</a>
            <?php if ($isLoggedIn): ?>
            <div class="profile-block" style="display: flex;">
                <span class="profile-login" onclick="window.location.href='profile.php'" style="cursor: pointer;"><?php echo htmlspecialchars($_SESSION['user_login']); ?></span>
                <img src="../img/default-avatar.png" alt="Аватар" class="avatar-img" onclick="window.location.href='profile.php'" style="cursor: pointer;">
                <button class="logout-btn" onclick="logout()">Выйти</button>
            </div>
            <?php else: ?>
            <a href="#" class="reg-btn" onclick="openRegisterModal()">Зарегистрироваться</a>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="catalog-page">
    <div class="container">
        <div class="catalog-hero">
            <h1>Найдите для себя вашего мягкого спутника или любое другое украшение</h1>
        </div>
        <div class="catalog-section">
            <h2 class="section-title">Мягкие игрушки</h2>
            <div class="products-grid">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="product-card" data-category="soft" data-id="<?php echo $i; ?>">
                        <div class="product-image soft-<?php echo $i; ?>"></div>
                        <div class="product-title">Мягкая игрушка <?php echo $i; ?></div>
                        <div class="product-price">1 290 руб.</div>
                        <div class="product-actions">
                            <button class="btn-buy" onclick="buyProduct(<?php echo $i; ?>, 'soft')">Купить</button>
                            <button class="btn-cart" onclick="addToCart(this, <?php echo $i; ?>, 'soft')">В корзину</button>
                        </div>
                    </div>  
                <?php endfor; ?>
            </div>
        </div>
        <div class="section-divider"></div>
        <div class="catalog-section">
            <h2 class="section-title">Деревянные игрушки</h2>
            <div class="products-grid">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="product-card" data-category="wood" data-id="<?php echo $i; ?>">
                        <div class="product-image wood-<?php echo $i; ?>"></div>
                        <div class="product-title">Деревянная игрушка <?php echo $i; ?></div>
                        <div class="product-price">890 руб.</div>
                        <div class="product-actions">
                            <button class="btn-buy" onclick="buyProduct(<?php echo $i; ?>, 'wood')">Купить</button>
                            <button class="btn-cart" onclick="addToCart(this, <?php echo $i; ?>, 'wood')">В корзину</button>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
        <div class="section-divider"></div>
        <div class="catalog-section">
            <h2 class="section-title">Глиняные товары</h2>
            <div class="products-grid">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="product-card" data-category="clay" data-id="<?php echo $i; ?>">
                        <div class="product-image clay-<?php echo $i; ?>"></div>
                        <div class="product-title">Глиняное изделие <?php echo $i; ?></div>
                        <div class="product-price">750 руб.</div>
                        <div class="product-actions">
                            <button class="btn-buy" onclick="buyProduct(<?php echo $i; ?>, 'clay')">Купить</button>
                            <button class="btn-cart" onclick="addToCart(this, <?php echo $i; ?>, 'clay')">В корзину</button>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
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
<div class="modal" id="authModal">
    <div class="modal-content">
        <div class="form-switch">
            <button class="switch-btn active" id="switchRegister">Регистрация</button>
            <button class="switch-btn" id="switchLogin">Войти</button>
        </div>
        <div id="registerForm" class="visible-form">
            <div class="form-group">
                <label>Логин</label>
                <input type="text" id="regLogin" placeholder="Введите логин">
                <div class="error-message" id="regLoginError"></div>
            </div>
            <div class="form-group">
                <label>Пароль</label>
                <input type="password" id="regPassword" placeholder="Введите пароль">
                <div class="error-message" id="regPasswordError"></div>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" id="regEmail" placeholder="Введите email">
                <div class="error-message" id="regEmailError"></div>
            </div>
            <button class="submit-btn" id="registerSubmit">Регистрация</button>
        </div>
        <div id="loginForm" class="hidden-form">
            <div class="form-group">
                <label>Логин или Email</label>
                <input type="text" id="loginLogin" placeholder="Введите логин или email">
                <div class="error-message" id="loginLoginError"></div>
            </div>
            <div class="form-group">
                <label>Пароль</label>
                <input type="password" id="loginPassword" placeholder="Введите пароль">
                <div class="error-message" id="loginPasswordError"></div>
            </div>
            <button class="submit-btn" id="loginSubmit">Войти</button>
            <div class="forgot-link">
                <a href="#">Забыли пароль?...</a>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="buyModal">
    <div class="modal-content buy-modal-content">
        <div class="buy-modal-header">
            <h2 class="buy-modal-title">Оформление покупки</h2>
            <button class="buy-modal-close" onclick="closeBuyModal()">×</button>
        </div>
        <div class="buy-product-info">
            <div class="buy-product-image" id="buyProductImage"></div>
            <div class="buy-product-details">
                <div class="buy-product-title" id="buyProductTitle">Название товара</div>
                <div class="buy-product-price" id="buyProductPrice">0 руб.</div>
                <div class="buy-product-description" id="buyProductDescription">
                    <p class="description-placeholder">Игрушка</p>
                </div>
            </div>
        </div>
        <div class="buy-payment-section">
            <h3 class="buy-payment-title">Выберите способ оплаты</h3>
            <div class="payment-options">
                <label class="payment-option">
                    <input type="radio" name="payment" value="card" checked>
                    <span class="payment-radio"></span>
                    <span class="payment-label">Банковская карта</span>
                </label>
                
                <label class="payment-option">
                    <input type="radio" name="payment" value="qiwi">
                    <span class="payment-radio"></span>
                    <span class="payment-label">QIWI</span>
                </label>
                
                <label class="payment-option">
                    <input type="radio" name="payment" value="yoomoney">
                    <span class="payment-radio"></span>
                    <span class="payment-label">ЮMoney</span>
                </label>
            </div>
        </div>
        <div class="buy-action">
            <button class="buy-checkout-btn" id="buyCheckoutBtn" onclick="processPayment()">
                Оплатить (<span id="buyCheckoutPrice">0 руб.</span>)
            </button>
        </div>
    </div>
</div>
<script src="../scripts/index.js"></script>
<script src="../scripts/catalog.js"></script>
</body>
</html>