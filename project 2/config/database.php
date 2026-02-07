<?php
// Параметры подключения к базе данных
$host = 'localhost';
$dbname = 'autoservice';
$username = 'root';
$password = '';
$charset = 'utf8mb4';

// Опции PDO
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

// DSN для подключения
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

try {
    // Создание экземпляра PDO
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    // В случае ошибки подключения
    die('Ошибка подключения к базе данных: ' . $e->getMessage());
} 