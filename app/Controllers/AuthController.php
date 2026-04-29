<?php

namespace App\Controllers;

use App\Core\App;

class AuthController extends Controller
{
    public function showLogin(): void
    {
        require_once dirname(__DIR__, 2) . '/middleware/auth.php';

        if (auth_user() !== null) {
            redirect('');
        }

        $this->view('auth.login', [
            'title' => 'Đăng nhập',
            'loginError' => (string) ($_SESSION['login_error'] ?? ''),
            'oldEmail' => (string) ($_SESSION['login_old_email'] ?? ''),
        ], '');

        unset($_SESSION['login_error'], $_SESSION['login_old_email']);
    }

    public function login(): void
    {
        if ($this->request->method() !== 'POST') {
            redirect('login');
        }

        $this->validateCsrf();

        $email = trim((string) $this->request->input('email', ''));
        $password = (string) $this->request->input('password', '');

        $_SESSION['login_old_email'] = $email;

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
            $_SESSION['login_error'] = 'Email hoặc mật khẩu không hợp lệ.';
            redirect('login');
        }

        $pdo = App::get('db')->pdo();
        $statement = $pdo->prepare(
            'SELECT id, ho_ten, email, password, role, chuc_vu, trang_thai
             FROM users
             WHERE email = :email
             LIMIT 1'
        );
        $statement->execute(['email' => $email]);
        $user = $statement->fetch();

        if (!$user) {
            $_SESSION['login_error'] = 'Email hoặc mật khẩu không đúng';
            redirect('login');
        }

        if (($user['trang_thai'] ?? '') === 'khoa') {
            $_SESSION['login_error'] = 'Tài khoản đã bị khóa';
            redirect('login');
        }

        if (!password_verify($password, (string) $user['password'])) {
            $_SESSION['login_error'] = 'Email hoặc mật khẩu không đúng';
            redirect('login');
        }

        session_regenerate_id(true);

        $_SESSION['id'] = (int) $user['id'];
        $_SESSION['ho_ten'] = (string) $user['ho_ten'];
        $_SESSION['email'] = (string) $user['email'];
        $_SESSION['role'] = (string) $user['role'];
        $_SESSION['chuc_vu'] = (string) ($user['chuc_vu'] ?? '');
        $_SESSION['user'] = [
            'id' => (int) $user['id'],
            'name' => (string) $user['ho_ten'],
            'ho_ten' => (string) $user['ho_ten'],
            'email' => (string) $user['email'],
            'role' => (string) $user['role'],
            'chuc_vu' => (string) ($user['chuc_vu'] ?? ''),
        ];

        $update = $pdo->prepare(
            'UPDATE users
             SET lan_dang_nhap_cuoi = NOW()
             WHERE id = :id'
        );
        $update->execute(['id' => (int) $user['id']]);

        unset($_SESSION['login_error'], $_SESSION['login_old_email']);
        session_write_close();

        redirect('');
    }

    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
        session_write_close();

        redirect('login');
    }
}
