<?php
class User {
    private $pdo; // Объявляем свойство для хранения объекта PDO

    public function __construct($pdo) { // Конструктор класса, принимает объект PDO для работы с базой данных
        $this->pdo = $pdo; // Инициализируем свойство с подключением к базе данных
    }

    public function register($username, $password) { // Метод для регистрации нового пользователя
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Хешируем пароль для безопасного хранения
        try { // Подготавливаем SQL-запрос для вставки нового пользователя
            $stmt = $this->pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            return $stmt->execute([$username, $hashedPassword]); // Выполняем запрос с параметрами и возвращаем результат
        } catch (PDOException $e) {
                     
            return false;
        }
    }

    public function login($username, $password) { // Метод для входа пользователя в систему
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?"); // Подготавливаем SQL-запрос для получения пользователя по имени
        $stmt->execute([$username]); // Выполняем запрос с параметрами
        $user = $stmt->fetch(PDO::FETCH_ASSOC); // Получаем данные пользователя

        return $user && password_verify($password, $user['password']) ? $user : false; // Проверяем, существует ли пользователь и совпадает ли пароль
    }
}
?>
