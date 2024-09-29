<?php ob_start(); ?>
<h1>Управление задачами</h1>
<p class="logout"><a href="index.php?path=logout">Выход</a></p>

<form method="POST" class="task-form" id="task-form">
    <input type="text" name="title" placeholder="Заголовок" required>
    <textarea name="description" placeholder="Описание"></textarea>
    <select name="status">
        <option value="not completed">Не выполнена</option>
        <option value="completed">Выполнена</option>
    </select>
    <button type="submit" name="create">Добавить задачу</button>
</form>
<h2>Список задач</h2>
<form method="GET" class="filter-form">
    <input type="text" name="search" placeholder="Поиск" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
    <select name="status">
        <option value="">Все статусы</option>
        <option value="completed" <?= isset($_GET['status']) && $_GET['status'] === 'completed' ? 'selected' : '' ?>>Выполнено</option>
        <option value="not completed" <?= isset($_GET['status']) && $_GET['status'] === 'not completed' ? 'selected' : '' ?>>Не выполнено</option>
    </select>
    <button type="submit">Фильтровать</button>
</form>
<h2>Сортировать по:</h2>
<div class="sorting">
    <a href="?orderBy=title&direction=ASC&search=<?= htmlspecialchars($_GET['search'] ?? '') ?>&status=<?= htmlspecialchars($_GET['status'] ?? '') ?>">Заголовку ↑</a>
    <a href="?orderBy=title&direction=DESC&search=<?= htmlspecialchars($_GET['search'] ?? '') ?>&status=<?= htmlspecialchars($_GET['status'] ?? '') ?>">Заголовку ↓</a>
    <a href="?orderBy=created_at&direction=ASC&search=<?= htmlspecialchars($_GET['search'] ?? '') ?>&status=<?= htmlspecialchars($_GET['status'] ?? '') ?>">Дате создания ↑</a>
    <a href="?orderBy=created_at&direction=DESC&search=<?= htmlspecialchars($_GET['search'] ?? '') ?>&status=<?= htmlspecialchars($_GET['status'] ?? '') ?>">Дате создания ↓</a>
</div>
<div class="task-list">
    <?php foreach ($tasks as $task): ?>
    <div class="task-item">
        <h3><?= htmlspecialchars($task['title']) ?></h3>
        <p><?= htmlspecialchars($task['description']) ?></p>
        <p>Статус: <?= htmlspecialchars($task['status']) ?></p>
        <p>Дата создания: <?= htmlspecialchars($task['created_at']) ?></p>
        <form method="POST" class="task-actions">
            <input type="hidden" name="id" value="<?= $task['id'] ?>">
            <button type="submit" name="delete">Удалить</button>
            <button type="button" onclick="editTask('<?= $task['id'] ?>', '<?= htmlspecialchars($task['title']) ?>', '<?= htmlspecialchars($task['description']) ?>', '<?= htmlspecialchars($task['status']) ?>')">Редактировать</button>
        </form>
    </div>
    <?php endforeach; ?>
</div>
<?php
$content = ob_get_clean();
include 'layout.php';
?>