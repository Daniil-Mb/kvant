<?php
class Task {
    private $pdo; // Объявляем свойство для хранения объекта PDO

    public function __construct($pdo) { // Конструктор класса, принимает объект PDO для работы с базой данных
        $this->pdo = $pdo; // Инициализируем свойство с подключением к базе данных
    }

    public function create($title, $description, $status) { // Метод для создания новой задачи
        $title = trim($title); // Удаляем лишние пробелы из заголовка
        if (empty($title)) { // Проверяем, не пуст ли заголовок
            throw new Exception('Заголовок не может быть пустым или состоять только из пробелов.'); // Генерируем исключение, если заголовок пуст
        }

        $stmt = $this->pdo->prepare("INSERT INTO tasks (title, description, status) VALUES (?, ?, ?)"); // Подготавливаем SQL-запрос для вставки новой задачи
        $stmt->execute([$title, $description, $status]); // Выполняем запрос с параметрами
    }

    public function getAll($orderBy = 'created_at', $direction = 'DESC', $status = null, $searchTerm = '') { // Метод для получения всех задач с возможностью сортировки и фильтрации
        $query = "SELECT * FROM tasks WHERE (title LIKE ? OR description LIKE ?)"; // Подготавливаем базовый SQL-запрос для выборки задач
        $params = ['%' . $searchTerm . '%', '%' . $searchTerm . '%'];  // Параметры для поиска

        if ($status) { // Если указан статус
            $query .= " AND status = ?"; // Добавляем условие для фильтрации по статусу
            $params[] = $status; // Добавляем статус в параметры
        }

        $query .= " ORDER BY $orderBy $direction"; // Добавляем сортировку к запросу
        $stmt = $this->pdo->prepare($query); // Подготавливаем запрос
        $stmt->execute($params); // Выполняем запрос с параметрами
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Возвращаем все результаты в виде ассоциативного массива
    }

    public function update($id, $title, $description, $status) { // Метод для обновления существующей задачи
        $stmt = $this->pdo->prepare("UPDATE tasks SET title = ?, description = ?, status = ? WHERE id = ?"); // Подготавливаем SQL-запрос для обновления задачи
        $stmt->execute([$title, $description, $status, $id]); // Выполняем запрос с параметрами
    }

    public function delete($id) { // Метод для удаления задачи
        $stmt = $this->pdo->prepare("DELETE FROM tasks WHERE id = ?"); // Подготавливаем SQL-запрос для удаления задачи
        $stmt->execute([$id]); // Выполняем запрос с id задачи
    }
}
?>