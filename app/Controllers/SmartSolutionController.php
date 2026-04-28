<?php

namespace App\Controllers;

use App\Models\EcosystemRegistry;
use App\Models\SystemCatalog;

class SmartSolutionController extends Controller
{
    public function index(): void
    {
        $model = new SystemCatalog();
        $keyword = trim((string) $this->request->input('keyword', ''));
        $page = (int) $this->request->input('page', 1);
        $pagination = $model->paginate($this->systems(), $keyword, $page, 10);

        $this->view('smart_solution.index', [
            'title' => 'Smart Solution',
            'keyword' => $keyword,
            'items' => $pagination['items'],
            'pagination' => $pagination,
            'heading' => 'Quản lý smart solution',
            'countLabel' => 'hệ sinh thái',
            'basePath' => 'smart-solution',
            'createUrl' => base_url('smart-solution/create'),
            'topbarFilters' => [
                'action' => base_url('smart-solution'),
                'keyword' => $keyword,
                'placeholder' => 'Tìm hệ sinh thái, chi tiết, hãng nổi bật, sản phẩm...',
            ],
        ]);
    }

    public function create(): void
    {
        $this->requireEditor();
        $model = new SystemCatalog();

        $this->view('catalog_entries.form', [
            'title' => 'Thêm mục smart solution',
            'heading' => 'Thêm mục smart solution',
            'formAction' => base_url('smart-solution/store'),
            'backUrl' => base_url('smart-solution'),
            'submitLabel' => 'Lưu dữ liệu',
            'systems' => $model->systemOptions($this->systems()),
            'errors' => session_get('_errors', []),
            'old' => session_get('_old', []),
        ]);

        unset($_SESSION['_errors'], $_SESSION['_old']);
    }

    public function store(): void
    {
        $this->requireEditor();
        $this->validateCsrf();
        $model = new SystemCatalog();
        $payload = $this->validatedPayload('smart-solution/create');
        $model->create($payload['table_name'], $payload['data'], $this->systems());

        unset($_SESSION['_errors'], $_SESSION['_old']);
        session_flash('success', 'Đã thêm dữ liệu smart solution.');
        redirect('smart-solution');
    }

    public function edit(string $table, string $id): void
    {
        $this->requireEditor();
        $model = new SystemCatalog();
        $item = $model->find($table, (int) $id, $this->systems());

        if (!$item) {
            http_response_code(404);
            $this->view('dashboard.not-found', ['title' => 'Không tìm thấy trang']);
            return;
        }

        $this->view('catalog_entries.form', [
            'title' => 'Cập nhật mục smart solution',
            'heading' => 'Cập nhật mục smart solution',
            'formAction' => base_url('smart-solution/update/' . $table . '/' . (int) $id),
            'backUrl' => base_url('smart-solution'),
            'submitLabel' => 'Cập nhật thông tin',
            'systems' => $model->systemOptions($this->systems()),
            'errors' => session_get('_errors', []),
            'old' => session_get('_old', $item),
            'deleteAction' => base_url('smart-solution/delete/' . $table . '/' . (int) $id),
            'deleteName' => $item['chi_tiet'],
            'lockSystem' => true,
        ]);

        unset($_SESSION['_errors'], $_SESSION['_old']);
    }

    public function update(string $table, string $id): void
    {
        $this->requireEditor();
        $this->validateCsrf();
        $model = new SystemCatalog();
        $payload = $this->validatedPayload('smart-solution/edit/' . $table . '/' . (int) $id, $table);
        $model->update($table, (int) $id, $payload['data'], $this->systems());

        unset($_SESSION['_errors'], $_SESSION['_old']);
        session_flash('success', 'Đã cập nhật dữ liệu smart solution.');
        redirect('smart-solution');
    }

    public function destroy(string $table, string $id): void
    {
        $this->requireEditor();
        $this->validateCsrf();
        $model = new SystemCatalog();
        $model->delete($table, (int) $id, $this->systems());

        session_flash('success', 'Đã xóa dữ liệu smart solution.');
        redirect('smart-solution');
    }

    private function validatedPayload(string $redirectPath, ?string $lockedTable = null): array
    {
        $tableName = $lockedTable ?? trim((string) $this->request->input('table_name', ''));
        $data = [
            'chi_tiet' => trim((string) $this->request->input('chi_tiet', '')),
            'hang_noi_bat' => trim((string) $this->request->input('hang_noi_bat', '')),
            'san_pham' => trim((string) $this->request->input('san_pham', '')),
            'nha_phan_phoi' => trim((string) $this->request->input('nha_phan_phoi', '')),
            'ghi_chu' => trim((string) $this->request->input('ghi_chu', '')),
        ];

        $errors = [];

        if ($tableName === '') {
            $errors['table_name'] = 'Hệ sinh thái là bắt buộc.';
        }

        if ($data['chi_tiet'] === '') {
            $errors['chi_tiet'] = 'Chi tiết là bắt buộc.';
        }

        if ($errors) {
            $_SESSION['_errors'] = $errors;
            $_SESSION['_old'] = array_merge($data, ['table_name' => $tableName]);
            session_flash('error', 'Vui lòng kiểm tra lại dữ liệu.');
            redirect($redirectPath);
        }

        $data['hang_noi_bat'] = $data['hang_noi_bat'] === '' ? null : $data['hang_noi_bat'];
        $data['san_pham'] = $data['san_pham'] === '' ? null : $data['san_pham'];
        $data['nha_phan_phoi'] = $data['nha_phan_phoi'] === '' ? null : $data['nha_phan_phoi'];
        $data['ghi_chu'] = $data['ghi_chu'] === '' ? null : $data['ghi_chu'];

        return [
            'table_name' => $tableName,
            'data' => $data,
        ];
    }

    private function systems(): array
    {
        return (new EcosystemRegistry())->bySection('smart-solution');
    }
}
