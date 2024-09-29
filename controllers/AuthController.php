<?php
require_once 'models/User.php';

class AuthController {
    private $userModel; // Объявляем свойство для хранения экземпляра модели

    public function __construct($pdo) { // Конструктор класса, принимает объект PDO для работы с базой данных
        $this->userModel = new User($pdo); // Инициализируем модель User с подключением к БД
    }

    public function register() { // Метод для регистрации нового пользователя
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Проверяем, что запрос - POST
            $response = []; // Инициализируем массив для ответа
            $username = trim($_POST['username']); // Получаем и очищаем имя пользователя
    
            if (empty($username)) { // Проверяем, не пустое ли имя пользователя
                $response = ['success' => false, 'message' => 'Имя пользователя не может быть пустым.'];
                echo json_encode($response);// Возвращаем ответ в формате JSON
                exit;
            }
    
            if ($this->userModel->register($username, $_POST['password'])) { // Пытаемся зарегистрировать пользователя
                $response = ['success' => true]; // Успешная регистрация
            } else {
                $response = ['success' => false, 'message' => 'Такой аккаунт уже существует.'];
            }
            echo json_encode($response); // Возвращаем ответ в формате JSON
            exit;
        }
        include 'views/register.php';
    }
    

    public function login() { // Метод для входа пользователя в систему
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Проверяем, что запрос - POST
            $response = []; // Инициализируем массив для ответа
            $username = trim($_POST['username']); // Получаем и очищаем имя пользователя
    
            if (empty($username)) { // Проверяем, не пустое ли имя пользователя
                $response = ['success' => false, 'message' => 'Имя пользователя не может быть пустым.'];
                echo json_encode($response); // Возвращаем ответ в формате JSON
                exit;
            }
    
            if ($user = $this->userModel->login($username, $_POST['password'])) { // Пытаемся войти в систему
                session_start();
                $_SESSION['user_id'] = $user['id']; // Сохраняем id пользователя в сессии
                $response = ['success' => true];
            } else {
                $response = ['success' => false, 'message' => 'Неверное имя пользователя или пароль.'];
            }
            echo json_encode($response);
            exit;
        }
        include 'views/login.php';
    }
    

    public function logout() { // Метод для выхода пользователя из системы
        session_start(); // Начинаем сессию
        session_unset();  // Очищаем данные сессии
        session_destroy();  // Завершаем сессию
        header('Location: index.php?path=login');
        exit;
    }
}
