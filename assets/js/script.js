function editTask(id, title, description, status) {
    location.href = '#task-form';
    document.querySelector('[name="title"]').value = title;
    document.querySelector('[name="description"]').value = description;
    document.querySelector('[name="status"]').value = status;
    const form = document.getElementById('task-form');
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'id';
    input.value = id;
    form.appendChild(input);
    form.querySelector('button[name="create"]').name = 'update';
}

document.addEventListener('DOMContentLoaded', function() {
    // Обработчик для формы регистрации
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(event) {
            event.preventDefault();
            let formData = new FormData(this);

            fetch('index.php?path=register', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'index.php?path=login';
                } else {
                    document.getElementById('registerError').textContent = data.message;
                }
            });
        });
    }
    // Обработчик для формы входа
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(event) {
            event.preventDefault();
            let formData = new FormData(this);

            fetch('index.php?path=login', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'index.php?path=tasks';
                } else {
                    document.getElementById('loginError').textContent = data.message;
                }
            });
        });
    }
});

