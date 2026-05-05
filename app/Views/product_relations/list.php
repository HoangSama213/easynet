<?php
$selectedProduct = $selectedProduct ?? null;
$crossSellRows = $crossSellRows ?? [];
$upSellRows = $upSellRows ?? [];
$canManage = function_exists('is_editor') && is_editor();
$selectedProduct = is_array($selectedProduct) ? $selectedProduct : [];
$selectedId = (int) ($selectedProduct['id'] ?? 0);
$buildTabUrl = static function (string $path) use ($selectedId): string {
    if ($selectedId > 0) {
        return base_url($path . '?chi_tiet_id=' . $selectedId);
    }

    return base_url($path);
};
$formatMoney = static function (mixed $value): string {
    return number_format((float) $value, 0, ',', '.') . ' đ';
};
$formatDiffClass = static function (float $value): string {
    if ($value > 0) {
        return 'is-positive';
    }

    if ($value < 0) {
        return 'is-negative';
    }

    return '';
};
?>

<section
    class="relation-page"
    data-product-relations-page
    data-page-mode="list"
    data-api-url="<?= e(base_url('quan-he-san-pham/api')) ?>"
    data-list-url="<?= e(base_url('quan-he-san-pham')) ?>"
    data-manage-url="<?= e(base_url('quan-he-san-pham/quan-ly')) ?>"
    data-selected-id="<?= e((string) $selectedId) ?>">
    <header class="relation-page-head">
        <div>
            <h1 class="page-title">Quan hệ sản phẩm</h1>
            <p class="relation-page-subtitle">Theo dõi Cross-sell và Up-sell theo từng sản phẩm chi tiết.</p>
        </div>

        <nav class="relation-page-tabs" aria-label="Tab quan hệ sản phẩm">
            <a class="relation-tab is-active" href="<?= e($buildTabUrl('quan-he-san-pham')) ?>">Danh sách</a>
            <?php if ($canManage): ?>
                <a class="relation-tab" href="<?= e($buildTabUrl('quan-he-san-pham/quan-ly')) ?>">Quản lý</a>
            <?php endif; ?>
        </nav>
    </header>

    <section class="relation-card">
        <div class="relation-search-shell" data-root-search-shell>
            <label class="relation-search-label" for="relation-root-search">Sản phẩm gốc</label>
            <input
                id="relation-root-search"
                class="relation-search-input"
                type="search"
                placeholder="Tìm sản phẩm theo tên hoặc SKU..."
                autocomplete="off"
                data-root-search-input>
            <div class="relation-search-results" data-root-search-results hidden></div>
        </div>
    </section>

    <?php if ($selectedProduct !== null): ?>
        <section class="relation-product-card">
            <div class="relation-product-main">
                <div>
                    <div class="relation-product-eyebrow">Sản phẩm gốc</div>
                    <h2 class="relation-product-title"><?= e($selectedProduct['ten'] ?? '') ?></h2>
                    <div class="relation-product-meta"><?= e($selectedProduct['sku'] ?? '') ?></div>
                </div>
                <span class="relation-badge">Đã chọn</span>
            </div>

            <dl class="relation-product-grid">
                <div>
                    <dt>Danh mục</dt>
                    <dd><?= e($selectedProduct['danh_muc'] ?? 'Chưa phân loại') ?></dd>
                </div>
                <div>
                    <dt>Giá bán lẻ</dt>
                    <dd><?= e($formatMoney($selectedProduct['gia_ban_le'] ?? 0)) ?></dd>
                </div>
                <div>
                    <dt>Tồn kho</dt>
                    <dd><?= e((string) ($selectedProduct['ton_kho'] ?? 0)) ?></dd>
                </div>
            </dl>
        </section>

        <section class="relation-card">
            <div class="relation-section-head">
                <div>
                    <h2>Cross-sell</h2>
                    <p>Danh sách sản phẩm mua kèm với giá ưu đãi.</p>
                </div>
            </div>

            <div class="relation-table-wrap">
                <table class="relation-table">
                    <thead>
                        <tr>
                            <th>Tên sản phẩm</th>
                            <th>SKU</th>
                            <th>Giá bán lẻ</th>
                            <th>Giá khi mua cùng</th>
                            <th>% giảm giá</th>
                            <th>Số lượng tối đa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($crossSellRows === []): ?>
                            <tr>
                                <td colspan="6" class="relation-empty">Chưa có sản phẩm Cross-sell.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($crossSellRows as $row): ?>
                            <tr>
                                <td><?= e($row['ten'] ?? '') ?></td>
                                <td><strong><?= e($row['sku'] ?? '') ?></strong></td>
                                <td><?= e($formatMoney($row['gia_ban_le'] ?? 0)) ?></td>
                                <td><?= $row['gia_mua_kem'] !== null ? e($formatMoney($row['gia_mua_kem'])) : '—' ?></td>
                                <td><?= $row['phan_tram_giam'] !== null ? e(number_format((float) $row['phan_tram_giam'], 2, ',', '.')) . '%' : '—' ?></td>
                                <td><?= e((string) ($row['so_luong_toi_da'] ?? 1)) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="relation-card">
            <div class="relation-section-head">
                <div>
                    <h2>Up-sell</h2>
                    <p>Danh sách sản phẩm nâng cấp trong cùng danh mục.</p>
                </div>
            </div>

            <div class="relation-table-wrap">
                <table class="relation-table">
                    <thead>
                        <tr>
                            <th>Tên sản phẩm</th>
                            <th>SKU</th>
                            <th>Giá hiện tại</th>
                            <th>Chênh lệch giá</th>
                            <th>Nhãn hiển thị</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($upSellRows === []): ?>
                            <tr>
                                <td colspan="5" class="relation-empty">Chưa có sản phẩm Up-sell.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($upSellRows as $row): ?>
                            <?php $diff = (float) ($row['chenh_lech_gia'] ?? 0); ?>
                            <tr>
                                <td><?= e($row['ten'] ?? '') ?></td>
                                <td><strong><?= e($row['sku'] ?? '') ?></strong></td>
                                <td><?= e($formatMoney($row['gia_ban_le'] ?? 0)) ?></td>
                                <td class="<?= e($formatDiffClass($diff)) ?>"><?= e($formatMoney($diff)) ?></td>
                                <td><?= e($row['note'] ?? '') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    <?php else: ?>
        <section class="relation-empty-state">
            <h2>Chưa chọn sản phẩm gốc</h2>
            <p>Chọn một sản phẩm để xem các quan hệ Cross-sell và Up-sell đã thiết lập.</p>
        </section>
    <?php endif; ?>
</section>
