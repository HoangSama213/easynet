<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\ThongBao;
use Throwable;

class Controller
{
    public function __construct(protected Request $request)
    {
    }

    protected function view(string $view, array $data = [], string $layout = 'layouts.app'): void
    {
        if (session_get('user')) {
            try {
                $thongBaoModel = new ThongBao();
                $thongBaoModel->dongBoTonKho();
                $data['thongBaoTopbar'] = $thongBaoModel->topbarData();
            } catch (Throwable) {
                $data['thongBaoTopbar'] = [
                    'so_chua_doc' => 0,
                    'moi' => [],
                    'truoc_do' => [],
                ];
            }
        }

        Response::view($view, $data, $layout);
    }

    protected function requireAuth(): array
    {
        require_once dirname(__DIR__, 2) . '/middleware/auth.php';

        return require_auth();
    }

    protected function requireEditor(): array
    {
        require_once dirname(__DIR__, 2) . '/middleware/require_editor.php';

        return require_editor_access();
    }

    protected function validateCsrf(): void
    {
        if (!hash_equals(csrf_token(), (string) $this->request->input('_token', ''))) {
            http_response_code(419);
            exit('Phiên làm việc không hợp lệ.');
        }
    }
}
