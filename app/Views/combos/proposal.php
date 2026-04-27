<?php
$proposal = $proposal ?? [];
$combo = $proposal['combo'] ?? [];
?>

<section class="supplier-page">
    <div class="supplier-page-head">
        <div>
            <h1 class="page-title">Proposal combo</h1>
        </div>
        <a class="btn btn-muted inline-action" href="<?= e(base_url('combos/' . ($combo['id'] ?? ''))) ?>">Quay lại combo</a>
    </div>

    <section class="card">
        <div class="grid cols-2 product-detail-grid">
            <div class="nested-card product-detail-card">
                <h3 style="margin-top: 0;"><?= e($proposal['title'] ?? 'Proposal') ?></h3>
                <?php if (!empty($proposal['customer_name']) || !empty($proposal['customer_channel'])): ?>
                <p class="muted">
                    <?= e($proposal['customer_name'] ?? 'Khách hàng') ?>
                    <?php if (!empty($proposal['customer_channel'])): ?>
                    · <?= e($proposal['customer_channel']) ?>
                    <?php endif; ?>
                </p>
                <?php endif; ?>
                <div style="white-space: pre-line; line-height: 1.7;"><?= e($proposal['body'] ?? '') ?></div>
            </div>

            <div class="nested-card product-detail-card">
                <table class="detail-info-table">
                    <tbody>
                        <tr>
                            <th>Mã combo</th>
                            <td><?= e($combo['ma_combo'] ?? '') ?></td>
                        </tr>
                        <tr>
                            <th>Tên combo</th>
                            <td><?= e($combo['ten_combo'] ?? '') ?></td>
                        </tr>
                        <tr>
                            <th>Giá lẻ</th>
                            <td><?= isset($combo['gia_le']) ? e(number_format((float) $combo['gia_le'], 0, ',', '.')) : '' ?></td>
                        </tr>
                        <tr>
                            <th>Giá combo</th>
                            <td><?= isset($combo['gia_combo']) ? e(number_format((float) $combo['gia_combo'], 0, ',', '.')) : '' ?></td>
                        </tr>
                        <tr>
                            <th>Tồn kho ảo</th>
                            <td><?= e((string) ($combo['ton_kho_ao'] ?? 0)) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="card" style="margin-top: 18px;">
        <div class="stack section-head">
            <h3 style="margin: 0;">Thành phần proposal</h3>
        </div>
        <div class="table-wrap">
            <table class="product-table product-table-modern">
                <thead>
                    <tr>
                        <th>Tên sản phẩm chi tiết</th>
                        <th>Số lượng</th>
                        <th>Giá tham chiếu</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (($combo['items'] ?? []) as $item): ?>
                    <tr>
                        <td><?= e($item['ten_chi_tiet'] ?? '') ?></td>
                        <td><?= e((string) ($item['so_luong'] ?? 0)) ?></td>
                        <td><?= e(number_format((float) ($item['gia'] ?? 0), 0, ',', '.')) ?></td>
                        <td><?= e(number_format((float) ($item['thanh_tien'] ?? 0), 0, ',', '.')) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</section>
