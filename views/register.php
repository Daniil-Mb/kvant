<?php ob_start(); ?>
<div class="auth">
    <h1 class="auth__title">Регистрация</h1>
    <form id="registerForm" class="auth__form">
        <input type="text" name="username" placeholder="Имя пользователя" required class="auth__input">
        <input type="password" name="password" placeholder="Пароль" required class="auth__input">
        <button type="submit" class="auth__button">Зарегистрироваться</button>
    </form>
    <p id="registerError" class="auth__error" style="color:red;"></p>
    <p class="auth__link">Уже есть аккаунт? <a href="index.php?path=login">Войдите</a></p>
</div>
<?php
$content = ob_get_clean();
include 'views/layout.php';
?>
