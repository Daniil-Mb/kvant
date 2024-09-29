<?php
$host = 'MySQL-8.2';
$db = 'kvant_db';
$user = 'root'; 
$pass = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass); // Создаем объект PDO для подключения к базе данных
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Устанавливаем режим обработки ошибок в исключения
} catch (PDOException $e) { 
    echo "Connection failed: " . $e->getMessage(); // В случае ошибки при подключении выводим сообщение об ошибке
}
?>
