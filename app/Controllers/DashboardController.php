<?php

namespace App\Controllers;

use App\Models\Dashboard;

class DashboardController extends Controller
{
    public function index(): void
    {
        $dashboardModel = new Dashboard();
        $keyword = trim((string) $this->request->input('keyword', $this->request->input('q', '')));
        $currentUser = session_get('user', [
            'name' => 'Quản trị hệ thống',
            'role' => 'admin',
        ]);

        $this->view('dashboard.index', [
            'title' => 'Tổng quan',
            'currentUser' => $currentUser,
            'stats' => $dashboardModel->stats(),
            'recentSuppliers' => $dashboardModel->recentSuppliers(),
            'searchKeyword' => $keyword,
            'searchResults' => $dashboardModel->globalSearch($keyword),
            'topbarFilters' => [
                'action' => base_url(),
                'keyword' => $keyword,
                'placeholder' => 'Tên NCC, sản phẩm, thương hiệu, website',
            ],
        ]);
    }
}
