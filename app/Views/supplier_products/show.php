<?php
$fieldClass = static function (string $field, array $errors): string {
    return isset($errors[$field]) ? 'input-error' : '';
};

$isCreate = (bool) ($isCreate ?? false);
$product = $product ?? [];
$hubData = $hubData ?? [
    'content' => [],
];

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

$contentItems = $old['content_items'] ?? ($hubData['content'] ?? []);

if (empty($contentItems)) {
    $contentItems = [['content_type' => 'mo_ta', 'title' => '', 'content_body' => '']];
}

$deletePrompt = 'Bạn có chắc muốn xóa sản phẩm';
if (!empty($product['ten_san_pham_chi_tiet'])) {
    $deletePrompt .= ' "' . $product['ten_san_pham_chi_tiet'] . '"';
}
$deletePrompt .= ' không?';
?>

<section class="card">
    <div class="stack section-head">
        <div>
            <h3 style="margin: 0;"><?= $isCreate ? 'Thêm sản phẩm' : 'Chi tiết sản phẩm' ?></h3>
            <?php if (!$isCreate): ?>
            <div class="muted" style="margin-top: 6px;"><?= e($product['ma_sku'] ?? '') ?> &middot; <?= e($product['ten_san_pham_chi_tiet'] ?? '') ?></div>
            <?php endif; ?>
        </div>
        <a class="btn btn-muted inline-action" href="<?= e(base_url('supplier-products')) ?>" <?= $isCreate ? 'data-clear-product-draft="1"' : '' ?>>Quay lại</a>
    </div>

    <form method="POST" action="<?= e($formAction) ?>" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">

        <div class="product-form-grid">
            <section class="nested-card product-form-card">
                <div class="product-form-card-head">
                    <h4>Thông tin cơ bản</h4>
                    <p>Nhập thông tin nhận diện của sản phẩm chi tiết.</p>
                </div>
                <div class="product-form-fields">
                    <div class="product-form-field">
                        <label for="ma_sku">Mã SKU</label>
                        <input id="ma_sku" class="<?= e($fieldClass('ma_sku', $errors ?? [])) ?>" type="text" name="ma_sku" value="<?= e((string) $oldValues['ma_sku']) ?>">
                        <?php if (isset($errors['ma_sku'])): ?><small class="field-error"><?= e($errors['ma_sku']) ?></small><?php endif; ?>
                    </div>
                    <div class="product-form-field">
                        <label for="ten_chi_tiet">Tên sản phẩm chi tiết</label>
                        <input id="ten_chi_tiet" class="<?= e($fieldClass('ten_chi_tiet', $errors ?? [])) ?>" type="text" name="ten_chi_tiet" value="<?= e((string) $oldValues['ten_chi_tiet']) ?>">
                        <?php if (isset($errors['ten_chi_tiet'])): ?><small class="field-error"><?= e($errors['ten_chi_tiet']) ?></small><?php endif; ?>
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
                </div>
            </section>

            <section class="nested-card product-form-card">
                <div class="product-form-card-head">
                    <h4>Nhà cung cấp và giá bán</h4>
                    <p>Thiết lập nguồn cung, giá, trạng thái và tồn kho.</p>
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
                        <label for="gia">Giá hiện tại</label>
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

            <?php if (!$isCreate): ?>
            <section class="nested-card product-form-card product-form-summary-card">
                <div class="product-form-card-head">
                    <h4>Thông tin hiện tại</h4>
                    <p>Đối chiếu nhanh dữ liệu đang hiển thị trên hệ thống.</p>
                </div>
                <div class="product-summary-grid">
                    <div class="product-summary-item">
                        <span class="product-summary-label">Mã NCC</span>
                        <strong><?= e($product['ma_ncc'] ?? '') ?></strong>
                    </div>
                    <div class="product-summary-item">
                        <span class="product-summary-label">Nhà cung cấp hiện tại</span>
                        <strong><?= e($product['ten_ncc'] ?? '') ?></strong>
                    </div>
                    <div class="product-summary-item">
                        <span class="product-summary-label">Nhóm sản phẩm NCC</span>
                        <strong><?= e($product['ten_san_pham_ncc'] ?? 'Chưa xác định') ?></strong>
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
                        <span class="product-summary-label">Cross / Up-sell</span>
                        <a class="btn btn-muted inline-action" href="<?= e(base_url('supplier-products/' . $product['id'] . '/relations')) ?>">Quản lý trong tab riêng</a>
                    </div>
                    <div class="product-summary-item product-summary-item-action">
                        <span class="product-summary-label">Tài nguyên media</span>
                        <a class="btn btn-muted inline-action" href="<?= e(base_url('supplier-products/' . $product['id'] . '/media')) ?>">Quản lý trong tab riêng</a>
                    </div>
                </div>
            </section>
            <?php endif; ?>
        </div>

        <div class="action-row" style="margin-top: 16px;">
            <div class="action-group action-group-left">
                <button type="submit" class="inline-action"><?= $isCreate ? 'Thêm sản phẩm' : 'Cập nhật thông tin' ?></button>
            </div>
            <?php if (!$isCreate): ?>
            <div class="action-group action-group-right">
                <button type="button" class="btn btn-muted inline-action" data-modal-open="delete-product-modal">Xóa</button>
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

<script>
document.addEventListener('DOMContentLoaded', () => {
    const isCreate = <?= $isCreate ? 'true' : 'false' ?>;

    if (!isCreate) {
        return;
    }

    const form = document.querySelector('form[action*="supplier-products/store"]');
    const draftKey = 'supplier-product-create-draft';

    if (!form) {
        return;
    }

    const baseFieldNames = [
        'ncc_id',
        'san_pham_id',
        'ma_sku',
        'ten_chi_tiet',
        'thuong_hieu',
        'gia',
        'trang_thai',
        'ton_kho',
        'price_note'
    ];

    const hasCurrentData = () => {
        const hasBaseData = baseFieldNames.some((name) => {
            const field = form.elements.namedItem(name);
            return field && String(field.value || '').trim() !== '';
        });

        return hasBaseData;
    };
    const saveDraft = () => {
        const draft = {
            fields: {}
        };
        baseFieldNames.forEach((name) => {
            const field = form.elements.namedItem(name);
            draft.fields[name] = field ? field.value : '';
        });

        sessionStorage.setItem(draftKey, JSON.stringify(draft));
    };

    const restoreDraft = () => {
        const rawDraft = sessionStorage.getItem(draftKey);
        if (!rawDraft || hasCurrentData()) {
            return;
        }

        try {
            const draft = JSON.parse(rawDraft);

            baseFieldNames.forEach((name) => {
                const field = form.elements.namedItem(name);
                if (field && draft.fields && typeof draft.fields[name] !== 'undefined') {
                    field.value = draft.fields[name];
                }
            });
        } catch (error) {
            sessionStorage.removeItem(draftKey);
        }
    };

    restoreDraft();

    form.addEventListener('input', saveDraft);
    form.addEventListener('change', saveDraft);
    form.addEventListener('submit', () => sessionStorage.removeItem(draftKey));

    document.querySelectorAll('[data-clear-product-draft]').forEach((link) => {
        link.addEventListener('click', () => {
            sessionStorage.removeItem(draftKey);
        });
    });
});
</script>




