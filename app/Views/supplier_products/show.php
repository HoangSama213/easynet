<?php
$fieldClass = static function (string $field, array $errors): string {
    return isset($errors[$field]) ? 'input-error' : '';
};

$isCreate = (bool) ($isCreate ?? false);
$product = $product ?? [];

$oldValues = [
    'ncc_id' => $old['ncc_id'] ?? ($product['ncc_id'] ?? ''),
    'san_pham_id' => $old['san_pham_id'] ?? ($product['san_pham_id'] ?? ''),
    'ma_sku' => $old['ma_sku'] ?? ($product['ma_sku'] ?? ''),
    'ten_chi_tiet' => $old['ten_chi_tiet'] ?? ($product['ten_san_pham_chi_tiet'] ?? ''),
    'thuong_hieu' => $old['thuong_hieu'] ?? ($product['thuong_hieu'] ?? ''),
    'gia' => $old['gia'] ?? ($product['gia'] ?? ''),
    'trang_thai' => $old['trang_thai'] ?? ($product['trang_thai_raw'] ?? ''),
    'ton_kho' => $old['ton_kho'] ?? ($product['ton_kho'] ?? ''),
    'price_note' => $old['price_note'] ?? '',
];
?>

<section class="card">
    <div class="stack section-head">
        <div>
            <h3 style="margin: 0;"><?= $isCreate ? 'Thêm sản phẩm' : 'Chi tiết sản phẩm' ?></h3>
            <?php if (!$isCreate): ?>
            <div class="muted" style="margin-top: 6px;"><?= e($product['ten_san_pham_chi_tiet'] ?? '') ?></div>
            <?php endif; ?>
        </div>
        <a class="btn btn-muted inline-action" href="<?= e(base_url('supplier-products')) ?>">Quay lại</a>
    </div>

    <form method="POST" action="<?= e($formAction) ?>">
        <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">

        <div class="grid cols-2 product-detail-grid">
            <div class="nested-card product-detail-card">
                <table class="detail-info-table">
                    <tbody>
                        <tr>
                            <th>Nhà cung cấp</th>
                            <td>
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
                            </td>
                        </tr>
                        <tr>
                            <th>Sản phẩm NCC</th>
                            <td>
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
                            </td>
                        </tr>
                        <tr>
                            <th>Mã SKU</th>
                            <td>
                                <input id="ma_sku" class="<?= e($fieldClass('ma_sku', $errors ?? [])) ?>" type="text" name="ma_sku" value="<?= e((string) $oldValues['ma_sku']) ?>">
                                <?php if (isset($errors['ma_sku'])): ?><small class="field-error"><?= e($errors['ma_sku']) ?></small><?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Tên sản phẩm chi tiết</th>
                            <td>
                                <input id="ten_chi_tiet" class="<?= e($fieldClass('ten_chi_tiet', $errors ?? [])) ?>" type="text" name="ten_chi_tiet" value="<?= e((string) $oldValues['ten_chi_tiet']) ?>">
                                <?php if (isset($errors['ten_chi_tiet'])): ?><small class="field-error"><?= e($errors['ten_chi_tiet']) ?></small><?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Thương hiệu</th>
                            <td>
                                <input id="thuong_hieu" type="text" name="thuong_hieu" value="<?= e((string) $oldValues['thuong_hieu']) ?>">
                            </td>
                        </tr>
                        <tr>
                            <th>Giá hiện tại</th>
                            <td>
                                <input id="gia" class="<?= e($fieldClass('gia', $errors ?? [])) ?>" type="text" name="gia" value="<?= e((string) $oldValues['gia']) ?>">
                                <?php if (isset($errors['gia'])): ?><small class="field-error"><?= e($errors['gia']) ?></small><?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Ghi chú thay đổi giá</th>
                            <td>
                                <textarea id="price_note" name="price_note" rows="3"><?= e((string) $oldValues['price_note']) ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th>Trạng thái</th>
                            <td>
                                <select id="trang_thai" class="<?= e($fieldClass('trang_thai', $errors ?? [])) ?>" name="trang_thai">
                                    <option value="">Chọn trạng thái</option>
                                    <option value="dang_ban" <?= (string) $oldValues['trang_thai'] === 'dang_ban' ? 'selected' : '' ?>>Đang bán</option>
                                    <option value="ngung" <?= (string) $oldValues['trang_thai'] === 'ngung' ? 'selected' : '' ?>>Ngừng</option>
                                </select>
                                <?php if (isset($errors['trang_thai'])): ?><small class="field-error"><?= e($errors['trang_thai']) ?></small><?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Tồn kho</th>
                            <td>
                                <input id="ton_kho" class="<?= e($fieldClass('ton_kho', $errors ?? [])) ?>" type="text" name="ton_kho" value="<?= e((string) $oldValues['ton_kho']) ?>">
                                <?php if (isset($errors['ton_kho'])): ?><small class="field-error"><?= e($errors['ton_kho']) ?></small><?php endif; ?>
                            </td>
                        </tr>
                        <?php if (!$isCreate): ?>
                        <tr>
                            <th>Ngày cập nhật</th>
                            <td><?= e($product['ngay_cap_nhat_hien_thi'] ?? '') ?></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if (!$isCreate): ?>
            <div class="nested-card product-detail-card">
                <table class="detail-info-table">
                    <tbody>
                        <tr>
                            <th>Mã NCC</th>
                            <td><?= e($product['ma_ncc'] ?? '') ?></td>
                        </tr>
                        <tr>
                            <th>Nhà cung cấp hiện tại</th>
                            <td><?= e($product['ten_ncc'] ?? '') ?></td>
                        </tr>
                        <tr>
                            <th>Sản phẩm NCC hiện tại</th>
                            <td><?= e($product['ten_san_pham_ncc'] ?? '') ?></td>
                        </tr>
                        <tr>
                            <th>Thương hiệu hiện tại</th>
                            <td><?= e($product['thuong_hieu'] ?? '') ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>

        <div class="action-row" style="margin-top: 16px;">
            <div class="action-group action-group-left">
                <button type="submit" class="inline-action"><?= $isCreate ? 'Thêm sản phẩm' : 'Cập nhật thông tin' ?></button>
            </div>
        </div>
    </form>
</section>

<?php if (!$isCreate): ?>
<section class="card" style="margin-top: 18px;">
    <div class="stack section-head">
        <h3 style="margin: 0;">Lịch sử thay đổi giá</h3>
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
                        <td><?= e($history['gia'] ?? '') ?></td>
                        <td><?= e($history['ghi_chu'] ?? 'Không có') ?></td>
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
                        <h4 class="mobile-card-title"><?= e($history['gia'] ?? '') ?></h4>
                        <div class="mobile-card-subtitle"><?= e($history['thoi_gian_thay_doi'] ?? '') ?></div>
                    </div>
                </div>
                <div class="mobile-detail-block" style="margin-top: 12px;">
                    <strong>Ghi chú</strong>
                    <?= e($history['ghi_chu'] ?? 'Không có') ?>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
