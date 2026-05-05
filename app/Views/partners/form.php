<?php
$errors = $errors ?? [];
$old = $old ?? [];
$formAction = (string) ($formAction ?? '');
$fieldOptions = $fieldOptions ?? [];
$deleteName = (string) ($deleteName ?? '');
$deleteAction = (string) ($deleteAction ?? '');
$fieldClass = static function (string $field, array $errors): string {
    return isset($errors[$field]) ? 'input-error' : '';
};

$deletePrompt = 'Bạn có chắc muốn xóa đối tác';
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
                <label for="linh_vuc">Lĩnh vực</label>
                <select id="linh_vuc" class="<?= e($fieldClass('linh_vuc', $errors ?? [])) ?>" name="linh_vuc">
                    <option value="">Chọn lĩnh vực</option>
                    <?php foreach (($fieldOptions ?? []) as $option): ?>
                    <option value="<?= e($option) ?>" <?= ($old['linh_vuc'] ?? '') === $option ? 'selected' : '' ?>>
                        <?= e($option) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['linh_vuc'])): ?><small class="field-error"><?= e($errors['linh_vuc']) ?></small><?php endif; ?>
            </div>
            <div>
                <label for="doi_tac_tieu_bieu">Đối tác tiêu biểu</label>
                <input id="doi_tac_tieu_bieu" type="text" name="doi_tac_tieu_bieu" value="<?= e($old['doi_tac_tieu_bieu'] ?? '') ?>">
            </div>
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
                <a class="btn btn-muted inline-action" href="<?= e($backUrl ?? base_url('doi-tac')) ?>">Quay lại</a>
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
