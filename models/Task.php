<?php
class Task {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($title, $description, $status) {
        // Удалите начальные и конечные пробелы
        $title = trim($title);
        
        // Проверка, что заголовок не пустой и не состоит только из пробелов
        if (empty($title)) {
            throw new Exception('Заголовок не может быть пустым или состоять только из пробелов.');
        }

        $stmt = $this->pdo->prepare("INSERT INTO tasks (title, description, status) VALUES (?, ?, ?)");
        $stmt->execute([$title, $description, $status]);
    }

    public function getAll($orderBy = 'created_at', $direction = 'DESC', $status = null, $searchTerm = '') {
        $query = "SELECT * FROM tasks WHERE (title LIKE ? OR description LIKE ?)";
        $params = ['%' . $searchTerm . '%', '%' . $searchTerm . '%'];

        if ($status) {
            $query .= " AND status = ?";
            $params[] = $status;
        }

        $query .= " ORDER BY $orderBy $direction";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $title, $description, $status) {
        $stmt = $this->pdo->prepare("UPDATE tasks SET title = ?, description = ?, status = ? WHERE id = ?");
        $stmt->execute([$title, $description, $status, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->execute([$id]);
    }
}
?>