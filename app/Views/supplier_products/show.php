<?php
$errors = $errors ?? [];
$old = $old ?? [];
$formAction = (string) ($formAction ?? '');
$priceHistory = $priceHistory ?? [];

$fieldClass = static function (string $field, array $errors): string {
    return isset($errors[$field]) ? 'input-error' : '';
};

$isCreate = (bool) ($isCreate ?? false);
$product = $product ?? [];
$canManage = function_exists('is_editor') && is_editor();

$oldValues = [
    'ncc_id' => $old['ncc_id'] ?? ($product['ncc_id'] ?? ''),
    'san_pham_id' => $old['san_pham_id'] ?? ($product['san_pham_id'] ?? ''),
    'ma_sku' => $old['ma_sku'] ?? ($product['ma_sku'] ?? ''),
    'ten_chi_tiet' => $old['ten_chi_tiet'] ?? ($product['ten_san_pham_chi_tiet'] ?? ''),
    'thuong_hieu' => $old['thuong_hieu'] ?? ($product['thuong_hieu'] ?? ''),
    'hinh_anh_san_pham' => $old['hinh_anh_san_pham'] ?? ($product['hinh_anh_san_pham'] ?? ''),
    'bao_hanh' => $old['bao_hanh'] ?? ($product['bao_hanh'] ?? ''),
    'mo_ta_ngan' => $old['mo_ta_ngan'] ?? ($product['mo_ta_ngan'] ?? ''),
    'dac_diem' => $old['dac_diem'] ?? ($product['dac_diem'] ?? ''),
    'thong_so_ky_thuat' => $old['thong_so_ky_thuat'] ?? ($product['thong_so_ky_thuat'] ?? ''),
    'tinh_nang' => $old['tinh_nang'] ?? ($product['tinh_nang'] ?? ''),
    'giai_phap_lien_quan' => $old['giai_phap_lien_quan'] ?? ($product['giai_phap_lien_quan'] ?? ''),
    'du_an_lien_quan' => $old['du_an_lien_quan'] ?? ($product['du_an_lien_quan'] ?? ''),
    'gia' => $old['gia'] ?? ($product['gia'] ?? ''),
    'trang_thai' => $old['trang_thai'] ?? ($product['trang_thai_raw'] ?? ''),
    'ton_kho' => $old['ton_kho'] ?? ($product['ton_kho'] ?? ''),
    'price_note' => $old['price_note'] ?? '',
];

$deletePrompt = 'Bạn có chắc muốn xóa sản phẩm';
if (!empty($product['ten_san_pham_chi_tiet'])) {
    $deletePrompt .= ' "' . $product['ten_san_pham_chi_tiet'] . '"';
}
$deletePrompt .= ' không?';

$resolveAssetUrl = static function (?string $path): string {
    $path = trim((string) $path);

    if ($path === '') {
        return '';
    }

    if (preg_match('#^(https?:)?//#i', $path) === 1 || str_starts_with($path, 'data:')) {
        return $path;
    }

    return base_url(ltrim($path, '/'));
};

$productImageUrl = $resolveAssetUrl((string) $oldValues['hinh_anh_san_pham']);
$formatPrice = static function (mixed $value): string {
    if ($value === null || $value === '') {
        return 'Chưa cập nhật';
    }

    if (is_numeric($value)) {
        return number_format((float) $value, 0, ',', '.') . ' đ';
    }

    return (string) $value;
};
?>

<section class="card">
    <div class="stack section-head">
        <div>
            <h3 class="product-section-title"><?= $isCreate ? 'Thêm sản phẩm' : 'Thông tin sản phẩm' ?></h3>
            <?php if (!$isCreate): ?>
                <div class="muted product-section-subtitle"><?= e($product['ma_sku'] ?? '') ?> &middot; <?= e($product['ten_san_pham_chi_tiet'] ?? '') ?></div>
            <?php endif; ?>
        </div>
        <a class="btn btn-muted inline-action" href="<?= e(base_url('supplier-products')) ?>" <?= $isCreate ? 'data-clear-product-draft="1"' : '' ?>>Quay lại</a>
    </div>

    <form
        method="POST"
        action="<?= e($formAction) ?>"
        enctype="multipart/form-data"
        <?= $isCreate ? 'data-product-draft-form="1" data-product-draft-key="supplier-product-create-draft"' : '' ?>>
        <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">

        <div class="product-form-grid">
            <section class="nested-card product-form-card">
                <div class="product-form-card-head">
                    <h4>Thông tin hiển thị sản phẩm</h4>
                    <p>Giữ nguyên tên sản phẩm, mã sản phẩm và mã định danh hiện có, chỉ bổ sung dữ liệu hiển thị.</p>
                </div>
                <div class="product-form-fields">
                    <div class="product-form-field">
                        <label for="ten_chi_tiet">Tên sản phẩm</label>
                        <input id="ten_chi_tiet" class="<?= e($fieldClass('ten_chi_tiet', $errors ?? [])) ?>" type="text" name="ten_chi_tiet" value="<?= e((string) $oldValues['ten_chi_tiet']) ?>">
                        <?php if (isset($errors['ten_chi_tiet'])): ?><small class="field-error"><?= e($errors['ten_chi_tiet']) ?></small><?php endif; ?>
                    </div>
                    <div class="product-form-field">
                        <label for="ma_sku">Mã sản phẩm</label>
                        <input
                            id="ma_sku"
                            class="<?= e($fieldClass('ma_sku', $errors ?? [])) ?>"
                            type="text"
                            name="ma_sku"
                            value="<?= e((string) $oldValues['ma_sku']) ?>"
                            <?= $isCreate ? 'readonly' : '' ?>>
                        <?php if ($isCreate): ?><small class="form-hint">Mã sản phẩm được tạo tự động theo thứ tự danh sách.</small><?php endif; ?>
                        <?php if (isset($errors['ma_sku'])): ?><small class="field-error"><?= e($errors['ma_sku']) ?></small><?php endif; ?>
                    </div>
                    <div class="product-form-field">
                        <label for="hinh_anh_tai_len">Hình ảnh sản phẩm</label>
                        <input type="hidden" name="hinh_anh_san_pham" value="<?= e((string) $oldValues['hinh_anh_san_pham']) ?>">
                        <input id="hinh_anh_tai_len" type="file" name="hinh_anh_tai_len" accept="image/*">
                        <small class="form-hint">Chọn ảnh từ máy tính. Hệ thống giữ lại ảnh hiện tại nếu bạn không chọn ảnh mới.</small>
                        <?php if (isset($errors['hinh_anh_san_pham'])): ?><small class="field-error"><?= e($errors['hinh_anh_san_pham']) ?></small><?php endif; ?>
                    </div>
                    <div class="product-form-field">
                        <label for="bao_hanh">Bảo hành</label>
                        <input id="bao_hanh" type="text" name="bao_hanh" value="<?= e((string) $oldValues['bao_hanh']) ?>" placeholder="Ví dụ: 12 tháng">
                    </div>
                    <div class="product-form-field">
                        <label for="thuong_hieu">Thương hiệu</label>
                        <input id="thuong_hieu" type="text" name="thuong_hieu" value="<?= e((string) $oldValues['thuong_hieu']) ?>">
                    </div>
                    <div class="product-form-field">
                        <label for="san_pham_id">Nhóm sản phẩm NCC</label>
                        <select id="san_pham_id" class="<?= e($fieldClass('san_pham_id', $errors ?? [])) ?>" name="san_pham_id">
                            <option value="">Chọn sản phẩm NCC</option>
                            <?php foreach (($productOptions ?? []) as $productOption): ?>
                                <option
                                    value="<?= e((string) $productOption['id']) ?>"
                                    <?= (string) $oldValues['san_pham_id'] === (string) $productOption['id'] ? 'selected' : '' ?>>
                                    <?= e($productOption['ten_san_pham'] ?? '') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['san_pham_id'])): ?><small class="field-error"><?= e($errors['san_pham_id']) ?></small><?php endif; ?>
                    </div>
                    <div class="product-form-field product-form-field-full">
                        <label for="mo_ta_ngan">Mô tả sản phẩm</label>
                        <textarea id="mo_ta_ngan" name="mo_ta_ngan" rows="3" placeholder="Đoạn mô tả ngắn từ 2 - 3 dòng"><?= e((string) $oldValues['mo_ta_ngan']) ?></textarea>
                    </div>
                </div>
            </section>

            <section class="nested-card product-form-card">
                <div class="product-form-card-head">
                    <h4>Nhà cung cấp và giá bán</h4>
                    <p>Thiết lập nguồn cung, giá, trạng thái và tồn kho đang áp dụng.</p>
                </div>
                <div class="product-form-fields">
                    <div class="product-form-field">
                        <label for="ncc_id">Nhà cung cấp</label>
                        <select id="ncc_id" class="<?= e($fieldClass('ncc_id', $errors ?? [])) ?>" name="ncc_id">
                            <option value="">Chọn nhà cung cấp</option>
                            <?php foreach (($supplierOptions ?? []) as $supplierOption): ?>
                                <option
                                    value="<?= e((string) $supplierOption['id']) ?>"
                                    <?= (string) $oldValues['ncc_id'] === (string) $supplierOption['id'] ? 'selected' : '' ?>>
                                    <?= e(($supplierOption['ma_ncc'] ?? '') . ' - ' . ($supplierOption['ten_ncc'] ?? '')) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['ncc_id'])): ?><small class="field-error"><?= e($errors['ncc_id']) ?></small><?php endif; ?>
                    </div>
                    <div class="product-form-field">
                        <label for="gia">Giá sản phẩm</label>
                        <input id="gia" class="<?= e($fieldClass('gia', $errors ?? [])) ?>" type="text" name="gia" value="<?= e((string) $oldValues['gia']) ?>">
                        <?php if (isset($errors['gia'])): ?><small class="field-error"><?= e($errors['gia']) ?></small><?php endif; ?>
                    </div>
                    <div class="product-form-field">
                        <label for="trang_thai">Trạng thái</label>
                        <select id="trang_thai" class="<?= e($fieldClass('trang_thai', $errors ?? [])) ?>" name="trang_thai">
                            <option value="">Chọn trạng thái</option>
                            <option value="dang_ban" <?= (string) $oldValues['trang_thai'] === 'dang_ban' ? 'selected' : '' ?>>Đang bán</option>
                            <option value="ngung" <?= (string) $oldValues['trang_thai'] === 'ngung' ? 'selected' : '' ?>>Ngừng</option>
                        </select>
                        <?php if (isset($errors['trang_thai'])): ?><small class="field-error"><?= e($errors['trang_thai']) ?></small><?php endif; ?>
                    </div>
                    <div class="product-form-field">
                        <label for="ton_kho">Tồn kho</label>
                        <input id="ton_kho" class="<?= e($fieldClass('ton_kho', $errors ?? [])) ?>" type="text" name="ton_kho" value="<?= e((string) $oldValues['ton_kho']) ?>">
                        <?php if (isset($errors['ton_kho'])): ?><small class="field-error"><?= e($errors['ton_kho']) ?></small><?php endif; ?>
                    </div>
                    <div class="product-form-field product-form-field-full">
                        <label for="price_note">Ghi chú thay đổi giá</label>
                        <textarea id="price_note" name="price_note" rows="3"><?= e((string) $oldValues['price_note']) ?></textarea>
                    </div>
                </div>
            </section>

            <section class="nested-card product-form-card product-form-card-wide">
                <div class="product-form-card-head">
                    <h4>Nội dung sản phẩm</h4>
                    <p>Bổ sung thông tin chi tiết để hiển thị trong mục xem sản phẩm.</p>
                </div>
                <div class="product-form-fields">
                    <div class="product-form-field product-form-field-full">
                        <label for="dac_diem">Đặc điểm</label>
                        <textarea id="dac_diem" name="dac_diem" rows="4"><?= e((string) $oldValues['dac_diem']) ?></textarea>
                    </div>
                    <div class="product-form-field product-form-field-full">
                        <label for="thong_so_ky_thuat">Thông số kỹ thuật</label>
                        <textarea id="thong_so_ky_thuat" name="thong_so_ky_thuat" rows="4"><?= e((string) $oldValues['thong_so_ky_thuat']) ?></textarea>
                    </div>
                    <div class="product-form-field product-form-field-full">
                        <label for="tinh_nang">Tính năng</label>
                        <textarea id="tinh_nang" name="tinh_nang" rows="4"><?= e((string) $oldValues['tinh_nang']) ?></textarea>
                    </div>
                    <div class="product-form-field">
                        <label for="giai_phap_lien_quan">Giải pháp liên quan</label>
                        <input id="giai_phap_lien_quan" type="text" name="giai_phap_lien_quan" value="<?= e((string) $oldValues['giai_phap_lien_quan']) ?>" placeholder="URL hoặc đường dẫn liên kết Interlink">
                    </div>
                    <div class="product-form-field">
                        <label for="du_an_lien_quan">Dự án liên quan</label>
                        <input id="du_an_lien_quan" type="text" name="du_an_lien_quan" value="<?= e((string) $oldValues['du_an_lien_quan']) ?>" placeholder="URL hoặc đường dẫn liên kết Interlink">
                    </div>
                </div>
            </section>

            <?php if (!$isCreate): ?>
                <section class="nested-card product-form-card product-form-summary-card">
                    <div class="product-form-card-head">
                        <h4>Thông tin hiện tại</h4>
                        <p>Đối chiếu nhanh dữ liệu đang hiển thị trên hệ thống.</p>
                    </div>

                    <div class="product-view-grid">
                        <div class="product-view-image">
                            <?php if ($productImageUrl !== ''): ?>
                                <img src="<?= e($productImageUrl) ?>" alt="<?= e((string) $oldValues['ten_chi_tiet']) ?>">
                            <?php else: ?>
                                <div class="product-image-placeholder">Chưa có ảnh</div>
                            <?php endif; ?>
                        </div>

                        <div class="product-view-meta">
                            <div class="product-summary-grid">
                                <div class="product-summary-item">
                                    <span class="product-summary-label">Tên sản phẩm</span>
                                    <strong><?= e($product['ten_san_pham_chi_tiet'] ?? '') ?></strong>
                                </div>
                                <div class="product-summary-item">
                                    <span class="product-summary-label">Mã sản phẩm</span>
                                    <strong><?= e($product['ma_sku'] ?? '') ?></strong>
                                </div>
                                <div class="product-summary-item">
                                    <span class="product-summary-label">Giá sản phẩm</span>
                                    <strong><?= e($formatPrice($product['gia'] ?? null)) ?></strong>
                                </div>
                                <div class="product-summary-item">
                                    <span class="product-summary-label">Bảo hành</span>
                                    <strong><?= e(($product['bao_hanh'] ?? '') !== '' ? $product['bao_hanh'] : 'Chưa cập nhật') ?></strong>
                                </div>
                                <div class="product-summary-item">
                                    <span class="product-summary-label">Nhà cung cấp hiện tại</span>
                                    <strong><?= e($product['ten_ncc'] ?? '') ?></strong>
                                </div>
                                <div class="product-summary-item">
                                    <span class="product-summary-label">Nhóm sản phẩm NCC</span>
                                    <strong><?= e(($product['ten_san_pham_ncc'] ?? '') !== '' ? $product['ten_san_pham_ncc'] : 'Chưa cập nhật') ?></strong>
                                </div>
                                <div class="product-summary-item">
                                    <span class="product-summary-label">Thương hiệu hiện tại</span>
                                    <strong><?= e($product['thuong_hieu'] ?? '') ?></strong>
                                </div>
                                <div class="product-summary-item">
                                    <span class="product-summary-label">Ngày cập nhật</span>
                                    <strong><?= e($product['ngay_cap_nhat_hien_thi'] ?? '') ?></strong>
                                </div>
                                <div class="product-summary-item product-summary-item-action">
                                    <span class="product-summary-label">Tài nguyên media</span>
                                    <a class="btn btn-muted inline-action" href="<?= e(base_url('supplier-products/' . $product['id'] . '/media')) ?>">Quản lý trong tab riêng</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="product-detail-sections">
                        <div class="product-detail-block">
                            <h5>Mô tả sản phẩm</h5>
                            <p><?= nl2br(e(($product['mo_ta_ngan'] ?? '') !== '' ? $product['mo_ta_ngan'] : 'Chưa cập nhật')) ?></p>
                        </div>
                        <div class="product-detail-block">
                            <h5>Đặc điểm</h5>
                            <p><?= nl2br(e(($product['dac_diem'] ?? '') !== '' ? $product['dac_diem'] : 'Chưa cập nhật')) ?></p>
                        </div>
                        <div class="product-detail-block">
                            <h5>Thông số kỹ thuật</h5>
                            <p><?= nl2br(e(($product['thong_so_ky_thuat'] ?? '') !== '' ? $product['thong_so_ky_thuat'] : 'Chưa cập nhật')) ?></p>
                        </div>
                        <div class="product-detail-block">
                            <h5>Tính năng</h5>
                            <p><?= nl2br(e(($product['tinh_nang'] ?? '') !== '' ? $product['tinh_nang'] : 'Chưa cập nhật')) ?></p>
                        </div>
                        <div class="product-detail-block">
                            <h5>Giải pháp liên quan</h5>
                            <?php if (!empty($product['giai_phap_lien_quan'])): ?>
                                <a class="product-inline-link" href="<?= e($resolveAssetUrl((string) $product['giai_phap_lien_quan'])) ?>" target="_blank" rel="noreferrer"><?= e($product['giai_phap_lien_quan']) ?></a>
                            <?php else: ?>
                                <p>Chưa cập nhật</p>
                            <?php endif; ?>
                        </div>
                        <div class="product-detail-block">
                            <h5>Dự án liên quan</h5>
                            <?php if (!empty($product['du_an_lien_quan'])): ?>
                                <a class="product-inline-link" href="<?= e($resolveAssetUrl((string) $product['du_an_lien_quan'])) ?>" target="_blank" rel="noreferrer"><?= e($product['du_an_lien_quan']) ?></a>
                            <?php else: ?>
                                <p>Chưa cập nhật</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>
        </div>

        <div class="action-row product-action-row">
            <div class="action-group action-group-left">
                <button type="submit" class="inline-action"><?= $isCreate ? 'Thêm sản phẩm' : 'Cập nhật thông tin' ?></button>
            </div>
            <?php if (!$isCreate && $canManage): ?>
                <div class="action-group action-group-right">
                    <button type="button" class="btn btn-danger inline-action" data-modal-open="delete-product-modal">Xóa</button>
                </div>
            <?php endif; ?>
        </div>
    </form>
</section>

<?php if (!$isCreate): ?>
    <div class="modal-backdrop" id="delete-product-modal" hidden>
        <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="delete-product-modal-title">
            <h3 id="delete-product-modal-title">Xác nhận xóa</h3>
            <p><?= e($deletePrompt) ?></p>
            <div class="stack modal-actions">
                <button type="button" class="btn btn-muted inline-action" data-modal-close>Hủy</button>
                <form method="POST" action="<?= e(base_url('supplier-products/' . $product['id'] . '/delete')) ?>">
                    <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                    <button type="submit" class="inline-action">Xác nhận xóa</button>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (!$isCreate): ?>
    <section class="card product-history-card">
        <div class="stack section-head">
            <h3 class="product-section-title">Lịch sử thay đổi giá</h3>
        </div>

        <div class="desktop-list">
            <div class="table-wrap">
                <table class="price-history-table">
                    <thead>
                        <tr>
                            <th>Thời gian</th>
                            <th>Giá</th>
                            <th>Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($priceHistory)): ?>
                            <tr>
                                <td colspan="3" class="muted">Chưa có dữ liệu lịch sử giá.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($priceHistory as $history): ?>
                            <tr>
                                <td><?= e($history['thoi_gian_thay_doi'] ?? '') ?></td>
                                <td><?= e($formatPrice($history['gia'] ?? null)) ?></td>
                                <td><?= e(($history['ghi_chu'] ?? '') !== '' ? $history['ghi_chu'] : 'Không có') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mobile-list">
            <div class="mobile-card-list">
                <?php if (empty($priceHistory)): ?>
                    <div class="mobile-card">
                        <div class="muted">Chưa có dữ liệu lịch sử giá.</div>
                    </div>
                <?php endif; ?>

                <?php foreach ($priceHistory as $history): ?>
                    <article class="mobile-card">
                        <div class="mobile-card-head">
                            <div>
                                <h4 class="mobile-card-title"><?= e($formatPrice($history['gia'] ?? null)) ?></h4>
                                <div class="mobile-card-subtitle"><?= e($history['thoi_gian_thay_doi'] ?? '') ?></div>
                            </div>
                        </div>
                        <div class="mobile-detail-block">
                            <strong>Ghi chú</strong>
                            <?= e(($history['ghi_chu'] ?? '') !== '' ? $history['ghi_chu'] : 'Không có') ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>
