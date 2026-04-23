<?php

namespace App\Controllers;

use App\Models\Supplier;

class ItemController extends Controller
{
    public function index(): void
    {
        $supplierModel = new Supplier();
        $keyword = trim((string) $this->request->input('keyword', ''));
        $categoryId = (int) $this->request->input('category_id', 0);
        $page = (int) $this->request->input('page', 1);
        $pagination = $supplierModel->paginate($keyword, $categoryId, $page, 5);

        $this->view('items.index', [
            'title' => 'Quản lý nhà cung cấp',
            'suppliers' => $pagination['items'],
            'pagination' => $pagination,
            'keyword' => $keyword,
            'categoryId' => $categoryId,
            'categories' => $supplierModel->categories(),
        ]);
    }

    public function create(): void
    {
        $supplierModel = new Supplier();

        $this->view('items.form', [
            'title' => 'Thêm nhà cung cấp',
            'categories' => $supplierModel->categories(),
            'errors' => session_get('_errors', []),
            'old' => session_get('_old', []),
            'formAction' => base_url('items/store'),
            'submitLabel' => 'Lưu nhà cung cấp',
        ]);

        unset($_SESSION['_errors'], $_SESSION['_old']);
    }

    public function store(): void
    {
        $this->validateCsrf();
        $supplierModel = new Supplier();
        $payload = $this->validatedPayload($supplierModel, 'items/create');

        try {
            $supplierModel->create($payload);
        } catch (\Throwable $exception) {
            $_SESSION['_old'] = $payload;
            session_flash('error', 'Không thể thêm nhà cung cấp lúc này. Vui lòng kiểm tra lại dữ liệu và thử lại.');
            redirect('items/create');
        }

        unset($_SESSION['_errors'], $_SESSION['_old']);
        session_flash('success', 'Đã thêm nhà cung cấp mới.');
        redirect('items');
    }

    public function edit(string $id): void
    {
        $supplierModel = new Supplier();
        $supplier = $supplierModel->find((int) $id);

        if (!$supplier) {
            http_response_code(404);
            $this->view('dashboard.not-found', ['title' => 'Không tìm thấy trang']);
            return;
        }

        $this->view('items.form', [
            'title' => 'Cập nhật nhà cung cấp',
            'categories' => $supplierModel->categories(),
            'errors' => session_get('_errors', []),
            'old' => session_get('_old', $supplier),
            'formAction' => base_url('items/update/' . $supplier['id']),
            'submitLabel' => 'Cập nhật thông tin',
            'deleteAction' => base_url('items/delete/' . $supplier['id']),
            'deleteName' => $supplier['ten_ncc'],
        ]);

        unset($_SESSION['_errors'], $_SESSION['_old']);
    }

    public function update(string $id): void
    {
        $this->validateCsrf();
        $supplierModel = new Supplier();
        $supplier = $supplierModel->find((int) $id);

        if (!$supplier) {
            http_response_code(404);
            $this->view('dashboard.not-found', ['title' => 'Không tìm thấy trang']);
            return;
        }

        $payload = $this->validatedPayload($supplierModel, 'items/edit/' . (int) $id);
        $supplierModel->update((int) $id, $payload);

        unset($_SESSION['_errors'], $_SESSION['_old']);
        session_flash('success', 'Đã cập nhật nhà cung cấp.');
        redirect('items');
    }

    public function destroy(string $id): void
    {
        $this->validateCsrf();
        $supplierModel = new Supplier();
        $supplier = $supplierModel->find((int) $id);

        if (!$supplier) {
            http_response_code(404);
            $this->view('dashboard.not-found', ['title' => 'Không tìm thấy trang']);
            return;
        }

        try {
            $supplierModel->delete((int) $id);
        } catch (\Throwable $exception) {
            session_flash('error', 'Không thể xóa nhà cung cấp lúc này. Vui lòng kiểm tra dữ liệu liên quan và thử lại.');
            redirect('items/edit/' . (int) $id);
        }

        session_flash('success', 'Đã xóa nhà cung cấp.');
        redirect('items');
    }

    private function validatedPayload(Supplier $supplierModel, string $redirectPath): array
    {
        $payload = [
            'ten_ncc' => trim((string) $this->request->input('ten_ncc')),
            'id_hang_muc' => (int) $this->request->input('id_hang_muc'),
            'website' => trim((string) $this->request->input('website')),
            'nguoi_lien_he' => trim((string) $this->request->input('nguoi_lien_he')),
            'nhom_zalo' => trim((string) $this->request->input('nhom_zalo')),
            'san_pham_ncc' => trim((string) $this->request->input('san_pham_ncc')),
            'thuong_hieu_phan_phoi' => trim((string) $this->request->input('thuong_hieu_phan_phoi')),
            'san_pham_chi_tiet' => trim((string) $this->request->input('san_pham_chi_tiet')),
            'ghi_chu' => trim((string) $this->request->input('ghi_chu')),
        ];

        $errors = [];
        foreach ([
            'ten_ncc' => 'Nhà cung cấp',
            'id_hang_muc' => 'Hạng mục',
            'website' => 'Website',
            'nguoi_lien_he' => 'Người liên hệ',
            'nhom_zalo' => 'Group Zalo',
            'san_pham_ncc' => 'Sản phẩm NCC',
            'thuong_hieu_phan_phoi' => 'Thương hiệu phân phối',
            'san_pham_chi_tiet' => 'Sản phẩm chi tiết',
        ] as $field => $label) {
            if ($field === 'id_hang_muc') {
                if ($payload[$field] <= 0) {
                    $errors[$field] = $label . ' là bắt buộc.';
                }
                continue;
            }

            if ($payload[$field] === '') {
                $errors[$field] = $label . ' là bắt buộc.';
            }
        }

        $category = $payload['id_hang_muc'] > 0 ? $supplierModel->categoryById($payload['id_hang_muc']) : null;
        if (!$category) {
            $errors['id_hang_muc'] = 'Hạng mục không hợp lệ.';
        }

        if ($payload['website'] !== '' && !filter_var($payload['website'], FILTER_VALIDATE_URL)) {
            $errors['website'] = 'Website không đúng định dạng URL.';
        }

        if ($errors) {
            $_SESSION['_errors'] = $errors;
            $_SESSION['_old'] = $payload;
            session_flash('error', 'Vui lòng kiểm tra lại thông tin bắt buộc.');
            redirect($redirectPath);
        }

        $payload['hang_muc'] = $category['ten_hang_muc'];
        $payload['ghi_chu'] = $payload['ghi_chu'] === '' ? null : $payload['ghi_chu'];

        return $payload;
    }
}