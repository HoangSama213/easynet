<?php
$fieldClass = static function (string $field, array $errors): string {
    return isset($errors[$field]) ? 'input-error' : '';
};

$deletePrompt = 'Bạn có chắc muốn xóa nhà cung cấp';
if (!empty($deleteName)) {
    $deletePrompt .= ' "' . $deleteName . '"';
}
$deletePrompt .= ' không?';
?>

<section class="card">
    <form method="POST" action="<?= e($formAction) ?>" class="form-grid" id="supplier-form">
        <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">

        <div class="grid cols-2">
            <div>
                <label for="ten_ncc">Tên nhà cung cấp</label>
                <input id="ten_ncc" class="<?= e($fieldClass('ten_ncc', $errors)) ?>" type="text" name="ten_ncc" value="<?= e($old['ten_ncc'] ?? '') ?>">
                <?php if (isset($errors['ten_ncc'])): ?><small class="field-error"><?= e($errors['ten_ncc']) ?></small><?php endif; ?>
            </div>
            <div>
                <label for="id_hang_muc">Hạng mục</label>
                <select id="id_hang_muc" class="<?= e($fieldClass('id_hang_muc', $errors)) ?>" name="id_hang_muc">
                    <option value="">Chọn hạng mục</option>
                    <?php foreach ($categories as $category): ?>
                    <option value="<?= e((string) $category['id']) ?>" <?= (string) ($old['id_hang_muc'] ?? '') === (string) $category['id'] ? 'selected' : '' ?>>
                        <?= e($category['ten_hang_muc']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['id_hang_muc'])): ?><small class="field-error"><?= e($errors['id_hang_muc']) ?></small><?php endif; ?>
            </div>
        </div>

        <div class="grid cols-2">
            <div>
                <label for="website">Website</label>
                <input id="website" class="<?= e($fieldClass('website', $errors)) ?>" type="text" name="website" value="<?= e($old['website'] ?? '') ?>">
                <?php if (isset($errors['website'])): ?><small class="field-error"><?= e($errors['website']) ?></small><?php endif; ?>
            </div>
            <div>
                <label for="nguoi_lien_he">Người liên hệ</label>
                <input id="nguoi_lien_he" class="<?= e($fieldClass('nguoi_lien_he', $errors)) ?>" type="text" name="nguoi_lien_he" value="<?= e($old['nguoi_lien_he'] ?? '') ?>">
                <?php if (isset($errors['nguoi_lien_he'])): ?><small class="field-error"><?= e($errors['nguoi_lien_he']) ?></small><?php endif; ?>
            </div>
        </div>

        <div>
            <label for="nhom_zalo">Group Zalo</label>
            <input id="nhom_zalo" class="<?= e($fieldClass('nhom_zalo', $errors)) ?>" type="text" name="nhom_zalo" value="<?= e($old['nhom_zalo'] ?? '') ?>">
            <?php if (isset($errors['nhom_zalo'])): ?><small class="field-error"><?= e($errors['nhom_zalo']) ?></small><?php endif; ?>
        </div>

        <div>
            <label for="san_pham_ncc">Sản phẩm NCC</label>
            <textarea id="san_pham_ncc" class="<?= e($fieldClass('san_pham_ncc', $errors)) ?>" name="san_pham_ncc"><?= e($old['san_pham_ncc'] ?? '') ?></textarea>
            <?php if (isset($errors['san_pham_ncc'])): ?><small class="field-error"><?= e($errors['san_pham_ncc']) ?></small><?php endif; ?>
        </div>

        <div>
            <label for="thuong_hieu_phan_phoi">Thương hiệu phân phối</label>
            <textarea id="thuong_hieu_phan_phoi" class="<?= e($fieldClass('thuong_hieu_phan_phoi', $errors)) ?>" name="thuong_hieu_phan_phoi"><?= e($old['thuong_hieu_phan_phoi'] ?? '') ?></textarea>
            <?php if (isset($errors['thuong_hieu_phan_phoi'])): ?><small class="field-error"><?= e($errors['thuong_hieu_phan_phoi']) ?></small><?php endif; ?>
        </div>

        <div>
            <label for="san_pham_chi_tiet">Sản phẩm chi tiết</label>
            <textarea id="san_pham_chi_tiet" class="<?= e($fieldClass('san_pham_chi_tiet', $errors)) ?>" name="san_pham_chi_tiet"><?= e($old['san_pham_chi_tiet'] ?? '') ?></textarea>
            <?php if (isset($errors['san_pham_chi_tiet'])): ?><small class="field-error"><?= e($errors['san_pham_chi_tiet']) ?></small><?php endif; ?>
        </div>

        <div>
            <label for="ghi_chu">Ghi chú</label>
            <textarea id="ghi_chu" name="ghi_chu"><?= e($old['ghi_chu'] ?? '') ?></textarea>
        </div>

        <div class="action-row">
            <div class="action-group action-group-left">
                <button type="submit" class="inline-action"><?= e($submitLabel ?? 'Lưu nhà cung cấp') ?></button>
            </div>

            <div class="action-group action-group-right">
                <a class="btn btn-muted inline-action" href="<?= e(base_url('items')) ?>">Quay lại</a>
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
