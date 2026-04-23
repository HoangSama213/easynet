<?php

namespace App\Controllers;

use App\Models\SupplierProduct;
use Throwable;

class SupplierProductController extends Controller
{
    public function index(): void
    {
        $model = new SupplierProduct();
        $page = (int) $this->request->input('page', 1);
        $keyword = trim((string) $this->request->input('keyword', ''));
        $pagination = $model->paginate($page, 10, $keyword);

        $this->view('supplier_products.index', [
            'title' => 'Quản lý sản phẩm NCC',
            'rows' => $pagination['items'],
            'pagination' => $pagination,
            'keyword' => $keyword,
        ]);
    }

    public function create(): void
    {
        $model = new SupplierProduct();
        $this->view('supplier_products.show', [
            'title' => 'Thêm sản phẩm',
            'isCreate' => true,
            'formAction' => base_url('supplier-products/store'),
            'product' => null,
            'priceHistory' => [],
            'supplierOptions' => $model->supplierOptions(),
            'productOptions' => $model->productOptions(),
            'errors' => $_SESSION['_product_errors'] ?? [],
            'old' => $_SESSION['_product_old'] ?? [],
        ]);

        unset($_SESSION['_product_errors'], $_SESSION['_product_old']);
    }

    public function store(): void
    {
        $this->validateCsrf();
        $model = new SupplierProduct();
        $payload = $this->validatedPayload();

        if (!empty($payload['errors'])) {
            $_SESSION['_product_errors'] = $payload['errors'];
            $_SESSION['_product_old'] = $payload['old'];
            session_flash('error', 'Vui lòng kiểm tra lại thông tin sản phẩm.');
            redirect('supplier-products/create');
        }

        try {
            $chiTietId = $model->create($payload['data']);
            session_flash('success', 'Đã thêm sản phẩm mới.');
            redirect('supplier-products/' . (int) $payload['data']['ncc_id'] . '/' . $chiTietId);
        } catch (Throwable $exception) {
            $_SESSION['_product_old'] = $payload['old'];
            session_flash('error', 'Không thể thêm sản phẩm lúc này. Vui lòng thử lại.');
            redirect('supplier-products/create');
        }
    }

    public function show(string $nccId, string $chiTietId): void
    {
        $model = new SupplierProduct();
        $product = $model->find((int) $nccId, (int) $chiTietId);

        if (!$product) {
            http_response_code(404);
            $this->view('dashboard.not-found', ['title' => 'Không tìm thấy trang']);
            return;
        }

        $errors = $_SESSION['_product_errors'] ?? [];
        $old = $_SESSION['_product_old'] ?? [];
        unset($_SESSION['_product_errors'], $_SESSION['_product_old']);

        $this->view('supplier_products.show', [
            'title' => 'Chi tiết sản phẩm',
            'isCreate' => false,
            'formAction' => base_url('supplier-products/' . $product['ncc_id'] . '/' . $product['chi_tiet_id'] . '/update'),
            'product' => $product,
            'priceHistory' => $model->priceHistory((int) $nccId, (int) $chiTietId),
            'supplierOptions' => $model->supplierOptions(),
            'productOptions' => $model->productOptions(),
            'errors' => $errors,
            'old' => $old,
        ]);
    }

    public function update(string $nccId, string $chiTietId): void
    {
        $this->validateCsrf();

        $model = new SupplierProduct();
        $product = $model->find((int) $nccId, (int) $chiTietId);

        if (!$product) {
            http_response_code(404);
            $this->view('dashboard.not-found', ['title' => 'Không tìm thấy trang']);
            return;
        }

        $payload = $this->validatedPayload();

        if (!empty($payload['errors'])) {
            $_SESSION['_product_errors'] = $payload['errors'];
            $_SESSION['_product_old'] = $payload['old'];
            session_flash('error', 'Vui lòng kiểm tra lại thông tin sản phẩm.');
            redirect('supplier-products/' . (int) $nccId . '/' . (int) $chiTietId);
        }

        try {
            $model->update((int) $nccId, (int) $chiTietId, $payload['data']);
            $targetNccId = (int) $payload['data']['ncc_id'];
            session_flash('success', 'Đã cập nhật thông tin sản phẩm.');
            redirect('supplier-products/' . $targetNccId . '/' . (int) $chiTietId);
        } catch (Throwable $exception) {
            $_SESSION['_product_old'] = $payload['old'];
            session_flash('error', 'Không thể cập nhật sản phẩm lúc này. Vui lòng thử lại.');
            redirect('supplier-products/' . (int) $nccId . '/' . (int) $chiTietId);
        }
    }

    private function validatedPayload(): array
    {
        $nccIdInput = trim((string) $this->request->input('ncc_id', ''));
        $sanPhamIdInput = trim((string) $this->request->input('san_pham_id', ''));
        $maSku = trim((string) $this->request->input('ma_sku', ''));
        $tenChiTiet = trim((string) $this->request->input('ten_chi_tiet', ''));
        $thuongHieu = trim((string) $this->request->input('thuong_hieu', ''));
        $giaInput = trim((string) $this->request->input('gia', ''));
        $trangThai = trim((string) $this->request->input('trang_thai', ''));
        $tonKhoInput = trim((string) $this->request->input('ton_kho', ''));
        $priceNote = trim((string) $this->request->input('price_note', ''));

        $errors = [];

        if ($nccIdInput === '' || filter_var($nccIdInput, FILTER_VALIDATE_INT) === false) {
            $errors['ncc_id'] = 'Nhà cung cấp là bắt buộc.';
        }

        if ($sanPhamIdInput === '' || filter_var($sanPhamIdInput, FILTER_VALIDATE_INT) === false) {
            $errors['san_pham_id'] = 'Sản phẩm NCC là bắt buộc.';
        }

        if ($tenChiTiet === '') {
            $errors['ten_chi_tiet'] = 'Tên sản phẩm chi tiết là bắt buộc.';
        }

        if ($giaInput !== '' && !is_numeric($giaInput)) {
            $errors['gia'] = 'Giá phải là số hợp lệ.';
        }

        if ($trangThai !== '' && !in_array($trangThai, ['dang_ban', 'ngung'], true)) {
            $errors['trang_thai'] = 'Trạng thái không hợp lệ.';
        }

        if ($tonKhoInput !== '' && filter_var($tonKhoInput, FILTER_VALIDATE_INT) === false) {
            $errors['ton_kho'] = 'Tồn kho phải là số nguyên.';
        }

        $old = [
            'ncc_id' => $nccIdInput,
            'san_pham_id' => $sanPhamIdInput,
            'ma_sku' => $maSku,
            'ten_chi_tiet' => $tenChiTiet,
            'thuong_hieu' => $thuongHieu,
            'gia' => $giaInput,
            'trang_thai' => $trangThai,
            'ton_kho' => $tonKhoInput,
            'price_note' => $priceNote,
        ];

        return [
            'errors' => $errors,
            'old' => $old,
            'data' => [
                'ncc_id' => (int) $nccIdInput,
                'san_pham_id' => (int) $sanPhamIdInput,
                'ma_sku' => $maSku === '' ? null : $maSku,
                'ten_chi_tiet' => $tenChiTiet,
                'thuong_hieu' => $thuongHieu === '' ? null : $thuongHieu,
                'gia' => $giaInput === '' ? null : $giaInput,
                'trang_thai' => $trangThai === '' ? null : $trangThai,
                'ton_kho' => $tonKhoInput === '' ? null : (int) $tonKhoInput,
                'price_note' => $priceNote,
            ],
        ];
    }
}
