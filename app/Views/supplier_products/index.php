<?php
$canManage = function_exists('is_editor') && is_editor();
$rows = $rows ?? [];
$pagination = $pagination ?? ['page' => 1, 'last_page' => 1];
$keyword = (string) ($keyword ?? '');

$resolveAssetUrl = static function (?string $path): string {
    $path = trim((string) $path);

    if ($path === '') {
        return '';
    }

    if (preg_match('#^(https?:)?//#i', $path) === 1 || str_starts_with($path, 'data:')) {
        return $path;
    }

    return base_url(ltrim($path, '/'));
};

$formatPrice = static function (mixed $value): string {
    if ($value === null || $value === '') {
        return 'Chưa cập nhật';
    }

    if (is_numeric($value)) {
        return number_format((float) $value, 0, ',', '.') . ' đ';
    }

    return (string) $value;
};

$shortDescription = static function (?string $value): string {
    $value = trim((string) $value);

    return $value === '' ? 'Chưa cập nhật mô tả sản phẩm.' : $value;
};
?>

<section class="supplier-page">
    <div class="supplier-page-head">
        <div>
            <h1 class="page-title">Danh sách sản phẩm</h1>
        </div>
        <?php if ($canManage): ?>
            <a class="btn btn-icon supplier-add-btn" href="<?= e(base_url('supplier-products/create')) ?>" aria-label="Thêm sản phẩm">+</a>
        <?php endif; ?>
    </div>

    <section class="supplier-table-card">
        <div class="desktop-list">
            <div class="table-wrap">
                <table class="product-table product-table-modern product-display-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Hình ảnh sản phẩm</th>
                            <th>Tên sản phẩm</th>
                            <th>Mã sản phẩm</th>
                            <th>Giá sản phẩm</th>
                            <th>Bảo hành</th>
                            <th>Mô tả sản phẩm</th>
                            <th>Chi tiết</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($rows)): ?>
                            <tr>
                                <td colspan="8" class="muted">Chưa có dữ liệu sản phẩm.</td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($rows as $index => $row): ?>
                            <?php $imageUrl = $resolveAssetUrl((string) ($row['hinh_anh_san_pham'] ?? '')); ?>
                            <tr class="supplier-row-main">
                                <td>
                                    <button
                                        type="button"
                                        class="toggle-btn collapse-toggle"
                                        data-target="product-row-<?= e((string) $index) ?>"
                                        data-collapse-group="product-desktop"
                                        aria-expanded="false"
                                        aria-label="Mở chi tiết">
                                        <span class="collapse-icon">
                                            <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                                        </span>
                                    </button>
                                </td>
                                <td>
                                    <div class="product-thumb">
                                        <?php if ($imageUrl !== ''): ?>
                                            <img src="<?= e($imageUrl) ?>" alt="<?= e($row['ten_san_pham_chi_tiet'] ?? '') ?>">
                                        <?php else: ?>
                                            <div class="product-thumb-placeholder">Không có ảnh</div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="product-name-cell">
                                        <strong><?= e($row['ten_san_pham_chi_tiet'] ?? '') ?></strong>
                                    </div>
                                </td>
                                <td><strong><?= e($row['ma_sku'] ?? '') ?></strong></td>
                                <td><?= e($formatPrice($row['gia'] ?? null)) ?></td>
                                <td><?= e(($row['bao_hanh'] ?? '') !== '' ? $row['bao_hanh'] : 'Chưa cập nhật') ?></td>
                                <td>
                                    <p class="product-short-desc"><?= e($shortDescription($row['mo_ta_ngan'] ?? '')) ?></p>
                                </td>
                                <td>
                                    <a class="btn btn-muted table-action-btn" href="<?= e(base_url('supplier-products/' . $row['id'])) ?>">Xem</a>
                                </td>
                            </tr>
                            <tr id="product-row-<?= e((string) $index) ?>" class="detail-row" data-collapse-item="product-desktop" hidden>
                                <td colspan="8">
                                    <div class="nested-card product-nested-card list-accordion-card">
                                        <div class="list-accordion-grid product-extra-grid list-accordion-head">
                                            <div>Nhà cung cấp</div>
                                            <div>Nhóm sản phẩm NCC</div>
                                            <div>Thương hiệu</div>
                                            <div>Tồn kho</div>
                                            <div>Ngày cập nhật</div>
                                        </div>
                                        <div class="list-accordion-grid product-extra-grid list-accordion-body">
                                            <div><?= e($row['ten_ncc'] ?? '') ?></div>
                                            <div><?= e(($row['ten_san_pham_ncc'] ?? '') !== '' ? $row['ten_san_pham_ncc'] : 'Chưa cập nhật') ?></div>
                                            <div><?= e(($row['thuong_hieu'] ?? '') !== '' ? $row['thuong_hieu'] : 'Chưa cập nhật') ?></div>
                                            <div><?= e((string) ($row['ton_kho'] ?? '')) ?></div>
                                            <div><?= e($row['ngay_cap_nhat'] ?? '') ?></div>
                                        </div>
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
                        <div class="muted">Chưa có dữ liệu sản phẩm.</div>
                    </div>
                <?php endif; ?>

                <?php foreach ($rows as $index => $row): ?>
                    <?php $imageUrl = $resolveAssetUrl((string) ($row['hinh_anh_san_pham'] ?? '')); ?>
                    <article class="mobile-card">
                        <div class="mobile-product-head">
                            <div class="mobile-product-thumb">
                                <?php if ($imageUrl !== ''): ?>
                                    <img src="<?= e($imageUrl) ?>" alt="<?= e($row['ten_san_pham_chi_tiet'] ?? '') ?>">
                                <?php else: ?>
                                    <div class="product-thumb-placeholder">Không có ảnh</div>
                                <?php endif; ?>
                            </div>

                            <div class="mobile-product-summary">
                                <h4 class="mobile-card-title"><?= e($row['ten_san_pham_chi_tiet'] ?? '') ?></h4>
                                <div class="mobile-card-subtitle"><?= e($row['ma_sku'] ?? '') ?></div>
                                <div class="mobile-product-price"><?= e($formatPrice($row['gia'] ?? null)) ?></div>
                            </div>

                            <button
                                type="button"
                                class="toggle-btn collapse-toggle"
                                data-target="product-mobile-<?= e((string) $index) ?>"
                                data-collapse-group="product-mobile"
                                aria-expanded="false"
                                aria-label="Mở chi tiết">
                                <span class="collapse-icon">
                                    <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                                </span>
                            </button>
                        </div>

                        <div class="mobile-card-grid">
                            <div class="mobile-field">
                                <span class="mobile-field-label">Bảo hành</span>
                                <span class="mobile-field-value"><?= e(($row['bao_hanh'] ?? '') !== '' ? $row['bao_hanh'] : 'Chưa cập nhật') ?></span>
                            </div>
                            <div class="mobile-field">
                                <span class="mobile-field-label">Nhóm sản phẩm NCC</span>
                                <span class="mobile-field-value"><?= e(($row['ten_san_pham_ncc'] ?? '') !== '' ? $row['ten_san_pham_ncc'] : 'Chưa cập nhật') ?></span>
                            </div>
                        </div>

                        <p class="product-short-desc product-short-desc-mobile"><?= e($shortDescription($row['mo_ta_ngan'] ?? '')) ?></p>

                        <div class="mobile-card-actions">
                            <a class="btn btn-muted" href="<?= e(base_url('supplier-products/' . $row['id'])) ?>">Xem chi tiết</a>
                        </div>

                        <div id="product-mobile-<?= e((string) $index) ?>" class="mobile-card-detail" data-collapse-item="product-mobile" hidden>
                            <div class="mobile-detail-list">
                                <div class="mobile-detail-block">
                                    <strong>Nhà cung cấp</strong>
                                    <?= e($row['ten_ncc'] ?? '') ?>
                                </div>
                                <div class="mobile-detail-block">
                                    <strong>Thương hiệu</strong>
                                    <?= e(($row['thuong_hieu'] ?? '') !== '' ? $row['thuong_hieu'] : 'Chưa cập nhật') ?>
                                </div>
                                <div class="mobile-detail-block">
                                    <strong>Tồn kho</strong>
                                    <?= e((string) ($row['ton_kho'] ?? '')) ?>
                                </div>
                                <div class="mobile-detail-block">
                                    <strong>Ngày cập nhật</strong>
                                    <?= e($row['ngay_cap_nhat'] ?? '') ?>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if (($pagination['last_page'] ?? 1) > 1): ?>
            <?php
            $currentPage = (int) $pagination['page'];
            $lastPage = (int) $pagination['last_page'];
            $startPage = max(1, min($currentPage - 1, $lastPage - 2));
            $endPage = min($lastPage, $startPage + 2);
            $startPage = max(1, $endPage - 2);
            $buildLink = static function (int $page) use ($keyword): string {
                return base_url('supplier-products?page=' . $page . ($keyword !== '' ? '&keyword=' . urlencode($keyword) : ''));
            };
            ?>
            <div class="pagination supplier-pagination">
                <?php if ($currentPage > 1): ?>
                    <a class="pagination-link is-arrow" href="<?= e($buildLink(1)) ?>" aria-label="Trang đầu">&laquo;</a>
                    <a class="pagination-link is-arrow" href="<?= e($buildLink($currentPage - 1)) ?>" aria-label="Trang trước">&lsaquo;</a>
                <?php endif; ?>
                <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                    <a class="pagination-link <?= $i === $currentPage ? 'is-active' : '' ?>" href="<?= e($buildLink($i)) ?>">
                        <?= e((string) $i) ?>
                    </a>
                <?php endfor; ?>
                <?php if ($currentPage < $lastPage): ?>
                    <a class="pagination-link is-arrow" href="<?= e($buildLink($currentPage + 1)) ?>" aria-label="Trang sau">&rsaquo;</a>
                    <a class="pagination-link is-arrow" href="<?= e($buildLink($lastPage)) ?>" aria-label="Trang cuối">&raquo;</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </section>
</section>
