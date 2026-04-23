<?php

namespace App\Controllers;

use App\Models\User;

class AuthController extends Controller
{
    public function showLogin(): void
    {
        if (session_get('user')) {
            redirect('');
        }

        $this->view('auth.login', ['title' => 'Đăng nhập']);
    }

    public function login(): void
    {
        $this->validateCsrf();

        $email = trim((string) $this->request->input('email'));
        $password = (string) $this->request->input('password');

        $_SESSION['_old'] = ['email' => $email];

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            session_flash('error', 'Email hoặc mật khẩu không đúng.');
            redirect('login');
        }

        if (($user['status'] ?? '') !== 'active') {
            session_flash('error', 'Tài khoản đã bị khóa.');
            redirect('login');
        }

        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];

        unset($_SESSION['_old']);
        session_flash('success', 'Đăng nhập thành công.');
        redirect('');
    }

    public function logout(): void
    {
        $this->validateCsrf();
        unset($_SESSION['user']);
        session_flash('success', 'Bạn đã đăng xuất.');
        redirect('login');
    }
}
