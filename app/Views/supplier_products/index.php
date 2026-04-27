<section class="supplier-page">
    <div class="supplier-page-head">
        <div>
            <h1 class="page-title">Danh sách phân loại sản phẩm</h1>
        </div>
        <a class="btn btn-icon supplier-add-btn" href="<?= e(base_url('supplier-products/create')) ?>" aria-label="Thêm sản phẩm">+</a>
    </div>

    <section class="supplier-table-card">
        <div class="desktop-list">
            <div class="table-wrap">
                <table class="product-table product-table-modern">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Mã SKU</th>
                            <th>Tên sản phẩm chi tiết</th>
                            <th>Nhà cung cấp</th>
                            <th>Thương hiệu</th>
                            <th>Giá</th>
                            <th>Chi tiết</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($rows)): ?>
                        <tr>
                            <td colspan="7" class="muted">Chưa có dữ liệu sản phẩm.</td>
                        </tr>
                        <?php endif; ?>

                        <?php foreach ($rows as $index => $row): ?>
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
                            <td><strong><?= e($row['ma_sku'] ?? '') ?></strong></td>
                            <td><?= e($row['ten_san_pham_chi_tiet'] ?? '') ?></td>
                            <td><?= e($row['ten_ncc'] ?? '') ?></td>
                            <td><?= e($row['thuong_hieu'] ?? '') ?></td>
                            <td><?= e($row['gia'] ?? '') ?></td>
                            <td>
                                <a class="btn btn-muted table-action-btn" href="<?= e(base_url('supplier-products/' . $row['id'])) ?>">Xem</a>
                            </td>
                        </tr>
                        <tr id="product-row-<?= e((string) $index) ?>" class="detail-row" data-collapse-item="product-desktop" hidden>
                            <td colspan="7">
                                <div class="nested-card product-nested-card list-accordion-card">
                                    <div class="list-accordion-grid list-accordion-head">
                                        <div>Mã NCC</div>
                                        <div>Nhóm sản phẩm NCC</div>
                                        <div>Trạng thái</div>
                                        <div>Tồn kho</div>
                                        <div>Ngày cập nhật</div>
                                    </div>
                                    <div class="list-accordion-grid list-accordion-body">
                                        <div><?= e($row['ma_ncc'] ?? '') ?></div>
                                        <div><?= e($row['ten_san_pham_ncc'] ?? '') ?></div>
                                        <div><?= e($row['trang_thai'] ?? '') ?></div>
                                        <div><?= e($row['ton_kho'] ?? '') ?></div>
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
                <article class="mobile-card">
                    <div class="mobile-card-head">
                        <div>
                            <h4 class="mobile-card-title"><?= e($row['ma_sku'] ?? '') ?></h4>
                            <div class="mobile-card-subtitle"><?= e($row['ten_san_pham_chi_tiet'] ?? '') ?></div>
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
                            <span class="mobile-field-label">Nhà cung cấp</span>
                            <span class="mobile-field-value"><?= e($row['ten_ncc'] ?? '') ?></span>
                        </div>
                        <div class="mobile-field">
                            <span class="mobile-field-label">Thương hiệu</span>
                            <span class="mobile-field-value"><?= e($row['thuong_hieu'] ?? '') ?></span>
                        </div>
                        <div class="mobile-field">
                            <span class="mobile-field-label">Giá</span>
                            <span class="mobile-field-value"><?= e($row['gia'] ?? '') ?></span>
                        </div>
                    </div>

                    <div class="mobile-card-actions">
                        <a class="btn btn-muted" href="<?= e(base_url('supplier-products/' . $row['id'])) ?>">Xem chi tiết</a>
                    </div>

                    <div id="product-mobile-<?= e((string) $index) ?>" class="mobile-card-detail" data-collapse-item="product-mobile" hidden>
                        <div class="mobile-detail-list">
                            <div class="mobile-detail-block">
                                <strong>Mã NCC</strong>
                                <?= e($row['ma_ncc'] ?? '') ?>
                            </div>
                            <div class="mobile-detail-block">
                                <strong>Nhóm sản phẩm NCC</strong>
                                <?= e($row['ten_san_pham_ncc'] ?? '') ?>
                            </div>
                            <div class="mobile-detail-block">
                                <strong>Trạng thái</strong>
                                <?= e($row['trang_thai'] ?? '') ?>
                            </div>
                            <div class="mobile-detail-block">
                                <strong>Tồn kho</strong>
                                <?= e($row['ton_kho'] ?? '') ?>
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
