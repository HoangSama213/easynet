<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name((string) require dirname(__DIR__) . '/config/session_name.php');
    session_start();
}

if (!function_exists('project_url')) {
    function project_url(string $path = ''): string
    {
        $appConfig = require dirname(__DIR__) . '/config/app.php';
        $baseUrl = rtrim((string) ($appConfig['base_url'] ?? ''), '/');
        $path = ltrim($path, '/');

        return $path === '' ? $baseUrl : $baseUrl . '/' . $path;
    }
}

if (!function_exists('sync_auth_session')) {
    function sync_auth_session(): void
    {
        $sessionUser = $_SESSION['user'] ?? null;

        if (is_array($sessionUser) && !empty($sessionUser['id']) && !empty($sessionUser['role'])) {
            $_SESSION['id'] = (int) $sessionUser['id'];
            $_SESSION['ho_ten'] = (string) ($sessionUser['ho_ten'] ?? $sessionUser['name'] ?? ($_SESSION['ho_ten'] ?? ''));
            $_SESSION['email'] = (string) ($sessionUser['email'] ?? ($_SESSION['email'] ?? ''));
            $_SESSION['role'] = (string) $sessionUser['role'];
            $_SESSION['chuc_vu'] = (string) ($sessionUser['chuc_vu'] ?? ($_SESSION['chuc_vu'] ?? ''));
            return;
        }

        if (!empty($_SESSION['id']) && !empty($_SESSION['role'])) {
            $_SESSION['user'] = [
                'id' => (int) $_SESSION['id'],
                'name' => (string) ($_SESSION['ho_ten'] ?? ''),
                'ho_ten' => (string) ($_SESSION['ho_ten'] ?? ''),
                'email' => (string) ($_SESSION['email'] ?? ''),
                'role' => (string) $_SESSION['role'],
                'chuc_vu' => (string) ($_SESSION['chuc_vu'] ?? ''),
            ];
        }
    }
}

if (!function_exists('auth_user')) {
    function auth_user(): ?array
    {
        sync_auth_session();

        $id = (int) ($_SESSION['id'] ?? 0);
        $role = trim((string) ($_SESSION['role'] ?? ''));

        if ($id <= 0 || $role === '') {
            return null;
        }

        return [
            'id' => $id,
            'ho_ten' => (string) ($_SESSION['ho_ten'] ?? ''),
            'email' => (string) ($_SESSION['email'] ?? ''),
            'role' => $role,
            'chuc_vu' => (string) ($_SESSION['chuc_vu'] ?? ''),
        ];
    }
}

if (!function_exists('require_auth')) {
    function require_auth(): array
    {
        $user = auth_user();

        if ($user === null) {
            $_SESSION['login_error'] = 'Vui lòng đăng nhập để tiếp tục.';
            header('Location: ' . project_url('login'));
            exit;
        }

        return $user;
    }
}

if (!function_exists('is_editor')) {
    function is_editor(): bool
    {
        $user = auth_user();

        return $user !== null && $user['role'] === 'editor';
    }
}

if (!function_exists('is_viewer')) {
    function is_viewer(): bool
    {
        $user = auth_user();

        return $user !== null && $user['role'] === 'viewer';
    }
}
