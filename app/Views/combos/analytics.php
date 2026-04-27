<section class="supplier-page">
    <div class="supplier-page-head">
        <div>
            <h1 class="page-title">Phân tích combo</h1>
        </div>
        <a class="btn btn-muted inline-action" href="<?= e(base_url('combos')) ?>">Quay lại danh sách</a>
    </div>

    <section class="supplier-table-card">
        <div class="desktop-list">
            <div class="table-wrap">
                <table class="product-table product-table-modern">
                    <thead>
                        <tr>
                            <th>Mã combo</th>
                            <th>Tên combo</th>
                            <th>Số SP</th>
                            <th>Tồn kho ảo</th>
                            <th>Tiết kiệm</th>
                            <th>Giá trị tiềm năng</th>
                            <th>Chiến dịch</th>
                            <th>Chi tiết</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($rows)): ?>
                        <tr>
                            <td colspan="8" class="muted">Chưa có dữ liệu phân tích combo.</td>
                        </tr>
                        <?php endif; ?>

                        <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><strong><?= e($row['ma_combo'] ?? '') ?></strong></td>
                            <td><?= e($row['ten_combo'] ?? '') ?></td>
                            <td><?= e((string) ($row['so_san_pham'] ?? 0)) ?></td>
                            <td><?= e((string) ($row['ton_kho_ao'] ?? 0)) ?></td>
                            <td><?= e(number_format((float) ($row['tiet_kiem'] ?? 0), 0, ',', '.')) ?></td>
                            <td><?= e(number_format((float) ($row['gia_tri_tiem_nang'] ?? 0), 0, ',', '.')) ?></td>
                            <td><?= e((string) ($row['so_chien_dich'] ?? 0)) ?></td>
                            <td><a class="btn btn-muted table-action-btn" href="<?= e(base_url('combos/' . $row['id'])) ?>">Xem</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mobile-list">
            <div class="mobile-card-list">
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
                            <span class="mobile-field-label">Tồn kho ảo</span>
                            <span class="mobile-field-value"><?= e((string) ($row['ton_kho_ao'] ?? 0)) ?></span>
                        </div>
                        <div class="mobile-field">
                            <span class="mobile-field-label">Tiết kiệm</span>
                            <span class="mobile-field-value"><?= e(number_format((float) ($row['tiet_kiem'] ?? 0), 0, ',', '.')) ?></span>
                        </div>
                    </div>
                    <?php if (!empty($row['goi_y'])): ?>
                    <div class="mobile-detail-block" style="margin-top: 12px;">
                        <strong>Gợi ý thêm</strong>
                        <?php foreach ($row['goi_y'] as $suggestion): ?>
                        <div><?= e($suggestion['ten_chi_tiet'] ?? '') ?> · <?= e((string) ($suggestion['tong_diem'] ?? 0)) ?></div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</section>
