<?php
require_once dirname(__DIR__) . '/config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

$login = $_POST['login'] ?? '';
$password = $_POST['password'] ?? '';
$email = $_POST['email'] ?? '';

if (empty($login) || empty($password) || empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Заполните все поля']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Некорректный email']);
    exit;
}

if (strlen($password) <= 6) {
    echo json_encode(['success' => false, 'message' => 'Пароль должен быть больше 6 символов']);
    exit;
}

if (preg_match('/\d/', $login)) {
    echo json_encode(['success' => false, 'message' => 'Логин не должен содержать цифры']);
    exit;
}

$login = $mysql->real_escape_string($login);
$email = $mysql->real_escape_string($email);

$check = $mysql->query("SELECT * FROM users WHERE login='$login' OR email='$email'");
if ($check->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Такой логин или email уже занят']);
    exit;
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$result = $mysql->query("INSERT INTO users (login, email, password) VALUES ('$login', '$email', '$hashed_password')");

if ($result) {
    $_SESSION['user_id'] = $mysql->insert_id;
    $_SESSION['user_login'] = $login;
    $_SESSION['user_avatar'] = 'default-avatar.png';
    
    echo json_encode([
        'success' => true,
        'message' => 'Регистрация успешна!',
        'user' => [
            'login' => $login,
            'avatar' => 'default-avatar.png'
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Ошибка при регистрации: ' . $mysql->error]);
}
?>