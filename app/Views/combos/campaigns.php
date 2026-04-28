<?php
$canManage = function_exists('is_editor') && is_editor();
$campaignOld = $campaignOld ?? [];
$campaignErrors = $campaignErrors ?? [];
$editingCampaign = $editingCampaign ?? null;
$isEditing = !empty($editingCampaign);

$formValues = [
    'combo_id' => $campaignOld['combo_id'] ?? '',
    'ten_chien_dich' => $campaignOld['ten_chien_dich'] ?? '',
    'ghi_chu' => $campaignOld['ghi_chu'] ?? '',
    'bat_dau' => $campaignOld['bat_dau'] ?? '',
    'ket_thuc' => $campaignOld['ket_thuc'] ?? '',
];
?>

<section class="supplier-page">
    <div class="supplier-page-head">
        <div>
            <h1 class="page-title">Chiến dịch marketing</h1>
        </div>
    </div>

    <?php if ($canManage): ?>
    <section class="card" style="margin-bottom: 18px;">
        <div class="stack section-head">
            <h3 style="margin: 0;"><?= $isEditing ? 'Cập nhật chiến dịch' : 'Thêm chiến dịch marketing' ?></h3>
        </div>

        <form method="POST" action="<?= e($isEditing ? base_url('combos/campaigns/' . $editingCampaign['id'] . '/update') : base_url('combos/campaigns/store')) ?>">
            <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">

            <div class="grid cols-2">
                <div>
                    <label for="combo_id">Combo</label>
                    <select id="combo_id" name="combo_id" class="<?= isset($campaignErrors['combo_id']) ? 'input-error' : '' ?>">
                        <option value="">Chọn combo</option>
                        <?php foreach (($comboOptions ?? []) as $comboOption): ?>
                        <option value="<?= e((string) $comboOption['id']) ?>" <?= (string) $formValues['combo_id'] === (string) $comboOption['id'] ? 'selected' : '' ?>>
                            <?= e(($comboOption['ma_combo'] ?? '') . ' - ' . ($comboOption['ten_combo'] ?? '')) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($campaignErrors['combo_id'])): ?><small class="field-error"><?= e($campaignErrors['combo_id']) ?></small><?php endif; ?>
                </div>

                <div>
                    <label for="ten_chien_dich">Tên chiến dịch</label>
                    <input id="ten_chien_dich" type="text" name="ten_chien_dich" value="<?= e((string) $formValues['ten_chien_dich']) ?>" class="<?= isset($campaignErrors['ten_chien_dich']) ? 'input-error' : '' ?>">
                    <?php if (isset($campaignErrors['ten_chien_dich'])): ?><small class="field-error"><?= e($campaignErrors['ten_chien_dich']) ?></small><?php endif; ?>
                </div>
            </div>

            <div class="grid cols-2">
                <div>
                    <label for="bat_dau">Bắt đầu</label>
                    <input id="bat_dau" type="date" name="bat_dau" value="<?= e((string) $formValues['bat_dau']) ?>">
                </div>

                <div>
                    <label for="ket_thuc">Kết thúc</label>
                    <input id="ket_thuc" type="date" name="ket_thuc" value="<?= e((string) $formValues['ket_thuc']) ?>" class="<?= isset($campaignErrors['ket_thuc']) ? 'input-error' : '' ?>">
                    <?php if (isset($campaignErrors['ket_thuc'])): ?><small class="field-error"><?= e($campaignErrors['ket_thuc']) ?></small><?php endif; ?>
                </div>
            </div>

            <div>
                <label for="ghi_chu">Ghi chú</label>
                <textarea id="ghi_chu" name="ghi_chu" rows="3"><?= e((string) $formValues['ghi_chu']) ?></textarea>
            </div>

            <div class="action-row" style="margin-top: 16px;">
                <div class="action-group action-group-left">
                    <button type="submit" class="inline-action"><?= $isEditing ? 'Cập nhật chiến dịch' : 'Thêm chiến dịch' ?></button>
                </div>
                <div class="action-group action-group-right">
                    <?php if ($isEditing): ?>
                    <a class="btn btn-muted inline-action" href="<?= e(base_url('combos/campaigns')) ?>">Hủy sửa</a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </section>
    <?php endif; ?>

    <section class="supplier-table-card">
        <div class="desktop-list">
            <div class="table-wrap">
                <table class="product-table product-table-modern">
                    <colgroup>
                        <col style="width: 24%;">
                        <col style="width: 26%;">
                        <col style="width: 12%;">
                        <col style="width: 12%;">
                        <col style="width: 16%;">
                        <col style="width: 10%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Tên chiến dịch</th>
                            <th>Combo</th>
                            <th>Bắt đầu</th>
                            <th>Kết thúc</th>
                            <th>Ghi chú</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($rows)): ?>
                        <tr>
                            <td colspan="6" class="muted">Chưa có chiến dịch marketing nào.</td>
                        </tr>
                        <?php endif; ?>
                        <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><strong><?= e($row['ten_chien_dich'] ?? '') ?></strong></td>
                            <td><?= e(($row['ma_combo'] ?? '') . ' - ' . ($row['ten_combo'] ?? '')) ?></td>
                            <td><?= e($row['bat_dau'] ?? '') ?></td>
                            <td><?= e($row['ket_thuc'] ?? '') ?></td>
                            <td><?= e($row['ghi_chu'] ?? '') ?></td>
                            <td>
                                <div class="stack" style="gap: 8px;">
                                    <a class="btn btn-muted table-action-btn" href="<?= e(base_url('combos/campaigns?edit=' . ($row['id'] ?? ''))) ?>">Sửa</a>
                                    <a class="btn btn-muted table-action-btn" href="<?= e(base_url('combos/' . ($row['combo_id'] ?? ''))) ?>">Xem combo</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mobile-list">
            <div class="mobile-card-list">
                <?php if (empty($rows)): ?>
                <div class="mobile-card">
                    <div class="muted">Chưa có chiến dịch marketing nào.</div>
                </div>
                <?php endif; ?>
                <?php foreach ($rows as $row): ?>
                <article class="mobile-card">
                    <div class="mobile-card-head">
                        <div>
                            <h4 class="mobile-card-title"><?= e($row['ten_chien_dich'] ?? '') ?></h4>
                            <div class="mobile-card-subtitle"><?= e(($row['ma_combo'] ?? '') . ' - ' . ($row['ten_combo'] ?? '')) ?></div>
                        </div>
                    </div>
                    <div class="mobile-card-grid">
                        <div class="mobile-field">
                            <span class="mobile-field-label">Bắt đầu</span>
                            <span class="mobile-field-value"><?= e($row['bat_dau'] ?? '') ?></span>
                        </div>
                        <div class="mobile-field">
                            <span class="mobile-field-label">Kết thúc</span>
                            <span class="mobile-field-value"><?= e($row['ket_thuc'] ?? '') ?></span>
                        </div>
                    </div>
                    <?php if (!empty($row['ghi_chu'])): ?>
                    <div class="mobile-detail-block" style="margin-top: 12px;">
                        <strong>Ghi chú</strong>
                        <?= e($row['ghi_chu']) ?>
                    </div>
                    <?php endif; ?>
                    <div class="mobile-card-actions">
                        <a class="btn btn-muted" href="<?= e(base_url('combos/campaigns?edit=' . ($row['id'] ?? ''))) ?>">Sửa</a>
                        <a class="btn btn-muted" href="<?= e(base_url('combos/' . ($row['combo_id'] ?? ''))) ?>">Xem combo</a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</section>
