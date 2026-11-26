<?php
session_start();
$base_url = 'http://localhost:8080/proivo/index.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>handMadeone</title>
    <link rel="stylesheet" href="styles/index.css">
</head>
<body>

<div class="container">
    <div class="header-row">
    <a href="index.php" class="logo-link">
        <img src="img/logo.png" alt="handMadeone" class="logo-img">
    </a>
    <div class="nav">
        <a href="pages/catalog.php">Каталог</a>
        <a href="pages/about-us.php">О нас</a>
            <a href="#" class="reg-btn" id="openRegisterModal">Зарегистрироваться</a>
            <div class="profile-block" id="profileBlock">
                <span class="profile-login" id="profileLogin"></span>
                <img src="img/default-avatar.png" alt="Аватар" class="avatar-img" id="avatarImg">
                <button class="logout-btn" id="logoutBtn">Выйти</button>
            </div>
        </div>
    </div>
</div>

<div class="hero">
    <h2>Наполните ваш дом теплом и уютом</h2>
    <p>Не вручили, изделия из дерева, товары изтины</p>
    <a href="pages/catalog.php" class="catalog-btn">Каталог</a>
</div>

<div class="catalog">
    <div class="container">
        <h3>Вы можете найти здесь такие товары как</h3>
        <div class="product-row">
            <div class="product-item"><div class="product-img"></div><div class="price">560 руб.</div><a href="#" class="buy-btn">Купить</a></div>
            <div class="product-item"><div class="product-img"></div><div class="price">670 руб.</div><a href="#" class="buy-btn">Купить</a></div>
            <div class="product-item"><div class="product-img"></div><div class="price">420 руб.</div><a href="#" class="buy-btn">Купить</a></div>
        </div>
    </div>
</div>

<div class="footer">
    <div class="container">
        <div class="footer-blocks">
            <div class="footer-col"><p>handMadeone 2026</p><a href="#">О компании</a><a href="#">Поддержка</a><a href="#">Сотрудничество</a></div>
            <div class="footer-col"><p>handMadeone</p><a href="#">О нас</a><a href="#">Каталог</a></div>
            <div class="footer-col">
                <p>Мы есть также</p>
                <div class="social-images">
                    <img src="img/vk.png" alt="social" class="social-img">
                    <img src="img/yt.png" alt="social" class="social-img">
                    <img src="img/tg.png" alt="social" class="social-img">
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
<script src="scripts/index.js"></script>
</body>
</html>