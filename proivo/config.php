<?php
$mysql = new mysqli("localhost", "root", "root", "handmade_db");

if ($mysql->connect_error) {
    die("Ошибка подключения к базе данных: " . $mysql->connect_error);
}

$mysql->set_charset("utf8");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>