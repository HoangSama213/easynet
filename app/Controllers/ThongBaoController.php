<?php

namespace App\Controllers;

use App\Models\ThongBao;

class ThongBaoController extends Controller
{
    public function index(): void
    {
        $model = new ThongBao();
        $model->dongBoTonKho();

        $page = (int) $this->request->input('page', 1);
        $pagination = $model->paginate($page, 12);

        $this->view('notifications.index', [
            'title' => 'Thông báo',
            'rows' => $pagination['items'],
            'pagination' => $pagination,
            'unreadCount' => $model->soChuaDoc(),
        ]);
    }

    public function markRead(string $id): void
    {
        $model = new ThongBao();
        $model->danhDauDaDoc((int) $id);

        $redirectTo = trim((string) $this->request->input('redirect_to', 'thong-bao'));
        redirect($redirectTo !== '' ? $redirectTo : 'thong-bao');
    }

    public function markAllRead(): void
    {
        $this->validateCsrf();

        $model = new ThongBao();
        $model->danhDauTatCaDaDoc();

        session_flash('success', 'Đã đánh dấu tất cả thông báo là đã đọc.');
        $redirectTo = trim((string) $this->request->input('redirect_to', ''));

        if ($redirectTo !== '' && (str_starts_with($redirectTo, '/') || str_starts_with($redirectTo, 'http'))) {
            header('Location: ' . $redirectTo);
            exit;
        }

        redirect($redirectTo !== '' ? $redirectTo : 'thong-bao');
    }
}
