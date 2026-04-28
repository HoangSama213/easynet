<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';

if (!function_exists('require_editor_access')) {
    function require_editor_access(): array
    {
        $user = require_auth();

        if (($user['role'] ?? '') !== 'editor') {
            $_SESSION['auth_error'] = 'Bạn không có quyền thực hiện thao tác này';
            header('Location: ' . project_url());
            exit;
        }

        return $user;
    }
}
