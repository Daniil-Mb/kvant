<?php
session_start(); // Запуск сессии
require 'config.php';
require 'controllers/TaskController.php';
require 'controllers/AuthController.php';

$taskController = new TaskController($pdo);
$authController = new AuthController($pdo);

// Определяем текущий маршрут (например, login, register, logout или tasks)
$path = $_GET['path'] ?? 'tasks'; // По умолчанию показываем задачи

// Логика выхода из аккаунта
if ($path === 'logout') {
    $authController->logout();
    exit;
}

// Проверка аутентификации для защищенных маршрутов (например, tasks)
if (!isset($_SESSION['user_id']) && !in_array($path, ['login', 'register'])) {
    header('Location: index.php?path=login');
    exit;
}

// Обработка маршрутов на основе текущего пути
switch ($path) {
    case 'register':
        $authController->register();
        break;
    case 'login':
        $authController->login();
        break;
    case 'tasks':
    default:
        // Проверяем, авторизован ли пользователь
        if (isset($_SESSION['user_id'])) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['create'])) {
                    $taskController->create();
                } elseif (isset($_POST['update'])) {
                    $taskController->update();
                } elseif (isset($_POST['delete'])) {
                    $taskController->delete();
                }
            }
            $taskController->index();
        } else {
            // Если не авторизован, перенаправляем на страницу входа
            header('Location: index.php?path=login');
        }
        break;
}
