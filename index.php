<?php
session_start();
require 'config.php';
require 'controllers/TaskController.php';
require 'controllers/AuthController.php';

$taskController = new TaskController($pdo);
$authController = new AuthController($pdo);

$path = $_GET['path'] ?? 'tasks';

if ($path === 'logout') {
    $authController->logout();
    exit;
}

if (!isset($_SESSION['user_id']) && !in_array($path, ['login', 'register'])) {
    header('Location: index.php?path=login');
    exit;
}

switch ($path) {
    case 'register':
        $authController->register();
        break;
    case 'login':
        $authController->login();
        break;
    case 'tasks':
    default:
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
            header('Location: index.php?path=login');
        }
        break;
}
