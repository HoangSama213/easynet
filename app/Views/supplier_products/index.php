<section class="card">
    <form method="GET" action="<?= e(base_url('supplier-products')) ?>" class="search-panel">
        <label for="keyword">Tìm kiếm sản phẩm</label>
        <div class="search-row">
            <input
                id="keyword"
                type="text"
                name="keyword"
                value="<?= e($keyword ?? '') ?>"
                placeholder="Mã SKU, tên chi tiết, thương hiệu, mã NCC...">
            <button type="submit">Lọc</button>
        </div>
    </form>
</section>

<section class="card" style="margin-top: 18px;">
    <div class="stack section-head">
        <h3 style="margin: 0;">Danh sách sản phẩm đang phát triển</h3>
        <a class="btn btn-icon" href="<?= e(base_url('supplier-products/create')) ?>" aria-label="Thêm sản phẩm">+</a>
    </div>

    <div class="desktop-list">
        <div class="table-wrap">
            <table class="product-table">
                <thead>
                    <tr>
                        <th style="width: 64px;"></th>
                        <th>Mã SKU</th>
                        <th>Tên sản phẩm chi tiết</th>
                        <th>Thương hiệu</th>
                        <th>Giá</th>
                        <th>Trạng thái</th>
                        <th>Tồn kho</th>
                        <th>Xem chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($rows)): ?>
                    <tr>
                        <td colspan="8" class="muted">Chưa có dữ liệu sản phẩm NCC.</td>
                    </tr>
                    <?php endif; ?>

                    <?php foreach ($rows as $index => $row): ?>
                    <tr>
                        <td>
                            <button
                                type="button"
                                class="toggle-btn collapse-toggle"
                                data-target="product-row-<?= e((string) $index) ?>"
                                data-collapse-group="product-desktop"
                                aria-expanded="false"
                                aria-label="Mở chi tiết">
                                <span class="collapse-icon">▸</span>
                            </button>
                        </td>
                        <td><?= e($row['ma_sku'] ?? '') ?></td>
                        <td><?= e($row['ten_san_pham_chi_tiet'] ?? '') ?></td>
                        <td><?= e($row['thuong_hieu'] ?? '') ?></td>
                        <td><?= e($row['gia'] ?? '') ?></td>
                        <td><?= e($row['trang_thai'] ?? '') ?></td>
                        <td><?= e($row['ton_kho'] ?? '') ?></td>
                        <td>
                            <a class="btn btn-muted" href="<?= e(base_url('supplier-products/' . $row['ncc_id'] . '/' . $row['chi_tiet_id'])) ?>">Xem chi tiết</a>
                        </td>
                    </tr>
                    <tr id="product-row-<?= e((string) $index) ?>" class="detail-row" data-collapse-item="product-desktop" hidden>
                        <td colspan="8">
                            <div class="nested-card product-nested-card">
                                <div class="table-wrap">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Mã NCC</th>
                                                <th>Tên nhà cung cấp</th>
                                                <th>Tên sản phẩm NCC</th>
                                                <th>Ngày cập nhật</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><strong><?= e($row['ma_ncc']) ?></strong></td>
                                                <td><?= e($row['ten_ncc']) ?></td>
                                                <td><?= e($row['ten_san_pham_ncc']) ?></td>
                                                <td><?= e($row['ngay_cap_nhat'] ?? '') ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
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
                <div class="muted">Chưa có dữ liệu sản phẩm NCC.</div>
            </div>
            <?php endif; ?>

            <?php foreach ($rows as $index => $row): ?>
            <article class="mobile-card">
                <div class="mobile-card-head">
                    <div>
                        <h4 class="mobile-card-title"><?= e($row['ten_san_pham_chi_tiet'] ?? '') ?></h4>
                        <div class="mobile-card-subtitle">Mã SKU: <?= e($row['ma_sku'] ?? '') ?></div>
                    </div>
                    <button
                        type="button"
                        class="toggle-btn collapse-toggle"
                        data-target="product-mobile-<?= e((string) $index) ?>"
                        data-collapse-group="product-mobile"
                        aria-expanded="false"
                        aria-label="Mở chi tiết">
                        <span class="collapse-icon">▸</span>
                    </button>
                </div>

                <div class="mobile-card-grid">
                    <div class="mobile-field">
                        <span class="mobile-field-label">Thương hiệu</span>
                        <span class="mobile-field-value"><?= e($row['thuong_hieu'] ?? '') ?></span>
                    </div>
                    <div class="mobile-field">
                        <span class="mobile-field-label">Trạng thái</span>
                        <span class="mobile-field-value"><?= e($row['trang_thai'] ?? '') ?></span>
                    </div>
                    <div class="mobile-field">
                        <span class="mobile-field-label">Giá</span>
                        <span class="mobile-field-value"><?= e($row['gia'] ?? '') ?></span>
                    </div>
                    <div class="mobile-field">
                        <span class="mobile-field-label">Tồn kho</span>
                        <span class="mobile-field-value"><?= e($row['ton_kho'] ?? '') ?></span>
                    </div>
                </div>

                <div class="mobile-card-actions">
                    <a class="btn btn-muted" href="<?= e(base_url('supplier-products/' . $row['ncc_id'] . '/' . $row['chi_tiet_id'])) ?>">Xem chi tiết</a>
                </div>

                <div id="product-mobile-<?= e((string) $index) ?>" class="mobile-card-detail" data-collapse-item="product-mobile" hidden>
                    <div class="mobile-detail-list">
                        <div class="mobile-detail-block">
                            <strong>Mã NCC</strong>
                            <?= e($row['ma_ncc']) ?>
                        </div>
                        <div class="mobile-detail-block">
                            <strong>Tên nhà cung cấp</strong>
                            <?= e($row['ten_ncc']) ?>
                        </div>
                        <div class="mobile-detail-block">
                            <strong>Tên sản phẩm NCC</strong>
                            <?= e($row['ten_san_pham_ncc']) ?>
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
    <div class="pagination">
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
