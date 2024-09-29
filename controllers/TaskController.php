<?php
require_once 'models/Task.php';

class TaskController {
    private $taskModel;

    public function __construct($pdo) {
        $this->taskModel = new Task($pdo);
    }

    public function index() {
        $tasks = $this->taskModel->getAll($_GET['orderBy'] ?? 'created_at', $_GET['direction'] ?? 'DESC', $_GET['status'] ?? null, $_GET['search'] ?? '');
        include 'views/tasks.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->taskModel->create($_POST['title'], $_POST['description'], $_POST['status'] ?? 'not completed');
            header('Location: index.php');
            exit;
        }
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