<?php

namespace App\Controllers;

use App\Models\Partner;

class PartnerController extends Controller
{
    public function index(): void
    {
        $model = new Partner();
        $keyword = trim((string) $this->request->input('keyword', ''));
        $page = (int) $this->request->input('page', 1);
        $pagination = $model->paginate($keyword, $page, 10);

        $this->view('partners.index', [
            'title' => 'Đối tác',
            'keyword' => $keyword,
            'items' => $pagination['items'],
            'pagination' => $pagination,
            'createUrl' => base_url('doi-tac/create'),
        ]);
    }

    public function create(): void
    {
        $model = new Partner();

        $this->view('partners.form', [
            'title' => 'Thêm đối tác',
            'formAction' => base_url('doi-tac/store'),
            'backUrl' => base_url('doi-tac'),
            'submitLabel' => 'Lưu dữ liệu',
            'errors' => session_get('_errors', []),
            'old' => session_get('_old', []),
            'fieldOptions' => $model->fieldOptions(),
        ]);

        unset($_SESSION['_errors'], $_SESSION['_old']);
    }

    public function store(): void
    {
        $this->validateCsrf();
        $model = new Partner();
        $payload = $this->validatedPayload('doi-tac/create');
        $model->create($payload);

        unset($_SESSION['_errors'], $_SESSION['_old']);
        session_flash('success', 'Đã thêm đối tác.');
        redirect('doi-tac');
    }

    public function edit(string $id): void
    {
        $model = new Partner();
        $item = $model->find((int) $id);

        if (!$item) {
            http_response_code(404);
            $this->view('dashboard.not-found', ['title' => 'Không tìm thấy trang']);
            return;
        }

        $this->view('partners.form', [
            'title' => 'Cập nhật đối tác',
            'formAction' => base_url('doi-tac/update/' . (int) $id),
            'backUrl' => base_url('doi-tac'),
            'submitLabel' => 'Cập nhật thông tin',
            'errors' => session_get('_errors', []),
            'old' => session_get('_old', $item),
            'deleteAction' => base_url('doi-tac/delete/' . (int) $id),
            'deleteName' => $item['doi_tac_tieu_bieu'] ?: $item['linh_vuc'],
            'fieldOptions' => $this->mergeOptions($model->fieldOptions(), $item['linh_vuc'] ?? ''),
        ]);

        unset($_SESSION['_errors'], $_SESSION['_old']);
    }

    public function update(string $id): void
    {
        $this->validateCsrf();
        $model = new Partner();
        $payload = $this->validatedPayload('doi-tac/edit/' . (int) $id);
        $model->update((int) $id, $payload);

        unset($_SESSION['_errors'], $_SESSION['_old']);
        session_flash('success', 'Đã cập nhật đối tác.');
        redirect('doi-tac');
    }

    public function destroy(string $id): void
    {
        $this->validateCsrf();
        $model = new Partner();
        $model->delete((int) $id);

        session_flash('success', 'Đã xóa đối tác.');
        redirect('doi-tac');
    }

    private function validatedPayload(string $redirectPath): array
    {
        $payload = [
            'linh_vuc' => trim((string) $this->request->input('linh_vuc', '')),
            'doi_tac_tieu_bieu' => trim((string) $this->request->input('doi_tac_tieu_bieu', '')),
            'ghi_chu' => trim((string) $this->request->input('ghi_chu', '')),
        ];

        $errors = [];

        if ($payload['linh_vuc'] === '') {
            $errors['linh_vuc'] = 'Lĩnh vực là bắt buộc.';
        }

        if ($errors) {
            $_SESSION['_errors'] = $errors;
            $_SESSION['_old'] = $payload;
            session_flash('error', 'Vui lòng kiểm tra lại dữ liệu.');
            redirect($redirectPath);
        }

        $payload['doi_tac_tieu_bieu'] = $payload['doi_tac_tieu_bieu'] === '' ? null : $payload['doi_tac_tieu_bieu'];
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
