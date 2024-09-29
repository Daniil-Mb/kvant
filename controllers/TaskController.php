<?php
require_once 'models/Task.php';

class TaskController {
    private $taskModel;

    public function __construct($pdo) {
        $this->taskModel = new Task($pdo);
    }

    public function index() {
        $searchTerm = $_GET['search'] ?? '';
        $searchTerm = trim($searchTerm); // Удаляем начальные и конечные пробелы   
        $tasks = $this->taskModel->getAll($_GET['orderBy'] ?? 'created_at', $_GET['direction'] ?? 'DESC', $_GET['status'] ?? null, $searchTerm);
        include 'views/tasks.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->taskModel->create($_POST['title'], $_POST['description'], $_POST['status'] ?? 'not completed');
                header('Location: index.php');
                exit;
            } catch (Exception $e) {
                // Сохраняем сообщение об ошибке в сессии
                $_SESSION['error'] = $e->getMessage();
                header('Location: form.php'); // Перенаправление на форму
                exit;
            }
        }

        // Если метод не POST, просто отобразите форму
        include 'views/form.php'; // Убедитесь, что у вас есть такой файл
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->taskModel->update($_POST['id'], $_POST['title'], $_POST['description'], $_POST['status']);
            header('Location: index.php');
            exit;
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->taskModel->delete($_POST['id']);
            header('Location: index.php');
            exit;
        }
    }
}
?>
