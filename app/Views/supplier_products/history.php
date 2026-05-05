<?php
$rows = $rows ?? [];
$pagination = $pagination ?? ['page' => 1, 'last_page' => 1];
$keyword = (string) ($keyword ?? '');
?>

<section class="supplier-page">
    <div class="supplier-page-head">
        <div>
            <h1 class="page-title">Lịch sử thay đổi giá</h1>
        </div>
    </div>

    <section class="supplier-table-card">
        <div class="desktop-list">
            <div class="table-wrap">
                <table class="product-table product-table-modern product-price-history-table">
                    <thead>
                        <tr>
                            <th>Mã SKU</th>
                            <th>Tên sản phẩm chi tiết</th>
                            <th>Nhà cung cấp</th>
                            <th>Giá</th>
                            <th>Thời gian thay đổi</th>
                            <th>Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($rows)): ?>
                        <tr>
                            <td colspan="6" class="muted">Chưa có lịch sử thay đổi giá.</td>
                        </tr>
                        <?php endif; ?>

                        <?php foreach ($rows as $row): ?>
                        <tr class="supplier-row-main">
                            <td><strong><?= e($row['ma_sku'] ?? '') ?></strong></td>
                            <td><?= e($row['ten_san_pham_chi_tiet'] ?? '') ?></td>
                            <td>
                                <div class="supplier-main-cell">
                                    <strong><?= e($row['ten_ncc'] ?? '') ?></strong>
                                    <span class="supplier-code"><?= e($row['ma_ncc'] ?? '') ?></span>
                                </div>
                            </td>
                            <td><?= e($row['gia'] ?? '') ?></td>
                            <td><?= e($row['thoi_gian_thay_doi'] ?? '') ?></td>
                            <td><?= e($row['ghi_chu'] ?? '') ?></td>
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
                    <div class="muted">Chưa có lịch sử thay đổi giá.</div>
                </div>
                <?php endif; ?>

                <?php foreach ($rows as $row): ?>
                <article class="mobile-card">
                    <div class="mobile-card-head">
                        <div>
                            <h4 class="mobile-card-title"><?= e($row['ma_sku'] ?? '') ?></h4>
                            <div class="mobile-card-subtitle"><?= e($row['ten_san_pham_chi_tiet'] ?? '') ?></div>
                        </div>
                    </div>

                    <div class="mobile-card-grid">
                        <div class="mobile-field">
                            <span class="mobile-field-label">Nhà cung cấp</span>
                            <span class="mobile-field-value"><?= e($row['ten_ncc'] ?? '') ?></span>
                        </div>
                        <div class="mobile-field">
                            <span class="mobile-field-label">Giá</span>
                            <span class="mobile-field-value"><?= e($row['gia'] ?? '') ?></span>
                        </div>
                        <div class="mobile-field">
                            <span class="mobile-field-label">Thời gian</span>
                            <span class="mobile-field-value"><?= e($row['thoi_gian_thay_doi'] ?? '') ?></span>
                        </div>
                    </div>

                    <div class="mobile-card-detail is-open">
                        <div class="mobile-detail-list">
                            <div class="mobile-detail-block">
                                <strong>Mã NCC</strong>
                                <?= e($row['ma_ncc'] ?? '') ?>
                            </div>
                            <div class="mobile-detail-block">
                                <strong>Ghi chú</strong>
                                <?= e($row['ghi_chu'] ?? '') ?>
                            </div>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</section>
