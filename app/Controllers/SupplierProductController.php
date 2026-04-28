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
            'title' => 'Danh sách sản phẩm',
            'rows' => $pagination['items'],
            'pagination' => $pagination,
            'keyword' => $keyword,
            'topbarFilters' => [
                'action' => base_url('supplier-products'),
                'keyword' => $keyword,
                'placeholder' => 'Tìm SKU, nhà cung cấp, thương hiệu...',
            ],
        ]);
    }

    public function create(): void
    {
        $this->requireEditor();
        $model = new SupplierProduct();
        $old = $_SESSION['_supplier_product_old'] ?? [];

        if (trim((string) ($old['ma_sku'] ?? '')) === '') {
            $old['ma_sku'] = $model->nextSkuCode();
        }

        $this->view('supplier_products.show', [
            'title' => 'Thêm sản phẩm',
            'isCreate' => true,
            'formAction' => base_url('supplier-products/store'),
            'product' => null,
            'hubData' => ['content' => []],
            'priceHistory' => [],
            'supplierOptions' => $model->supplierOptions(),
            'productOptions' => $model->productOptions(),
            'errors' => $_SESSION['_supplier_product_errors'] ?? [],
            'old' => $old,
            'extraJsFiles' => ['assets/js/supplier-product-form.js'],
        ]);

        unset($_SESSION['_supplier_product_errors'], $_SESSION['_supplier_product_old']);
    }

    public function store(): void
    {
        $this->requireEditor();
        $this->validateCsrf();
        $model = new SupplierProduct();
        $payload = $this->validatedPayload();

        if ($payload['errors'] !== []) {
            $_SESSION['_supplier_product_errors'] = $payload['errors'];
            $_SESSION['_supplier_product_old'] = $payload['old'];
            session_flash('error', 'Vui lòng kiểm tra lại thông tin sản phẩm.');
            redirect('supplier-products/create');
        }

        try {
            $id = $model->create($payload['data']);
            unset($_SESSION['_supplier_product_errors'], $_SESSION['_supplier_product_old']);
            session_flash('success', 'Đã thêm sản phẩm mới.');
            redirect('supplier-products/' . $id);
        } catch (Throwable $exception) {
            $this->logException('store', $exception, $payload['data']);
            $_SESSION['_supplier_product_old'] = $payload['old'];
            session_flash('error', 'Không thể lưu sản phẩm lúc này. Vui lòng thử lại.');
            redirect('supplier-products/create');
        }
    }

    public function show(string $id): void
    {
        $model = new SupplierProduct();
        $product = $model->findById((int) $id);

        if (!$product) {
            http_response_code(404);
            $this->view('dashboard.not-found', ['title' => 'Không tìm thấy trang']);
            return;
        }

        $this->view('supplier_products.show', [
            'title' => 'Chi tiết sản phẩm',
            'isCreate' => false,
            'formAction' => base_url('supplier-products/' . (int) $id . '/update'),
            'product' => $product,
            'hubData' => $model->hubDataByProductId((int) $id),
            'priceHistory' => $model->priceHistory((int) $id),
            'supplierOptions' => $model->supplierOptions(),
            'productOptions' => $model->productOptions(),
            'errors' => $_SESSION['_supplier_product_errors'] ?? [],
            'old' => $_SESSION['_supplier_product_old'] ?? [],
            'extraJsFiles' => ['assets/js/supplier-product-form.js'],
        ]);

        unset($_SESSION['_supplier_product_errors'], $_SESSION['_supplier_product_old']);
    }

    public function update(string $id): void
    {
        $this->requireEditor();
        $this->validateCsrf();
        $model = new SupplierProduct();
        $product = $model->findById((int) $id);

        if (!$product) {
            http_response_code(404);
            $this->view('dashboard.not-found', ['title' => 'Không tìm thấy trang']);
            return;
        }

        $payload = $this->validatedPayload((int) ($product['chi_tiet_id'] ?? 0));

        if ($payload['errors'] !== []) {
            $_SESSION['_supplier_product_errors'] = $payload['errors'];
            $_SESSION['_supplier_product_old'] = $payload['old'];
            session_flash('error', 'Vui lòng kiểm tra lại thông tin sản phẩm.');
            redirect('supplier-products/' . (int) $id);
        }

        try {
            $model->update((int) $id, $payload['data']);
            unset($_SESSION['_supplier_product_errors'], $_SESSION['_supplier_product_old']);
            session_flash('success', 'Đã cập nhật sản phẩm.');
            redirect('supplier-products/' . (int) $id);
        } catch (Throwable $exception) {
            $this->logException('update', $exception, ['id' => (int) $id, 'payload' => $payload['data']]);
            $_SESSION['_supplier_product_old'] = $payload['old'];
            session_flash('error', 'Không thể cập nhật sản phẩm lúc này. Vui lòng thử lại.');
            redirect('supplier-products/' . (int) $id);
        }
    }

    public function destroy(string $id): void
    {
        $this->requireEditor();
        $this->validateCsrf();
        $model = new SupplierProduct();
        $product = $model->findById((int) $id);

        if (!$product) {
            http_response_code(404);
            $this->view('dashboard.not-found', ['title' => 'Không tìm thấy trang']);
            return;
        }

        try {
            $model->delete((int) $id);
            session_flash('success', 'Đã xóa sản phẩm.');
            redirect('supplier-products');
        } catch (Throwable $exception) {
            $this->logException('delete', $exception, ['id' => (int) $id]);
            session_flash('error', 'Không thể xóa sản phẩm lúc này.');
            redirect('supplier-products/' . (int) $id);
        }
    }

    public function historyIndex(): void
    {
        $model = new SupplierProduct();
        $keyword = trim((string) $this->request->input('keyword', ''));

        $this->view('supplier_products.history', [
            'title' => 'Lịch sử thay đổi giá',
            'rows' => $model->priceHistoryRows($keyword),
            'keyword' => $keyword,
            'topbarFilters' => [
                'action' => base_url('supplier-products/history'),
                'keyword' => $keyword,
                'placeholder' => 'Tìm SKU, nhà cung cấp, ghi chú...',
            ],
        ]);
    }

    public function mediaIndex(): void
    {
        $model = new SupplierProduct();
        $page = (int) $this->request->input('page', 1);
        $keyword = trim((string) $this->request->input('keyword', ''));
        $pagination = $model->paginateMediaRows($page, 10, $keyword);

        $this->view('supplier_products.media', [
            'title' => 'Tài nguyên media',
            'rows' => $pagination['items'],
            'pagination' => $pagination,
            'keyword' => $keyword,
            'selectedProduct' => null,
            'mediaData' => ['media' => ['anh' => null, 'video' => null, 'pdf' => null]],
            'errors' => $_SESSION['_supplier_product_media_errors'] ?? [],
            'old' => $_SESSION['_supplier_product_media_old'] ?? [],
            'topbarFilters' => [
                'action' => base_url('supplier-products/media'),
                'keyword' => $keyword,
                'placeholder' => 'Tìm SKU, tên sản phẩm, nhà cung cấp...',
            ],
        ]);

        unset($_SESSION['_supplier_product_media_errors'], $_SESSION['_supplier_product_media_old']);
    }

    public function mediaShow(string $id): void
    {
        $model = new SupplierProduct();
        $mediaData = $model->mediaDataByProductId((int) $id);

        if ($mediaData === []) {
            http_response_code(404);
            $this->view('dashboard.not-found', ['title' => 'Không tìm thấy trang']);
            return;
        }

        $this->view('supplier_products.media', [
            'title' => 'Chỉnh sửa tài nguyên media',
            'selectedProduct' => $mediaData['product'],
            'mediaData' => $mediaData,
            'errors' => $_SESSION['_supplier_product_media_errors'] ?? [],
            'old' => $_SESSION['_supplier_product_media_old'] ?? [],
        ]);

        unset($_SESSION['_supplier_product_media_errors'], $_SESSION['_supplier_product_media_old']);
    }

    public function mediaUpdate(string $id): void
    {
        $this->requireEditor();
        $this->validateCsrf();
        $model = new SupplierProduct();
        $mediaData = $model->mediaDataByProductId((int) $id);

        if ($mediaData === []) {
            http_response_code(404);
            $this->view('dashboard.not-found', ['title' => 'Không tìm thấy trang']);
            return;
        }

        $payload = $this->validatedMediaPayload();

        if ($payload['errors'] !== []) {
            $_SESSION['_supplier_product_media_errors'] = $payload['errors'];
            $_SESSION['_supplier_product_media_old'] = $payload['old'];
            session_flash('error', 'Vui lòng kiểm tra lại tài nguyên media.');
            redirect('supplier-products/' . (int) $id . '/media');
        }

        try {
            $model->updateMediaByProductId((int) $id, $payload['data']);
            unset($_SESSION['_supplier_product_media_errors'], $_SESSION['_supplier_product_media_old']);
            session_flash('success', 'Đã cập nhật tài nguyên media.');
            redirect('supplier-products/' . (int) $id . '/media');
        } catch (Throwable $exception) {
            $this->logException('media_update', $exception, ['id' => (int) $id, 'payload' => $payload['data']]);
            $_SESSION['_supplier_product_media_old'] = $payload['old'];
            session_flash('error', 'Không thể cập nhật media lúc này. Vui lòng thử lại.');
            redirect('supplier-products/' . (int) $id . '/media');
        }
    }

    public function relationsIndex(): void
    {
        $model = new SupplierProduct();
        $page = (int) $this->request->input('page', 1);
        $keyword = trim((string) $this->request->input('keyword', ''));
        $pagination = $model->paginateRelationRows($page, 10, $keyword);

        $this->view('supplier_products.relations', [
            'title' => 'Quản lý Cross-sell / Up-sell',
            'rows' => $pagination['items'],
            'pagination' => $pagination,
            'keyword' => $keyword,
            'selectedProduct' => null,
            'relationData' => ['cross_sell' => [], 'up_sell' => []],
            'detailOptions' => $model->detailOptions(),
            'errors' => $_SESSION['_supplier_product_relation_errors'] ?? [],
            'old' => $_SESSION['_supplier_product_relation_old'] ?? [],
            'topbarFilters' => [
                'action' => base_url('supplier-products/relations'),
                'keyword' => $keyword,
                'placeholder' => 'Tìm SKU, tên sản phẩm, nhà cung cấp...',
            ],
        ]);

        unset($_SESSION['_supplier_product_relation_errors'], $_SESSION['_supplier_product_relation_old']);
    }

    public function relationsShow(string $id): void
    {
        $model = new SupplierProduct();
        $relationData = $model->relationDataByProductId((int) $id);

        if ($relationData === []) {
            http_response_code(404);
            $this->view('dashboard.not-found', ['title' => 'Không tìm thấy trang']);
            return;
        }

        $this->view('supplier_products.relations', [
            'title' => 'Chỉnh sửa Cross-sell / Up-sell',
            'selectedProduct' => $relationData['product'],
            'relationData' => $relationData,
            'detailOptions' => $model->detailOptions(),
            'errors' => $_SESSION['_supplier_product_relation_errors'] ?? [],
            'old' => $_SESSION['_supplier_product_relation_old'] ?? [],
        ]);

        unset($_SESSION['_supplier_product_relation_errors'], $_SESSION['_supplier_product_relation_old']);
    }

    public function relationsUpdate(string $id): void
    {
        $this->requireEditor();
        $this->validateCsrf();
        $model = new SupplierProduct();
        $relationData = $model->relationDataByProductId((int) $id);

        if ($relationData === []) {
            http_response_code(404);
            $this->view('dashboard.not-found', ['title' => 'Không tìm thấy trang']);
            return;
        }

        $payload = $this->validatedRelationPayload();

        if ($payload['errors'] !== []) {
            $_SESSION['_supplier_product_relation_errors'] = $payload['errors'];
            $_SESSION['_supplier_product_relation_old'] = $payload['old'];
            session_flash('error', 'Vui lòng kiểm tra lại các quan hệ sản phẩm.');
            redirect('supplier-products/' . (int) $id . '/relations');
        }

        try {
            $model->updateRelationsByProductId((int) $id, $payload['cross_sell'], $payload['up_sell']);
            unset($_SESSION['_supplier_product_relation_errors'], $_SESSION['_supplier_product_relation_old']);
            session_flash('success', 'Đã cập nhật quan hệ sản phẩm.');
            redirect('supplier-products/' . (int) $id . '/relations');
        } catch (Throwable $exception) {
            $this->logException('relations_update', $exception, ['id' => (int) $id, 'payload' => $payload]);
            $_SESSION['_supplier_product_relation_old'] = $payload['old'];
            session_flash('error', 'Không thể cập nhật quan hệ sản phẩm lúc này. Vui lòng thử lại.');
            redirect('supplier-products/' . (int) $id . '/relations');
        }
    }

    private function validatedPayload(?int $ignoreDetailId = null): array
    {
        $nccId = (int) $this->request->input('ncc_id', 0);
        $sanPhamIdRaw = trim((string) $this->request->input('san_pham_id', ''));
        $maSku = trim((string) $this->request->input('ma_sku', ''));
        $tenChiTiet = trim((string) $this->request->input('ten_chi_tiet', ''));
        $thuongHieu = trim((string) $this->request->input('thuong_hieu', ''));
        $hinhAnhSanPham = trim((string) $this->request->input('hinh_anh_san_pham', ''));
        $baoHanh = trim((string) $this->request->input('bao_hanh', ''));
        $moTaNgan = trim((string) $this->request->input('mo_ta_ngan', ''));
        $dacDiem = trim((string) $this->request->input('dac_diem', ''));
        $thongSoKyThuat = trim((string) $this->request->input('thong_so_ky_thuat', ''));
        $tinhNang = trim((string) $this->request->input('tinh_nang', ''));
        $giaiPhapLienQuan = trim((string) $this->request->input('giai_phap_lien_quan', ''));
        $duAnLienQuan = trim((string) $this->request->input('du_an_lien_quan', ''));
        $gia = trim((string) $this->request->input('gia', ''));
        $trangThai = trim((string) $this->request->input('trang_thai', ''));
        $tonKho = trim((string) $this->request->input('ton_kho', ''));
        $priceNote = trim((string) $this->request->input('price_note', ''));
        $contentItems = $this->normalizeContentItems($this->request->input('content_items', []));

        $errors = [];
        $model = new SupplierProduct();

        if ($ignoreDetailId === null && $maSku === '') {
            $maSku = $model->nextSkuCode();
        }

        if ($nccId <= 0) {
            $errors['ncc_id'] = 'Vui lòng chọn nhà cung cấp.';
        }

        if ($tenChiTiet === '') {
            $errors['ten_chi_tiet'] = 'Tên sản phẩm là bắt buộc.';
        }

        if ($maSku !== '' && $model->skuExists($maSku, $ignoreDetailId)) {
            $errors['ma_sku'] = 'Mã sản phẩm đã tồn tại.';
        }

        if ($gia === '' || !is_numeric($gia)) {
            $errors['gia'] = 'Giá sản phẩm phải là số hợp lệ.';
        }

        if ($trangThai === '' || !in_array($trangThai, ['dang_ban', 'ngung'], true)) {
            $errors['trang_thai'] = 'Vui lòng chọn trạng thái hợp lệ.';
        }

        if ($tonKho === '' || filter_var($tonKho, FILTER_VALIDATE_INT) === false || (int) $tonKho < 0) {
            $errors['ton_kho'] = 'Tồn kho phải là số nguyên không âm.';
        }

        $hinhAnhSanPham = $this->resolveUploadedProductImage($hinhAnhSanPham, $errors);

        $old = [
            'ncc_id' => $nccId > 0 ? (string) $nccId : '',
            'san_pham_id' => $sanPhamIdRaw,
            'ma_sku' => $maSku,
            'ten_chi_tiet' => $tenChiTiet,
            'thuong_hieu' => $thuongHieu,
            'hinh_anh_san_pham' => $hinhAnhSanPham,
            'bao_hanh' => $baoHanh,
            'mo_ta_ngan' => $moTaNgan,
            'dac_diem' => $dacDiem,
            'thong_so_ky_thuat' => $thongSoKyThuat,
            'tinh_nang' => $tinhNang,
            'giai_phap_lien_quan' => $giaiPhapLienQuan,
            'du_an_lien_quan' => $duAnLienQuan,
            'gia' => $gia,
            'trang_thai' => $trangThai,
            'ton_kho' => $tonKho,
            'price_note' => $priceNote,
            'content_items' => $contentItems,
        ];

        return [
            'errors' => $errors,
            'old' => $old,
            'data' => [
                'ncc_id' => $nccId,
                'san_pham_id' => $sanPhamIdRaw === '' ? null : (int) $sanPhamIdRaw,
                'ma_sku' => $maSku === '' ? null : $maSku,
                'ten_chi_tiet' => $tenChiTiet,
                'thuong_hieu' => $thuongHieu === '' ? null : $thuongHieu,
                'hinh_anh_san_pham' => $hinhAnhSanPham === '' ? null : $hinhAnhSanPham,
                'bao_hanh' => $baoHanh === '' ? null : $baoHanh,
                'mo_ta_ngan' => $moTaNgan === '' ? null : $moTaNgan,
                'dac_diem' => $dacDiem === '' ? null : $dacDiem,
                'thong_so_ky_thuat' => $thongSoKyThuat === '' ? null : $thongSoKyThuat,
                'tinh_nang' => $tinhNang === '' ? null : $tinhNang,
                'giai_phap_lien_quan' => $giaiPhapLienQuan === '' ? null : $giaiPhapLienQuan,
                'du_an_lien_quan' => $duAnLienQuan === '' ? null : $duAnLienQuan,
                'gia' => (float) $gia,
                'trang_thai' => $trangThai,
                'ton_kho' => (int) $tonKho,
                'price_note' => $priceNote === '' ? null : $priceNote,
                'content_items' => $contentItems,
            ],
        ];
    }

    private function validatedMediaPayload(): array
    {
        $mediaItems = (array) $this->request->input('media_items', []);
        $errors = [];
        $oldItems = [];
        $data = [];

        foreach ($mediaItems as $index => $item) {
            $mediaType = trim((string) ($item['media_type'] ?? ''));
            $existingUrl = trim((string) ($item['media_url'] ?? ''));
            $mappedType = match ($mediaType) {
                'image' => 'anh',
                'video' => 'video',
                'pdf' => 'pdf',
                default => null,
            };

            if ($mappedType === null) {
                continue;
            }

            $url = $existingUrl;
            $upload = $_FILES['media_uploads'] ?? null;
            if (is_array($upload) && isset($upload['error'][$index]) && (int) $upload['error'][$index] === UPLOAD_ERR_OK) {
                $stored = $this->storeUploadedMedia(
                    (string) ($upload['name'][$index] ?? ''),
                    (string) ($upload['tmp_name'][$index] ?? '')
                );

                if ($stored === null) {
                    $errors['media_items'] = 'Không thể tải tệp media lên hệ thống.';
                } else {
                    $url = $stored;
                }
            }

            $oldItems[] = [
                'media_type' => $mediaType,
                'media_url' => $url,
            ];

            if ($url === '') {
                continue;
            }

            $data[] = [
                'loai' => $mappedType,
                'url' => $url,
            ];
        }

        return [
            'errors' => $errors,
            'old' => ['media_items' => $oldItems],
            'data' => $data,
        ];
    }

    private function validatedRelationPayload(): array
    {
        $crossSell = $this->normalizeRelationItems($this->request->input('cross_sell_items', []));
        $upSell = $this->normalizeRelationItems($this->request->input('up_sell_items', []));

        return [
            'errors' => [],
            'old' => [
                'cross_sell_items' => $crossSell,
                'up_sell_items' => $upSell,
            ],
            'cross_sell' => $crossSell,
            'up_sell' => $upSell,
        ];
    }

    private function normalizeContentItems(mixed $items): array
    {
        $normalized = [];

        foreach ((array) $items as $item) {
            $contentType = trim((string) ($item['content_type'] ?? 'mo_ta'));
            $title = trim((string) ($item['title'] ?? ''));
            $body = trim((string) ($item['content_body'] ?? ''));

            if ($title === '' && $body === '') {
                continue;
            }

            $normalized[] = [
                'content_type' => $contentType,
                'title' => $title,
                'content_body' => $body,
            ];
        }

        return $normalized;
    }

    private function normalizeRelationItems(mixed $items): array
    {
        $normalized = [];

        foreach ((array) $items as $item) {
            $targetId = (int) ($item['target_chi_tiet_id'] ?? 0);
            $score = max(1, (int) ($item['relation_score'] ?? 1));
            $note = trim((string) ($item['note'] ?? ''));

            if ($targetId <= 0) {
                continue;
            }

            $normalized[] = [
                'target_chi_tiet_id' => $targetId,
                'relation_score' => $score,
                'note' => $note,
            ];
        }

        return $normalized;
    }

    private function storeUploadedMedia(string $originalName, string $temporaryPath): ?string
    {
        if ($temporaryPath === '' || !is_uploaded_file($temporaryPath)) {
            return null;
        }

        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'mp4', 'mov', 'avi', 'pdf'];

        if (!in_array($extension, $allowedExtensions, true)) {
            return null;
        }

        $directory = dirname(__DIR__, 2) . '/public/uploads/product-media';
        if (!is_dir($directory) && !mkdir($directory, 0777, true) && !is_dir($directory)) {
            return null;
        }

        $filename = date('YmdHis') . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
        $destination = $directory . '/' . $filename;

        if (!move_uploaded_file($temporaryPath, $destination)) {
            return null;
        }

        return 'public/uploads/product-media/' . $filename;
    }

    private function resolveUploadedProductImage(string $currentPath, array &$errors): string
    {
        $upload = $_FILES['hinh_anh_tai_len'] ?? null;

        if (!is_array($upload) || !isset($upload['error'])) {
            return $currentPath;
        }

        $errorCode = (int) $upload['error'];

        if ($errorCode === UPLOAD_ERR_NO_FILE) {
            return $currentPath;
        }

        if ($errorCode !== UPLOAD_ERR_OK) {
            $errors['hinh_anh_san_pham'] = 'Không thể tải hình ảnh sản phẩm lên hệ thống.';
            return $currentPath;
        }

        $storedPath = $this->storeUploadedProductImage(
            (string) ($upload['name'] ?? ''),
            (string) ($upload['tmp_name'] ?? '')
        );

        if ($storedPath === null) {
            $errors['hinh_anh_san_pham'] = 'Tệp hình ảnh không hợp lệ. Vui lòng chọn ảnh JPG, PNG, GIF hoặc WEBP.';
            return $currentPath;
        }

        return $storedPath;
    }

    private function storeUploadedProductImage(string $originalName, string $temporaryPath): ?string
    {
        if ($temporaryPath === '' || !is_uploaded_file($temporaryPath)) {
            return null;
        }

        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($extension, $allowedExtensions, true)) {
            return null;
        }

        $directory = dirname(__DIR__, 2) . '/public/uploads/product-images';
        if (!is_dir($directory) && !mkdir($directory, 0777, true) && !is_dir($directory)) {
            return null;
        }

        $filename = date('YmdHis') . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
        $destination = $directory . '/' . $filename;

        if (!move_uploaded_file($temporaryPath, $destination)) {
            return null;
        }

        return 'public/uploads/product-images/' . $filename;
    }

    private function logException(string $action, Throwable $exception, array $context = []): void
    {
        $path = dirname(__DIR__, 2) . '/storage/logs/supplier_product_errors.log';
        $entry = [
            'time' => date('Y-m-d H:i:s'),
            'action' => $action,
            'message' => $exception->getMessage(),
            'type' => get_class($exception),
            'context' => $context,
        ];

        file_put_contents(
            $path,
            json_encode($entry, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL,
            FILE_APPEND
        );
    }
}
