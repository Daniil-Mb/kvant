<?php
require_once 'models/User.php';

class AuthController {
    private $userModel;

    public function __construct($pdo) {
        $this->userModel = new User($pdo);
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $response = [];
            $username = trim($_POST['username']); // Удаляем пробелы
    
            // Проверка на пустое имя пользователя
            if (empty($username)) {
                $response = ['success' => false, 'message' => 'Имя пользователя не может быть пустым.'];
                echo json_encode($response);
                exit;
            }
    
            if ($this->userModel->register($username, $_POST['password'])) {
                $response = ['success' => true];
            } else {
                $response = ['success' => false, 'message' => 'Такой аккаунт уже существует.'];
            }
            echo json_encode($response);
            exit;
        }
        include 'views/register.php';
    }
    

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $response = [];
            $username = trim($_POST['username']); // Удаляем пробелы
    
            // Проверка на пустое имя пользователя
            if (empty($username)) {
                $response = ['success' => false, 'message' => 'Имя пользователя не может быть пустым.'];
                echo json_encode($response);
                exit;
            }
    
            if ($user = $this->userModel->login($username, $_POST['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $response = ['success' => true];
            } else {
                $response = ['success' => false, 'message' => 'Неверное имя пользователя или пароль.'];
            }
            echo json_encode($response);
            exit;
        }
        include 'views/login.php';
    }
    

    public function logout() {
        session_start();
        session_unset(); 
        session_destroy(); 
        header('Location: index.php?path=login');
        exit;
    }
}
