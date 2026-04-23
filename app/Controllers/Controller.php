<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;

class Controller
{
    public function __construct(protected Request $request)
    {
    }

    protected function view(string $view, array $data = [], string $layout = 'layouts.app'): void
    {
        Response::view($view, $data, $layout);
    }

    protected function requireAuth(): array
    {
        $user = session_get('user');

        if (!$user) {
            session_flash('error', 'Vui lòng đăng nhập để tiếp tục.');
            redirect('login');
        }

        return $user;
    }

    protected function requireRole(array $roles): array
    {
        $user = $this->requireAuth();

        if (!in_array($user['role'], $roles, true)) {
            http_response_code(403);
            $this->view('dashboard.forbidden', ['title' => 'Không có quyền truy cập']);
            exit;
        }

        return $user;
    }

    protected function validateCsrf(): void
    {
        if (!hash_equals(csrf_token(), (string) $this->request->input('_token', ''))) {
            http_response_code(419);
            exit('Phiên làm việc không hợp lệ.');
        }
    }
}
