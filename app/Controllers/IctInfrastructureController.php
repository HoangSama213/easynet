<?php

namespace App\Controllers;

use App\Models\SystemCatalog;

class IctInfrastructureController extends Controller
{
    public function index(): void
    {
        $model = new SystemCatalog();
        $keyword = trim((string) $this->request->input('keyword', ''));
        $page = (int) $this->request->input('page', 1);
        $pagination = $model->paginate($this->systems(), $keyword, $page, 10);

        $this->view('ict_infrastructure.index', [
            'title' => 'Hạ tầng ICT',
            'keyword' => $keyword,
            'items' => $pagination['items'],
            'pagination' => $pagination,
            'heading' => 'Quản lý hạ tầng ICT',
            'countLabel' => 'hệ sinh thái',
            'basePath' => 'ha-tang-ict',
            'createUrl' => base_url('ha-tang-ict/create'),
            'topbarFilters' => [
                'action' => base_url('ha-tang-ict'),
                'keyword' => $keyword,
                'placeholder' => 'Tìm hệ sinh thái, chi tiết, hãng nổi bật, sản phẩm...',
            ],
        ]);
    }

    public function create(): void
    {
        $model = new SystemCatalog();

        $this->view('catalog_entries.form', [
            'title' => 'Thêm mục hạ tầng ICT',
            'heading' => 'Thêm mục hạ tầng ICT',
            'formAction' => base_url('ha-tang-ict/store'),
            'backUrl' => base_url('ha-tang-ict'),
            'submitLabel' => 'Lưu dữ liệu',
            'systems' => $model->systemOptions($this->systems()),
            'errors' => session_get('_errors', []),
            'old' => session_get('_old', []),
        ]);

        unset($_SESSION['_errors'], $_SESSION['_old']);
    }

    public function store(): void
    {
        $this->validateCsrf();
        $model = new SystemCatalog();
        $payload = $this->validatedPayload('ha-tang-ict/create');
        $model->create($payload['table_name'], $payload['data'], $this->systems());

        unset($_SESSION['_errors'], $_SESSION['_old']);
        session_flash('success', 'Đã thêm dữ liệu hạ tầng ICT.');
        redirect('ha-tang-ict');
    }

    public function edit(string $table, string $id): void
    {
        $model = new SystemCatalog();
        $item = $model->find($table, (int) $id, $this->systems());

        if (!$item) {
            http_response_code(404);
            $this->view('dashboard.not-found', ['title' => 'Không tìm thấy trang']);
            return;
        }

        $this->view('catalog_entries.form', [
            'title' => 'Cập nhật mục hạ tầng ICT',
            'heading' => 'Cập nhật mục hạ tầng ICT',
            'formAction' => base_url('ha-tang-ict/update/' . $table . '/' . (int) $id),
            'backUrl' => base_url('ha-tang-ict'),
            'submitLabel' => 'Cập nhật thông tin',
            'systems' => $model->systemOptions($this->systems()),
            'errors' => session_get('_errors', []),
            'old' => session_get('_old', $item),
            'deleteAction' => base_url('ha-tang-ict/delete/' . $table . '/' . (int) $id),
            'deleteName' => $item['chi_tiet'],
            'lockSystem' => true,
        ]);

        unset($_SESSION['_errors'], $_SESSION['_old']);
    }

    public function update(string $table, string $id): void
    {
        $this->validateCsrf();
        $model = new SystemCatalog();
        $payload = $this->validatedPayload('ha-tang-ict/edit/' . $table . '/' . (int) $id, $table);
        $model->update($table, (int) $id, $payload['data'], $this->systems());

        unset($_SESSION['_errors'], $_SESSION['_old']);
        session_flash('success', 'Đã cập nhật dữ liệu hạ tầng ICT.');
        redirect('ha-tang-ict');
    }

    public function destroy(string $table, string $id): void
    {
        $this->validateCsrf();
        $model = new SystemCatalog();
        $model->delete($table, (int) $id, $this->systems());

        session_flash('success', 'Đã xóa dữ liệu hạ tầng ICT.');
        redirect('ha-tang-ict');
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
        return [
            ['table' => 'kenh_truyen_dan_toc_do_cao', 'group' => 'Hạ tầng ICT', 'label' => 'Kênh truyền dẫn mạng viễn thông tốc độ cao', 'order' => 1],
            ['table' => 'ha_tang_cntt_thue_ngoai', 'group' => 'Hạ tầng ICT', 'label' => 'Hạ tầng CNTT thuê ngoài', 'order' => 1],
            ['table' => 'trung_tam_du_lieu', 'group' => 'Hạ tầng ICT', 'label' => 'Trung tâm dữ liệu', 'order' => 1],
            ['table' => 'thiet_bi_phan_cung', 'group' => 'Hạ tầng ICT', 'label' => 'Thiết bị phần cứng', 'order' => 1],
            ['table' => 'phan_mem', 'group' => 'Hạ tầng ICT', 'label' => 'Phần mềm', 'order' => 1],
            ['table' => 'mang', 'group' => 'Hạ tầng ICT', 'label' => 'Mạng', 'order' => 1],
            ['table' => 'du_lieu_luu_tru', 'group' => 'Hạ tầng ICT', 'label' => 'Dữ liệu & Lưu trữ', 'order' => 1],
            ['table' => 'bao_mat', 'group' => 'Hạ tầng ICT', 'label' => 'Bảo mật', 'order' => 1],
            ['table' => 'thiet_bi_tin_hoc_van_phong', 'group' => 'Hạ tầng ICT', 'label' => 'Thiết bị tin học văn phòng', 'order' => 1],
        ];
    }
}
