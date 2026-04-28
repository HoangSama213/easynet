<?php

namespace App\Controllers;

use App\Models\EcosystemRegistry;
use App\Models\ElectromechanicalLightCurrent;

class ElectromechanicalLightCurrentController extends Controller
{
    public function index(): void
    {
        $model = new ElectromechanicalLightCurrent();
        $keyword = trim((string) $this->request->input('keyword', ''));
        $page = (int) $this->request->input('page', 1);
        $pagination = $model->paginate($this->systems(), $keyword, $page, 10);

        $this->view('electromechanical_light_current.index', [
            'title' => 'Cơ điện điện nhẹ',
            'keyword' => $keyword,
            'items' => $pagination['items'],
            'pagination' => $pagination,
            'heading' => 'Quản lý cơ điện điện nhẹ',
            'countLabel' => 'hệ sinh thái',
            'basePath' => 'co-dien-dien-nhe',
            'createUrl' => base_url('co-dien-dien-nhe/create'),
            'topbarFilters' => [
                'action' => base_url('co-dien-dien-nhe'),
                'keyword' => $keyword,
                'placeholder' => 'Tìm hệ sinh thái, chi tiết, hãng nổi bật, sản phẩm...',
            ],
        ]);
    }

    public function create(): void
    {
        $this->requireEditor();
        $model = new ElectromechanicalLightCurrent();

        $this->view('catalog_entries.form', [
            'title' => 'Thêm mục cơ điện điện nhẹ',
            'heading' => 'Thêm mục cơ điện điện nhẹ',
            'formAction' => base_url('co-dien-dien-nhe/store'),
            'backUrl' => base_url('co-dien-dien-nhe'),
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
        $model = new ElectromechanicalLightCurrent();
        $payload = $this->validatedPayload('co-dien-dien-nhe/create');
        $model->create($payload['table_name'], $payload['data'], $this->systems());

        unset($_SESSION['_errors'], $_SESSION['_old']);
        session_flash('success', 'Đã thêm dữ liệu cơ điện điện nhẹ.');
        redirect('co-dien-dien-nhe');
    }

    public function edit(string $table, string $id): void
    {
        $this->requireEditor();
        $model = new ElectromechanicalLightCurrent();
        $item = $model->find($table, (int) $id, $this->systems());

        if (!$item) {
            http_response_code(404);
            $this->view('dashboard.not-found', ['title' => 'Không tìm thấy trang']);
            return;
        }

        $this->view('catalog_entries.form', [
            'title' => 'Cập nhật mục cơ điện điện nhẹ',
            'heading' => 'Cập nhật mục cơ điện điện nhẹ',
            'formAction' => base_url('co-dien-dien-nhe/update/' . $table . '/' . (int) $id),
            'backUrl' => base_url('co-dien-dien-nhe'),
            'submitLabel' => 'Cập nhật thông tin',
            'systems' => $model->systemOptions($this->systems()),
            'errors' => session_get('_errors', []),
            'old' => session_get('_old', $item),
            'deleteAction' => base_url('co-dien-dien-nhe/delete/' . $table . '/' . (int) $id),
            'deleteName' => $item['chi_tiet'],
            'lockSystem' => true,
        ]);

        unset($_SESSION['_errors'], $_SESSION['_old']);
    }

    public function update(string $table, string $id): void
    {
        $this->requireEditor();
        $this->validateCsrf();
        $model = new ElectromechanicalLightCurrent();
        $payload = $this->validatedPayload('co-dien-dien-nhe/edit/' . $table . '/' . (int) $id, $table);
        $model->update($table, (int) $id, $payload['data'], $this->systems());

        unset($_SESSION['_errors'], $_SESSION['_old']);
        session_flash('success', 'Đã cập nhật dữ liệu cơ điện điện nhẹ.');
        redirect('co-dien-dien-nhe');
    }

    public function destroy(string $table, string $id): void
    {
        $this->requireEditor();
        $this->validateCsrf();
        $model = new ElectromechanicalLightCurrent();
        $model->delete($table, (int) $id, $this->systems());

        session_flash('success', 'Đã xóa dữ liệu cơ điện điện nhẹ.');
        redirect('co-dien-dien-nhe');
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
        return (new EcosystemRegistry())->bySection('co-dien-dien-nhe');
    }
}
