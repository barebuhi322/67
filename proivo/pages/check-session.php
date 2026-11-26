<?php
require_once dirname(__DIR__) . '/config.php';

header('Content-Type: application/json');

if (isset($_SESSION['user_id'])) {
    echo json_encode([
        'logged_in' => true,
        'user' => [
            'login' => $_SESSION['user_login'],
            'avatar' => 'default-avatar.png'
        ]
    ]);
} else {
    echo json_encode(['logged_in' => false]);
}
?>