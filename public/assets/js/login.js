document.addEventListener('DOMContentLoaded', function () {
    var checkbox = document.getElementById('show-password');
    var passwordInput = document.getElementById('password');

    if (!checkbox || !passwordInput) {
        return;
    }

    checkbox.addEventListener('change', function () {
        passwordInput.type = checkbox.checked ? 'text' : 'password';
    });
});
