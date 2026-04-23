<?php
$fieldClass = static function (string $field, array $errors): string {
    return isset($errors[$field]) ? 'input-error' : '';
};

$deletePrompt = 'Bạn có chắc muốn xóa mục';
if (!empty($deleteName)) {
    $deletePrompt .= ' "' . $deleteName . '"';
}
$deletePrompt .= ' không?';
?>

<section class="card">
    <div class="stack section-head">
        <h3 style="margin: 0;"><?= e($heading ?? 'Cập nhật dữ liệu') ?></h3>
    </div>

    <form method="POST" action="<?= e($formAction) ?>">
        <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">

        <div class="grid cols-2">
            <div>
                <label for="table_name">Hệ sinh thái</label>
                <select id="table_name" class="<?= e($fieldClass('table_name', $errors ?? [])) ?>" name="table_name" <?= !empty($lockSystem) ? 'disabled' : '' ?>>
                    <option value="">Chọn hệ sinh thái</option>
                    <?php foreach ($systems as $system): ?>
                    <option value="<?= e($system['table']) ?>" <?= ($old['table_name'] ?? '') === $system['table'] ? 'selected' : '' ?>>
                        <?= e($system['group'] . ' - ' . $system['label']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <?php if (!empty($lockSystem)): ?>
                <input type="hidden" name="table_name" value="<?= e($old['table_name'] ?? '') ?>">
                <?php endif; ?>
                <?php if (isset($errors['table_name'])): ?><small class="field-error"><?= e($errors['table_name']) ?></small><?php endif; ?>
            </div>

            <div>
                <label for="chi_tiet">Chi tiết</label>
                <input id="chi_tiet" class="<?= e($fieldClass('chi_tiet', $errors ?? [])) ?>" type="text" name="chi_tiet" value="<?= e($old['chi_tiet'] ?? '') ?>">
                <?php if (isset($errors['chi_tiet'])): ?><small class="field-error"><?= e($errors['chi_tiet']) ?></small><?php endif; ?>
            </div>
        </div>

        <div class="grid cols-2">
            <div>
                <label for="hang_noi_bat">Hãng nổi bật</label>
                <input id="hang_noi_bat" type="text" name="hang_noi_bat" value="<?= e($old['hang_noi_bat'] ?? '') ?>">
            </div>

            <div>
                <label for="san_pham">Sản phẩm</label>
                <input id="san_pham" type="text" name="san_pham" value="<?= e($old['san_pham'] ?? '') ?>">
            </div>
        </div>

        <div>
            <label for="nha_phan_phoi">Nhà phân phối của hãng</label>
            <textarea id="nha_phan_phoi" name="nha_phan_phoi"><?= e($old['nha_phan_phoi'] ?? '') ?></textarea>
        </div>

        <div>
            <label for="ghi_chu">Ghi chú</label>
            <textarea id="ghi_chu" name="ghi_chu"><?= e($old['ghi_chu'] ?? '') ?></textarea>
        </div>

        <div class="action-row" style="margin-top: 16px;">
            <div class="action-group action-group-left">
                <button type="submit" class="inline-action"><?= e($submitLabel ?? 'Lưu dữ liệu') ?></button>
            </div>

            <div class="action-group action-group-right">
                <?php if (!empty($deleteAction)): ?>
                <button type="button" class="btn btn-muted inline-action" data-modal-open="delete-modal">Xóa</button>
                <?php endif; ?>
                <a class="btn btn-muted inline-action" href="<?= e($backUrl ?? base_url()) ?>">Quay lại</a>
            </div>
        </div>
    </form>
</section>

<?php if (!empty($deleteAction)): ?>
<div class="modal-backdrop" id="delete-modal" hidden>
    <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="delete-modal-title">
        <h3 id="delete-modal-title">Xác nhận xóa</h3>
        <p><?= e($deletePrompt) ?></p>
        <div class="stack modal-actions">
            <button type="button" class="btn btn-muted inline-action" data-modal-close>Hủy</button>
            <form method="POST" action="<?= e($deleteAction) ?>">
                <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                <button type="submit" class="inline-action">Xác nhận xóa</button>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
