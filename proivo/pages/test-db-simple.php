<?php
$mysql = new mysqli("localhost", "root", "root", "handmade_db");

if ($mysql->connect_error) {
    die(" Ошибка подключения: " . $mysql->connect_error);
}

echo " Подключение к handmade_db успешно!<br>";
echo " Версия MySQL: " . $mysql->server_info . "<br>";

$result = $mysql->query("SHOW TABLES LIKE 'users'");
if ($result->num_rows > 0) {
    echo " Таблица 'users' существует<br>";
    
    $fields = $mysql->query("DESCRIBE users");
    echo "<h3>Структура таблицы users:</h3>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Поле</th><th>Тип</th><th>Null</th><th>Ключ</th></tr>";
    while ($field = $fields->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$field['Field']}</td>";
        echo "<td>{$field['Type']}</td>";
        echo "<td>{$field['Null']}</td>";
        echo "<td>{$field['Key']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo " Таблица 'users' не найдена!<br>";
    echo "Создайте таблицу SQL запросом:<br>";
    echo "<pre>
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    avatar VARCHAR(255) DEFAULT 'default-avatar.png',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
    </pre>";
}

$mysql->close();
?>