<?php
require_once dirname(__DIR__) . '/config.php';

header('Content-Type: application/json');

$login = $_POST['login'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($login) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Заполните все поля']);
    exit;
}

$login = $mysql->real_escape_string($login);

$result = $mysql->query("SELECT * FROM users WHERE login='$login' OR email='$login'");

if ($result->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => 'Пользователь не найден']);
    exit;
}

$user = $result->fetch_assoc();

if (password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_login'] = $user['login'];
    
    echo json_encode([
        'success' => true,
        'message' => 'Вход выполнен успешно!',
        'user' => [
            'login' => $user['login'],
            'avatar' => $user['avatar'] ?? 'default-avatar.png'
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Неверный пароль']);
}
?>