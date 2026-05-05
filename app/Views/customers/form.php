<?php
$errors = $errors ?? [];
$old = $old ?? [];
$formAction = (string) ($formAction ?? '');
$categoryOptions = $categoryOptions ?? [];
$deleteName = (string) ($deleteName ?? '');
$deleteAction = (string) ($deleteAction ?? '');
$fieldClass = static function (string $field, array $errors): string {
    return isset($errors[$field]) ? 'input-error' : '';
};

$deletePrompt = 'Bạn có chắc muốn xóa khách hàng';
if (!empty($deleteName)) {
    $deletePrompt .= ' "' . $deleteName . '"';
}
$deletePrompt .= ' không?';
?>

<section class="card">
    <form method="POST" action="<?= e($formAction) ?>">
        <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">

        <div class="grid cols-2">
            <div>
                <label for="phan_loai">Phân loại</label>
                <select id="phan_loai" class="<?= e($fieldClass('phan_loai', $errors ?? [])) ?>" name="phan_loai">
                    <option value="">Chọn phân loại</option>
                    <?php foreach (($categoryOptions ?? []) as $option): ?>
                    <option value="<?= e($option) ?>" <?= ($old['phan_loai'] ?? '') === $option ? 'selected' : '' ?>>
                        <?= e($option) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['phan_loai'])): ?><small class="field-error"><?= e($errors['phan_loai']) ?></small><?php endif; ?>
            </div>
            <div>
                <label for="phan_loai_khach_hang">Phân loại khách hàng</label>
                <input id="phan_loai_khach_hang" type="text" name="phan_loai_khach_hang" value="<?= e($old['phan_loai_khach_hang'] ?? '') ?>">
            </div>
        </div>

        <div>
            <label for="khach_hang_tieu_bieu">Khách hàng tiêu biểu</label>
            <textarea id="khach_hang_tieu_bieu" name="khach_hang_tieu_bieu"><?= e($old['khach_hang_tieu_bieu'] ?? '') ?></textarea>
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
                <a class="btn btn-muted inline-action" href="<?= e($backUrl ?? base_url('khach-hang')) ?>">Quay lại</a>
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
            <form method="POST" action="<?= e($deleteAction) ?>">
                <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                <button type="submit" class="inline-action">Xác nhận xóa</button>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
