<?php
$fieldClass = static function (string $field, array $errors): string {
    return isset($errors[$field]) ? 'input-error' : '';
};

$isCreate = (bool) ($isCreate ?? false);
$combo = $combo ?? [];

$oldValues = [
    'ten_combo' => $old['ten_combo'] ?? ($combo['ten_combo'] ?? ''),
    'mo_ta' => $old['mo_ta'] ?? ($combo['mo_ta'] ?? ''),
    'gia_combo' => $old['gia_combo'] ?? ($combo['gia_combo'] ?? ''),
    'trang_thai' => $old['trang_thai'] ?? ($combo['trang_thai'] ?? 'active'),
];

$itemValues = $old['items'] ?? ($combo['items'] ?? []);
if (empty($itemValues)) {
    $itemValues = [['chi_tiet_id' => '', 'so_luong' => 1]];
}

$deletePrompt = 'Bạn có chắc muốn xóa combo';
if (!empty($combo['ten_combo'])) {
    $deletePrompt .= ' "' . $combo['ten_combo'] . '"';
}
$deletePrompt .= ' không?';
?>

<section class="card">
    <div class="stack section-head">
        <div>
            <h3 style="margin: 0;"><?= $isCreate ? 'Thêm combo' : 'Chi tiết combo' ?></h3>
            <?php if (!$isCreate): ?>
            <div class="muted" style="margin-top: 6px;"><?= e($combo['ma_combo'] ?? '') ?> · <?= e($combo['ten_combo'] ?? '') ?></div>
            <?php endif; ?>
        </div>
        <a class="btn btn-muted inline-action" href="<?= e(base_url('combos')) ?>">Quay lại</a>
    </div>

    <form method="POST" action="<?= e($formAction) ?>">
        <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">

        <div class="grid cols-2 product-detail-grid">
            <div class="nested-card product-detail-card">
                <table class="detail-info-table">
                    <tbody>
                        <?php if (!$isCreate): ?>
                        <tr>
                            <th>Mã combo</th>
                            <td><?= e($combo['ma_combo'] ?? '') ?></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <th>Tên combo</th>
                            <td>
                                <input id="ten_combo" class="<?= e($fieldClass('ten_combo', $errors ?? [])) ?>" type="text" name="ten_combo" value="<?= e((string) $oldValues['ten_combo']) ?>">
                                <?php if (isset($errors['ten_combo'])): ?><small class="field-error"><?= e($errors['ten_combo']) ?></small><?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Mô tả</th>
                            <td>
                                <textarea id="mo_ta" name="mo_ta" rows="4"><?= e((string) $oldValues['mo_ta']) ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th>Giá combo</th>
                            <td>
                                <input id="gia_combo" class="<?= e($fieldClass('gia_combo', $errors ?? [])) ?>" type="text" name="gia_combo" value="<?= e((string) $oldValues['gia_combo']) ?>">
                                <?php if (isset($errors['gia_combo'])): ?><small class="field-error"><?= e($errors['gia_combo']) ?></small><?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Trạng thái</th>
                            <td>
                                <select id="trang_thai" name="trang_thai">
                                    <option value="active" <?= (string) $oldValues['trang_thai'] === 'active' ? 'selected' : '' ?>>Đang hoạt động</option>
                                    <option value="inactive" <?= (string) $oldValues['trang_thai'] === 'inactive' ? 'selected' : '' ?>>Ngừng</option>
                                </select>
                            </td>
                        </tr>
                        <?php if (!$isCreate): ?>
                        <tr>
                            <th>Giá lẻ</th>
                            <td><?= isset($combo['gia_le']) && $combo['gia_le'] !== null ? e(number_format((float) $combo['gia_le'], 0, ',', '.')) : '0' ?></td>
                        </tr>
                        <tr>
                            <th>Tồn kho combo</th>
                            <td><?= e((string) ($combo['ton_kho_ao'] ?? 0)) ?></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="nested-card product-detail-card">
                <table class="detail-info-table">
                    <tbody>
                        <tr>
                            <th>Tổng giá trị đề xuất</th>
                            <td>
                                <?php
                                $giaLe = (float) ($combo['gia_le'] ?? 0);
                                $giaCombo = (float) ($combo['gia_combo'] ?? 0);
                                $tietKiem = max(0, $giaLe - $giaCombo);
                                ?>
                                <div><strong><?= e(number_format($giaCombo, 0, ',', '.')) ?> đ</strong></div>
                                <div class="muted" style="margin-top: 6px;">Tiết kiệm tham chiếu: <?= e(number_format($tietKiem, 0, ',', '.')) ?> đ</div>
                            </td>
                        </tr>
                        <tr>
                            <th>Số chiến dịch</th>
                            <td>
                                <?= e((string) count($combo['campaigns'] ?? [])) ?>
                                <?php if (!$isCreate): ?>
                                <div style="margin-top: 8px;">
                                    <a class="btn btn-muted inline-action" href="<?= e(base_url('combos/campaigns')) ?>">Quản lý tại tab chiến dịch</a>
                                </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Proposal gần đây</th>
                            <td><?= e((string) count($combo['proposal_history'] ?? [])) ?></td>
                        </tr>
                        <tr>
                            <th>Gợi ý mở rộng</th>
                            <td><?= e((string) count($combo['goi_y_combo'] ?? [])) ?> sản phẩm</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <section class="card" style="margin-top: 18px;">
            <div class="stack section-head">
                <h3 style="margin: 0;">Thành phần combo</h3>
            </div>
            <?php if (isset($errors['items'])): ?><small class="field-error"><?= e($errors['items']) ?></small><?php endif; ?>
            <div id="combo-items" class="stack" style="flex-direction: column; align-items: stretch; gap: 10px;">
                <?php foreach ($itemValues as $index => $item): ?>
                <div class="combo-item-row" style="display: grid; grid-template-columns: minmax(0, 1fr) 110px 44px; gap: 10px;">
                    <select name="items[<?= e((string) $index) ?>][chi_tiet_id]">
                        <option value="">Chọn sản phẩm chi tiết</option>
                        <?php foreach (($productOptions ?? []) as $productOption): ?>
                        <option
                            value="<?= e((string) $productOption['id']) ?>"
                            <?= (string) ($item['chi_tiet_id'] ?? '') === (string) $productOption['id'] ? 'selected' : '' ?>>
                            <?= e(($productOption['ten_chi_tiet'] ?? '') . (($productOption['gia'] ?? null) !== null ? ' · ' . number_format((float) $productOption['gia'], 0, ',', '.') : '') . ' · Kho ' . ($productOption['ton_kho'] ?? 0)) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="number" min="1" name="items[<?= e((string) $index) ?>][so_luong]" value="<?= e((string) ($item['so_luong'] ?? 1)) ?>" placeholder="Số lượng">
                    <button type="button" class="btn btn-muted inline-action combo-remove-item" aria-label="Xóa dòng">-</button>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <div class="action-row" style="margin-top: 16px;">
            <div class="action-group action-group-left">
                <button type="submit" class="inline-action"><?= $isCreate ? 'Thêm combo' : 'Cập nhật thông tin' ?></button>
            </div>
            <?php if (!$isCreate): ?>
            <div class="action-group action-group-right">
                <a class="btn btn-muted inline-action" href="<?= e(base_url('combos')) ?>">Quay lại</a>
            </div>
            <?php endif; ?>
        </div>
    </form>
</section>

<?php if (!$isCreate): ?>
<div class="grid cols-2" style="margin-top: 18px;">
    <section class="card">
        <div class="stack section-head">
            <h3 style="margin: 0;">Proposal tự động</h3>
        </div>
        <p class="muted" style="margin-top: 0;">Nhập thông tin khách hàng để tạo proposal nhanh và lưu lại lịch sử đề xuất.</p>
        <form method="GET" action="<?= e(base_url('combos/' . $combo['id'] . '/proposal')) ?>" class="grid cols-2">
            <div>
                <label for="customer_name">Tên khách hàng</label>
                <input id="customer_name" type="text" name="customer_name">
            </div>
            <div>
                <label for="customer_channel">Kênh gửi</label>
                <input id="customer_channel" type="text" name="customer_channel" placeholder="Zalo / Email / Điện thoại">
            </div>
            <div style="grid-column: 1 / -1;">
                <button type="submit" class="inline-action">Tạo proposal</button>
            </div>
        </form>

        <?php if (!empty($combo['proposal_history'])): ?>
        <div class="stack" style="margin-top: 16px; gap: 10px; flex-direction: column;">
            <?php foreach ($combo['proposal_history'] as $proposal): ?>
            <div class="nested-card" style="padding: 12px;">
                <strong><?= e($proposal['proposal_title'] ?? '') ?></strong>
                <div class="muted" style="margin-top: 6px;">
                    <?= e($proposal['customer_name'] ?? 'Khách lẻ') ?> · <?= e($proposal['customer_channel'] ?? 'Chưa có kênh') ?> · <?= e($proposal['created_at'] ?? '') ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </section>

    <section class="card">
        <div class="stack section-head">
            <h3 style="margin: 0;">Gợi ý mở rộng combo</h3>
        </div>
        <?php if (empty($combo['goi_y_combo'])): ?>
        <div class="muted">Chưa có gợi ý từ dữ liệu cross-sell / up-sell.</div>
        <?php else: ?>
        <div class="stack" style="gap: 10px; flex-direction: column;">
            <?php foreach ($combo['goi_y_combo'] as $suggestion): ?>
            <div class="nested-card" style="padding: 12px;">
                <strong><?= e($suggestion['ten_chi_tiet'] ?? '') ?></strong>
                <div class="muted" style="margin-top: 6px;">
                    <?= ($suggestion['relation_type'] ?? '') === 'up_sell' ? 'Up-sell' : 'Cross-sell' ?> · Điểm <?= e((string) ($suggestion['tong_diem'] ?? 0)) ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <a class="btn btn-muted inline-action" href="<?= e(base_url('combos/analytics')) ?>" style="margin-top: 12px; width: auto;">Xem phân tích tổng hợp</a>
    </section>
</div>

<div class="modal-backdrop" id="delete-combo-modal" hidden>
    <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="delete-combo-modal-title">
        <h3 id="delete-combo-modal-title">Xác nhận xóa</h3>
        <p><?= e($deletePrompt) ?></p>
        <div class="stack modal-actions">
            <form method="POST" action="<?= e(base_url('combos/' . $combo['id'] . '/delete')) ?>">
                <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                <button type="submit" class="inline-action">Xác nhận xóa</button>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<template id="combo-item-template">
    <div class="combo-item-row" style="display: grid; grid-template-columns: minmax(0, 1fr) 110px 44px; gap: 10px;">
        <select data-name="chi_tiet_id">
            <option value="">Chọn sản phẩm chi tiết</option>
            <?php foreach (($productOptions ?? []) as $productOption): ?>
            <option value="<?= e((string) $productOption['id']) ?>">
                <?= e(($productOption['ten_chi_tiet'] ?? '') . (($productOption['gia'] ?? null) !== null ? ' · ' . number_format((float) $productOption['gia'], 0, ',', '.') : '') . ' · Kho ' . ($productOption['ton_kho'] ?? 0)) ?>
            </option>
            <?php endforeach; ?>
        </select>
        <input type="number" min="1" value="1" placeholder="Số lượng" data-name="so_luong">
        <button type="button" class="btn btn-muted inline-action combo-remove-item" aria-label="Xóa dòng">-</button>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('combo-items');
    const addButton = document.getElementById('combo-add-item');
    const template = document.getElementById('combo-item-template');

    if (!container || !addButton || !template) {
        return;
    }

    const syncNames = () => {
        container.querySelectorAll('.combo-item-row').forEach((row, index) => {
            row.querySelectorAll('[data-name]').forEach((field) => {
                field.name = `items[${index}][${field.getAttribute('data-name')}]`;
            });
        });
    };

    const ensureOneRow = () => {
        if (container.querySelectorAll('.combo-item-row').length === 0) {
            container.appendChild(template.content.firstElementChild.cloneNode(true));
            syncNames();
        }
    };

    addButton.addEventListener('click', () => {
        container.appendChild(template.content.firstElementChild.cloneNode(true));
        syncNames();
    });

    container.addEventListener('click', (event) => {
        const button = event.target.closest('.combo-remove-item');
        if (!button) {
            return;
        }

        const row = button.closest('.combo-item-row');
        if (row) {
            row.remove();
            ensureOneRow();
            syncNames();
        }
    });

    syncNames();
});
</script>
