<?php $canManage = function_exists('is_editor') && is_editor(); ?>

<section class="supplier-page">
    <div class="supplier-page-head">
        <div>
            <h1 class="page-title">Sales</h1>
        </div>
    </div>

    <section class="supplier-table-card">
        <div class="desktop-list">
            <div class="table-wrap">
                <table class="product-table product-table-modern combo-sales-table">
                    <colgroup>
                        <col style="width: 11%;">
                        <col style="width: 20%;">
                        <col style="width: 20%;">
                        <col style="width: 12%;">
                        <col style="width: 17%;">
                        <col style="width: 12%;">
                        <col style="width: 8%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Mã combo</th>
                            <th>Tên combo</th>
                            <th>Nhu cầu / phạm vi</th>
                            <th>Tồn kho</th>
                            <th>Cảnh báo</th>
                            <th>Gợi ý thay thế</th>
                            <th>Proposal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($rows)): ?>
                        <tr>
                            <td colspan="7" class="muted">Chưa có combo phù hợp với nhu cầu tra cứu.</td>
                        </tr>
                        <?php endif; ?>

                        <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><strong><?= e($row['ma_combo'] ?? '') ?></strong></td>
                            <td>
                                <div class="supplier-main-cell">
                                    <strong><?= e($row['ten_combo'] ?? '') ?></strong>
                                    <span class="supplier-code"><?= e($row['ten_chien_dich'] ?? 'Chưa gắn chiến dịch') ?></span>
                                </div>
                            </td>
                            <td>
                                <div><?= e($row['ten_san_pham'] ?? '') ?></div>
                                <?php if (!empty($row['mo_ta'])): ?>
                                <div class="supplier-code"><?= e($row['mo_ta']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?= e((string) ($row['ton_kho_ao'] ?? 0)) ?></strong>
                                <div class="supplier-code">Tiết kiệm: <?= e(number_format((float) ($row['tiet_kiem'] ?? 0), 0, ',', '.')) ?> đ</div>
                            </td>
                            <td>
                                <?php if (empty($row['canh_bao'])): ?>
                                <span class="muted">Khả dụng tốt</span>
                                <?php else: ?>
                                <?php foreach ($row['canh_bao'] as $warning): ?>
                                <div><strong><?= e($warning['ten_chi_tiet'] ?? '') ?></strong>: <?= e($warning['muc_canh_bao'] ?? '') ?></div>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (empty($row['goi_y'])): ?>
                                <span class="muted">Chưa có</span>
                                <?php else: ?>
                                <?php foreach ($row['goi_y'] as $suggestion): ?>
                                <div><?= e($suggestion['ten_chi_tiet'] ?? '') ?></div>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($canManage): ?>
                                <a class="btn btn-muted table-action-btn" href="<?= e(base_url('combos/' . $row['id'] . '/proposal')) ?>">Tạo proposal</a>
                                <?php else: ?>
                                <span class="muted">Chỉ xem</span>
                                <?php endif; ?>
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
                    <div class="muted">Chưa có combo phù hợp với nhu cầu tra cứu.</div>
                </div>
                <?php endif; ?>

                <?php foreach ($rows as $row): ?>
                <article class="mobile-card">
                    <div class="mobile-card-head">
                        <div>
                            <h4 class="mobile-card-title"><?= e($row['ma_combo'] ?? '') ?></h4>
                            <div class="mobile-card-subtitle"><?= e($row['ten_combo'] ?? '') ?></div>
                        </div>
                    </div>

                    <div class="mobile-card-grid">
                        <div class="mobile-field">
                            <span class="mobile-field-label">Tồn kho</span>
                            <span class="mobile-field-value"><?= e((string) ($row['ton_kho_ao'] ?? 0)) ?></span>
                        </div>
                        <div class="mobile-field">
                            <span class="mobile-field-label">Tiết kiệm</span>
                            <span class="mobile-field-value"><?= e(number_format((float) ($row['tiet_kiem'] ?? 0), 0, ',', '.')) ?> đ</span>
                        </div>
                    </div>

                    <div class="mobile-detail-block" style="margin-top: 12px;">
                        <strong>Nhu cầu / phạm vi</strong>
                        <div><?= e($row['ten_san_pham'] ?? '') ?></div>
                        <?php if (!empty($row['ten_chien_dich'])): ?>
                        <div class="supplier-code"><?= e($row['ten_chien_dich']) ?></div>
                        <?php endif; ?>
                    </div>

                    <?php if ($canManage): ?>
                    <div class="mobile-card-actions">
                        <a class="btn btn-muted" href="<?= e(base_url('combos/' . $row['id'] . '/proposal')) ?>">Tạo proposal</a>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($row['canh_bao'])): ?>
                    <div class="mobile-card-detail is-open">
                        <div class="mobile-detail-list">
                            <?php foreach ($row['canh_bao'] as $warning): ?>
                            <div class="mobile-detail-block">
                                <strong><?= e($warning['ten_chi_tiet'] ?? '') ?></strong>
                                <?= e($warning['muc_canh_bao'] ?? '') ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</section>
