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
    <link rel="stylesheet" href="../styles/about-us.css">
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
<div class="about-page">
    <div class="container">
        <div class="about-hero">
            <h1>История handMadeone</h1>
            <p>Мастерская душевных подарков и уютных вещей, созданных с любовью</p>
        </div>
        <div class="about-story">
            <div class="about-story-content">
                <h2>Как всё начиналось</h2>
                <p>В далеком 2020 году, в разгар пандемии, когда весь мир ушел на самоизоляцию, основательница handMadeone Анна решила превратить свое хобби в дело жизни. Начав с создания мягких игрушек для своих племянников, она быстро поняла, что её работы приносят радость не только близким.</p>
                <p>Первые заказы приходили через VK и Telegramm — друзья друзей просили связать или сшить такую же игрушку для своих детей. С каждым месяцем мастерская росла, появлялись новые направления: сначала деревянные игрушки, которые вырезал муж Анны, а затем и глиняные изделия, которые добавила подруга-керамист.</p>
                <p>К 2022 году handMadeone превратилась в небольшую, но дружную мастерскую, где трудятся 5 мастеров. А в 2024 году мы открыли этот сайт, чтобы вы могли найти свой идеальный подарок, не выходя из дома.</p>
            </div>
            <div class="about-story-image"></div>
        </div>
        <div class="about-mission">
            <h2>Наша миссия</h2>
            <p>Возвращать тепло и уют в каждый дом через уникальные вещи ручной работы, созданные с душой. Мы верим, что настоящие эмоции нельзя купить в масс-маркете — их можно только создать своими руками.</p>
        </div>
        <div class="about-categories">
            <h2>Что мы создаём</h2>
            <div class="categories-grid">
                <div class="category-card">
                    <div class="category-icon icon-soft"></div>
                    <h3>Мягкие игрушки</h3>
                    <p>Экологичные игрушки из натуральных материалов</p>
                    <ul class="category-features">
                        <li>Гипоаллергенные материалы</li>
                        <li>Ручная работа</li>
                        <li>Индивидуальный дизайн</li>
                        <li>Более 50 видов животных</li>
                        <li>Игрушки-подушки</li>
                    </ul>
                </div>

                <div class="category-card">
                    <div class="category-icon icon-wood"></div>
                    <h3>Деревянные игрушки</h3>
                    <p>Развивающие игрушки из натурального дерева</p>
                    <ul class="category-features">
                        <li>Береза и дуб</li>
                        <li>Безопасные краски</li>
                        <li>Развитие моторики</li>
                        <li>Конструкторы и пазлы</li>
                        <li>Именные подарки</li>
                    </ul>
                </div>

                <div class="category-card">
                    <div class="category-icon icon-clay"></div>
                    <h3>Глиняные изделия</h3>
                    <p>Керамическая посуда и декор для дома</p>
                    <ul class="category-features">
                        <li>Ручная лепка</li>
                        <li>Уникальная глазурь</li>
                        <li>Кружки и тарелки</li>
                        <li>Кашпо для цветов</li>
                        <li>Авторские сервизы</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="about-team">
            <h2>Наша команда</h2>
            <p class="team-text">
                Сегодня handMadeone — это 5 мастеров и 2 помощника. Мы не просто коллеги, мы — семья. 
                Каждый день мы вкладываем частичку души в наши изделия, чтобы они согревали ваши дома. 
                Мы проводим мастер-классы, участвуем в ярмарках и постоянно учимся новому, чтобы радовать вас.
            </p>
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
<script src="../scipts/index.js"></script>
</body>
</html>