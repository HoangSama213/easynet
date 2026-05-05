<?php
$selectedProduct = $selectedProduct ?? null;
$crossSellRows = $crossSellRows ?? [];
$upSellRows = $upSellRows ?? [];
$canManage = function_exists('is_editor') && is_editor();
$selectedProduct = is_array($selectedProduct) ? $selectedProduct : [];
$selectedId = (int) ($selectedProduct['id'] ?? 0);
$defaultType = $defaultType ?? 'cross_sell';
$csrf = csrf_token();
$categoryId = (int) ($selectedProduct['danh_muc_id'] ?? 0);
$buildTabUrl = static function (string $path) use ($selectedId): string {
    if ($selectedId > 0) {
        return base_url($path . '?chi_tiet_id=' . $selectedId);
    }

    return base_url($path);
};
$formatMoney = static function (mixed $value): string {
    return number_format((float) $value, 0, ',', '.') . ' đ';
};
?>

<section
    class="relation-page"
    data-product-relations-page
    data-page-mode="manage"
    data-api-url="<?= e(base_url('quan-he-san-pham/api')) ?>"
    data-list-url="<?= e(base_url('quan-he-san-pham')) ?>"
    data-manage-url="<?= e(base_url('quan-he-san-pham/quan-ly')) ?>"
    data-selected-id="<?= e((string) $selectedId) ?>"
    data-category-id="<?= e((string) $categoryId) ?>"
    data-default-type="<?= e((string) $defaultType) ?>"
    data-csrf="<?= e($csrf) ?>">
    <header class="relation-page-head">
        <div>
            <h1 class="page-title">Quản lý quan hệ sản phẩm</h1>
            <p class="relation-page-subtitle">Thiết lập Cross-sell và Up-sell trực tiếp theo từng sản phẩm gốc.</p>
        </div>

        <nav class="relation-page-tabs" aria-label="Tab quan hệ sản phẩm">
            <a class="relation-tab" href="<?= e($buildTabUrl('quan-he-san-pham')) ?>">Danh sách</a>
            <?php if ($canManage): ?>
                <a class="relation-tab is-active" href="<?= e($buildTabUrl('quan-he-san-pham/quan-ly')) ?>">Quản lý</a>
            <?php endif; ?>
        </nav>
    </header>

    <section class="relation-card relation-step-card">
        <div class="relation-step-label">Bước 1</div>
        <div class="relation-section-head">
            <div>
                <h2>Chọn sản phẩm gốc</h2>
                <p>Tìm sản phẩm chi tiết theo tên hoặc SKU.</p>
            </div>
        </div>

        <div class="relation-search-shell" data-root-search-shell>
            <input
                id="relation-manage-search"
                class="relation-search-input relation-search-input-lg"
                type="search"
                placeholder="Tìm sản phẩm theo tên hoặc SKU..."
                autocomplete="off"
                data-root-search-input>
            <div class="relation-search-results" data-root-search-results hidden></div>
        </div>

        <?php if ($selectedProduct !== null): ?>
            <div class="relation-product-card relation-product-card-compact">
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
            </div>
        <?php endif; ?>
    </section>

    <?php if ($selectedProduct !== null): ?>
        <section class="relation-card relation-step-card">
            <div class="relation-step-label">Bước 2</div>
            <div class="relation-section-head">
                <div>
                    <h2>Chọn loại quan hệ</h2>
                    <p>Chọn dạng quan hệ cần cấu hình cho sản phẩm gốc.</p>
                </div>
            </div>

            <div class="relation-type-grid">
                <label class="relation-radio-card">
                    <input type="radio" name="relation_type" value="cross_sell" <?= $defaultType === 'cross_sell' ? 'checked' : '' ?>>
                    <span class="relation-radio-content">
                        <strong>Cross-sell</strong>
                        <span>Gợi ý sản phẩm mua kèm với giá ưu đãi</span>
                    </span>
                </label>

                <label class="relation-radio-card">
                    <input type="radio" name="relation_type" value="up_sell" <?= $defaultType === 'up_sell' ? 'checked' : '' ?>>
                    <span class="relation-radio-content">
                        <strong>Up-sell</strong>
                        <span>Gợi ý phiên bản cao cấp hơn</span>
                    </span>
                </label>
            </div>
        </section>

        <section class="relation-card relation-step-card" data-step-panel="up_sell" <?= $defaultType !== 'up_sell' ? 'hidden' : '' ?>>
            <div class="relation-step-label">Bước 3A</div>
            <div class="relation-section-head">
                <div>
                    <h2>Danh sách Up-sell</h2>
                    <p>Chỉ tìm trong cùng danh mục với sản phẩm gốc.</p>
                </div>
            </div>

            <div class="relation-toolbar">
                <div class="relation-search-shell relation-inline-search" data-add-search-shell="up_sell">
                    <input
                        class="relation-search-input"
                        type="search"
                        placeholder="Tìm sản phẩm nâng cấp theo tên hoặc SKU..."
                        autocomplete="off"
                        data-add-search-input="up_sell">
                    <div class="relation-search-results" data-add-search-results="up_sell" hidden></div>
                </div>
                <button type="button" class="btn relation-add-btn" data-trigger-search="up_sell">+ Thêm sản phẩm</button>
            </div>

            <div class="relation-table-wrap">
                <table class="relation-table">
                    <thead>
                        <tr>
                            <th>Tên sản phẩm nâng cấp</th>
                            <th>Giá hiện tại</th>
                            <th>Chênh lệch giá</th>
                            <th>Nhãn hiển thị</th>
                            <th>Kích hoạt khi còn hàng</th>
                            <th>Xóa</th>
                        </tr>
                    </thead>
                    <tbody id="up-sell-body">
                        <?php if ($upSellRows === []): ?>
                            <tr class="relation-empty-row">
                                <td colspan="6" class="relation-empty">Chưa có sản phẩm Up-sell.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($upSellRows as $row): ?>
                            <?php $diff = (float) ($row['chenh_lech_gia'] ?? 0); ?>
                            <tr data-relation-row="<?= e((string) $row['id']) ?>" data-type="up_sell">
                                <td>
                                    <strong><?= e($row['ten'] ?? '') ?></strong>
                                    <div class="relation-cell-sub"><?= e($row['sku'] ?? '') ?></div>
                                </td>
                                <td><?= e($formatMoney($row['gia_ban_le'] ?? 0)) ?></td>
                                <td class="<?= $diff > 0 ? 'is-positive' : ($diff < 0 ? 'is-negative' : '') ?>">
                                    <?= e($formatMoney($diff)) ?>
                                </td>
                                <td>
                                    <input
                                        class="relation-table-input"
                                        type="text"
                                        value="<?= e($row['note'] ?? '') ?>"
                                        data-update-note="<?= e((string) $row['id']) ?>">
                                </td>
                                <td>
                                    <label class="relation-switch <?= empty($row['co_the_kich_hoat']) ? 'is-disabled' : '' ?>">
                                        <input
                                            type="checkbox"
                                            <?= !empty($row['chi_kich_hoat_khi_con_hang']) ? 'checked' : '' ?>
                                            <?= empty($row['co_the_kich_hoat']) ? 'disabled' : '' ?>
                                            data-update-up-sell="<?= e((string) $row['id']) ?>">
                                        <span class="relation-switch-track"></span>
                                    </label>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger relation-delete-btn" data-delete-relation="<?= e((string) $row['id']) ?>">Xóa</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="relation-card relation-step-card" data-step-panel="cross_sell" <?= $defaultType !== 'cross_sell' ? 'hidden' : '' ?>>
            <div class="relation-step-label">Bước 3B</div>
            <div class="relation-section-head">
                <div>
                    <h2>Danh sách Cross-sell</h2>
                    <p>Tìm tất cả sản phẩm trong kho để cấu hình giá mua kèm.</p>
                </div>
            </div>

            <div class="relation-toolbar">
                <div class="relation-search-shell relation-inline-search" data-add-search-shell="cross_sell">
                    <input
                        class="relation-search-input"
                        type="search"
                        placeholder="Tìm sản phẩm mua kèm theo tên hoặc SKU..."
                        autocomplete="off"
                        data-add-search-input="cross_sell">
                    <div class="relation-search-results" data-add-search-results="cross_sell" hidden></div>
                </div>
                <button type="button" class="btn relation-add-btn" data-trigger-search="cross_sell">+ Thêm sản phẩm</button>
            </div>

            <div class="relation-table-wrap">
                <table class="relation-table">
                    <thead>
                        <tr>
                            <th>Tên sản phẩm mua kèm</th>
                            <th>Giá bán lẻ</th>
                            <th>Giá khi mua cùng</th>
                            <th>% Giảm giá</th>
                            <th>Số lượng tối đa</th>
                            <th>Xóa</th>
                        </tr>
                    </thead>
                    <tbody id="cross-sell-body">
                        <?php if ($crossSellRows === []): ?>
                            <tr class="relation-empty-row">
                                <td colspan="6" class="relation-empty">Chưa có sản phẩm Cross-sell.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($crossSellRows as $row): ?>
                            <tr data-relation-row="<?= e((string) $row['id']) ?>" data-type="cross_sell">
                                <td>
                                    <strong><?= e($row['ten'] ?? '') ?></strong>
                                    <div class="relation-cell-sub"><?= e($row['sku'] ?? '') ?></div>
                                </td>
                                <td data-retail-price="<?= e((string) ($row['gia_ban_le'] ?? 0)) ?>"><?= e($formatMoney($row['gia_ban_le'] ?? 0)) ?></td>
                                <td>
                                    <input
                                        class="relation-table-input"
                                        type="number"
                                        min="0"
                                        step="1000"
                                        value="<?= e($row['gia_mua_kem'] !== null ? (string) $row['gia_mua_kem'] : '') ?>"
                                        data-update-bundle-price="<?= e((string) $row['id']) ?>">
                                </td>
                                <td data-discount-cell>
                                    <?= $row['phan_tram_giam'] !== null ? e(number_format((float) $row['phan_tram_giam'], 2, ',', '.')) . '%' : '—' ?>
                                </td>
                                <td>
                                    <input
                                        class="relation-table-input"
                                        type="number"
                                        min="1"
                                        step="1"
                                        value="<?= e((string) ($row['so_luong_toi_da'] ?? 1)) ?>"
                                        data-update-max-qty="<?= e((string) $row['id']) ?>">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger relation-delete-btn" data-delete-relation="<?= e((string) $row['id']) ?>">Xóa</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    <?php endif; ?>
</section>
