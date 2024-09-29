<?php
require_once 'models/Task.php';

class TaskController {
    private $taskModel; // Объявляем свойство для хранения экземпляра модели

    // Конструктор класса, принимает объект PDO для работы с базой данных
    public function __construct($pdo) {
        $this->taskModel = new Task($pdo); // Инициализируем модель Task с подключением к БД
    }

    // Метод для отображения списка задач
    public function index() {
        $searchTerm = $_GET['search'] ?? ''; // Получаем параметр поиска из URL, если он есть
        $searchTerm = trim($searchTerm); // Удаляем начальные и конечные пробелы
        $tasks = $this->taskModel->getAll($_GET['orderBy'] ?? 'created_at', $_GET['direction'] ?? 'DESC', $_GET['status'] ?? null, $searchTerm); // Получаем список задач с учетом параметров сортировки, статуса и поиска
        include 'views/tasks.php';
    }
    // Метод для создания новой задачи
    public function create() { // Проверяем, что запрос - POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->taskModel->create($_POST['title'], $_POST['description'], $_POST['status'] ?? 'not completed'); // Создаем задачу с данными из POST-запроса
                header('Location: index.php');
                exit;
            } catch (Exception $e) { // Если возникла ошибка, сохраняем сообщение об ошибке в сессии
                $_SESSION['error'] = $e->getMessage();
                header('Location: form.php'); 
                exit;
            }
        }
    }
    // Метод для обновления существующей задачи
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Проверяем, что запрос - POST
            $this->taskModel->update($_POST['id'], $_POST['title'], $_POST['description'], $_POST['status']); // Обновляем задачу с данными из POST-запроса
            header('Location: index.php');
            exit;
        }
    }
    // Метод для удаления задачи
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Проверяем, что запрос - POST
            $this->taskModel->delete($_POST['id']); // Удаляем задачу по id
            header('Location: index.php');
            exit;
        }
    }
}
?>
