<?php

namespace App\Controllers;

use App\Models\Customer;

class CustomerController extends Controller
{
    public function index(): void
    {
        $model = new Customer();
        $keyword = trim((string) $this->request->input('keyword', ''));
        $page = (int) $this->request->input('page', 1);
        $pagination = $model->paginate($keyword, $page, 10);

        $this->view('customers.index', [
            'title' => 'Khách hàng',
            'keyword' => $keyword,
            'items' => $pagination['items'],
            'pagination' => $pagination,
            'createUrl' => base_url('khach-hang/create'),
            'topbarFilters' => [
                'action' => base_url('khach-hang'),
                'keyword' => $keyword,
                'placeholder' => 'Tìm phân loại, nhóm khách hàng, khách hàng tiêu biểu...',
            ],
        ]);
    }

    public function create(): void
    {
        $model = new Customer();

        $this->view('customers.form', [
            'title' => 'Thêm khách hàng',
            'formAction' => base_url('khach-hang/store'),
            'backUrl' => base_url('khach-hang'),
            'submitLabel' => 'Lưu dữ liệu',
            'errors' => session_get('_errors', []),
            'old' => session_get('_old', []),
            'categoryOptions' => $model->categoryOptions(),
        ]);

        unset($_SESSION['_errors'], $_SESSION['_old']);
    }

    public function store(): void
    {
        $this->validateCsrf();
        $model = new Customer();
        $payload = $this->validatedPayload('khach-hang/create');
        $model->create($payload);

        unset($_SESSION['_errors'], $_SESSION['_old']);
        session_flash('success', 'Đã thêm khách hàng.');
        redirect('khach-hang');
    }

    public function edit(string $id): void
    {
        $model = new Customer();
        $item = $model->find((int) $id);

        if (!$item) {
            http_response_code(404);
            $this->view('dashboard.not-found', ['title' => 'Không tìm thấy trang']);
            return;
        }

        $this->view('customers.form', [
            'title' => 'Cập nhật khách hàng',
            'formAction' => base_url('khach-hang/update/' . (int) $id),
            'backUrl' => base_url('khach-hang'),
            'submitLabel' => 'Cập nhật thông tin',
            'errors' => session_get('_errors', []),
            'old' => session_get('_old', $item),
            'deleteAction' => base_url('khach-hang/delete/' . (int) $id),
            'deleteName' => $item['phan_loai_khach_hang'] ?: $item['phan_loai'],
            'categoryOptions' => $this->mergeOptions($model->categoryOptions(), $item['phan_loai'] ?? ''),
        ]);

        unset($_SESSION['_errors'], $_SESSION['_old']);
    }

    public function update(string $id): void
    {
        $this->validateCsrf();
        $model = new Customer();
        $payload = $this->validatedPayload('khach-hang/edit/' . (int) $id);
        $model->update((int) $id, $payload);

        unset($_SESSION['_errors'], $_SESSION['_old']);
        session_flash('success', 'Đã cập nhật khách hàng.');
        redirect('khach-hang');
    }

    public function destroy(string $id): void
    {
        $this->validateCsrf();
        $model = new Customer();
        $model->delete((int) $id);

        session_flash('success', 'Đã xóa khách hàng.');
        redirect('khach-hang');
    }

    private function validatedPayload(string $redirectPath): array
    {
        $payload = [
            'phan_loai' => trim((string) $this->request->input('phan_loai', '')),
            'phan_loai_khach_hang' => trim((string) $this->request->input('phan_loai_khach_hang', '')),
            'khach_hang_tieu_bieu' => trim((string) $this->request->input('khach_hang_tieu_bieu', '')),
            'ghi_chu' => trim((string) $this->request->input('ghi_chu', '')),
        ];

        $errors = [];

        if ($payload['phan_loai'] === '') {
            $errors['phan_loai'] = 'Phân loại là bắt buộc.';
        }

        if ($errors) {
            $_SESSION['_errors'] = $errors;
            $_SESSION['_old'] = $payload;
            session_flash('error', 'Vui lòng kiểm tra lại dữ liệu.');
            redirect($redirectPath);
        }

        $payload['phan_loai_khach_hang'] = $payload['phan_loai_khach_hang'] === '' ? null : $payload['phan_loai_khach_hang'];
        $payload['khach_hang_tieu_bieu'] = $payload['khach_hang_tieu_bieu'] === '' ? null : $payload['khach_hang_tieu_bieu'];
        $payload['ghi_chu'] = $payload['ghi_chu'] === '' ? null : $payload['ghi_chu'];

        return $payload;
    }

    private function mergeOptions(array $options, string $current): array
    {
        $current = trim($current);
        if ($current !== '' && !in_array($current, $options, true)) {
            $options[] = $current;
            sort($options);
        }

        return $options;
    }
}
