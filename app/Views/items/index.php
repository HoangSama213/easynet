<section class="supplier-page">
    <div class="supplier-page-head">
        <div>
            <h1 class="page-title">Danh sách nhà cung cấp</h1>
        </div>
        <a class="btn btn-icon supplier-add-btn" href="<?= e(base_url('items/create')) ?>" aria-label="Thêm nhà cung cấp">+</a>
    </div>

    <section class="supplier-table-card">
        <div class="desktop-list">
            <div class="table-wrap">
                <table class="supplier-table supplier-table-modern">
                    <colgroup>
                        <col style="width: 36px;">
                        <col style="width: 25.16%;">
                        <col style="width: 17.61%;">
                        <col style="width: 22.64%;">
                        <col style="width: 18.87%;">
                        <col style="width: 90px;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Nhà cung cấp</th>
                            <th>Hạng mục</th>
                            <th>Website</th>
                            <th>Người liên hệ</th>
                            <th>Chức năng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($suppliers)): ?>
                        <tr>
                            <td colspan="6" class="muted">Chưa có dữ liệu phù hợp.</td>
                        </tr>
                        <?php endif; ?>

                        <?php foreach ($suppliers as $index => $supplier): ?>
                        <tr class="supplier-row-main">
                            <td>
                                <button
                                    type="button"
                                    class="toggle-btn collapse-toggle"
                                    data-target="supplier-row-<?= e((string) $index) ?>"
                                    data-collapse-group="supplier-desktop"
                                    aria-expanded="false"
                                    aria-label="Mở chi tiết">
                                    <span class="collapse-icon">
                                        <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                                    </span>
                                </button>
                            </td>
                            <td>
                                <div class="supplier-main-cell">
                                    <strong><?= e($supplier['ten_ncc']) ?></strong>
                                    <span class="supplier-code">Mã NCC: <?= e($supplier['ma_ncc'] ?? '') ?></span>
                                </div>
                            </td>
                            <td><?= e($supplier['ten_hang_muc'] ?? 'Chưa phân loại') ?></td>
                            <td>
                                <?php if (!empty($supplier['website'])): ?>
                                <a class="supplier-link" href="<?= e($supplier['website']) ?>" target="_blank" rel="noreferrer">
                                    <?= e($supplier['website']) ?>
                                </a>
                                <?php else: ?>
                                <span class="muted">Không có</span>
                                <?php endif; ?>
                            </td>
                            <td><?= e($supplier['nguoi_lien_he'] ?? 'Chưa cập nhật') ?></td>
                            <td>
                                <div class="table-action-group">
                                    <a class="btn btn-muted table-action-btn" href="<?= e(base_url('items/edit/' . $supplier['id'])) ?>">Sửa</a>
                                    <form method="POST" action="<?= e(base_url('items/delete/' . $supplier['id'])) ?>" onsubmit="return confirm('Bạn có chắc muốn xóa nhà cung cấp &quot;<?= e($supplier['ten_ncc']) ?>&quot; không?');">
                                        <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                                        <button type="submit" class="btn btn-muted table-action-btn">Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <tr id="supplier-row-<?= e((string) $index) ?>" class="detail-row" data-collapse-item="supplier-desktop" hidden>
                            <td colspan="6">
                                <div class="nested-card supplier-nested-card supplier-accordion-card">
                                    <div class="supplier-accordion-grid supplier-accordion-head">
                                        <div>Group Zalo</div>
                                        <div>Sản phẩm NCC</div>
                                        <div>Thương hiệu phân phối</div>
                                        <div>Sản phẩm chi tiết</div>
                                        <div>Ghi chú</div>
                                        <div></div>
                                    </div>
                                    <div class="supplier-accordion-grid supplier-accordion-body">
                                        <div><?= e($supplier['nhom_zalo'] ?? 'Chưa cập nhật') ?></div>
                                        <div><?= e($supplier['san_pham_ncc'] ?? 'Chưa cập nhật') ?></div>
                                        <div><?= e($supplier['thuong_hieu_phan_phoi'] ?? 'Chưa cập nhật') ?></div>
                                        <div><?= e($supplier['san_pham_chi_tiet'] ?? 'Chưa cập nhật') ?></div>
                                        <div><?= e($supplier['ghi_chu'] ?? 'Không có') ?></div>
                                        <div></div>
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
                <?php if (empty($suppliers)): ?>
                <div class="mobile-card">
                    <div class="muted">Chưa có dữ liệu phù hợp.</div>
                </div>
                <?php endif; ?>

                <?php foreach ($suppliers as $index => $supplier): ?>
                <article class="mobile-card supplier-mobile-card">
                    <div class="mobile-card-head">
                        <div>
                            <h4 class="mobile-card-title"><?= e($supplier['ten_ncc']) ?></h4>
                            <div class="mobile-card-subtitle">Mã NCC: <?= e($supplier['ma_ncc'] ?? '') ?></div>
                        </div>
                        <button
                            type="button"
                            class="toggle-btn collapse-toggle"
                            data-target="supplier-mobile-<?= e((string) $index) ?>"
                            data-collapse-group="supplier-mobile"
                            aria-expanded="false"
                            aria-label="Mở chi tiết">
                            <span class="collapse-icon">
                                <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                            </span>
                        </button>
                    </div>

                    <div class="mobile-card-grid">
                        <div class="mobile-field">
                            <span class="mobile-field-label">Hạng mục</span>
                            <span class="mobile-field-value"><?= e($supplier['ten_hang_muc'] ?? 'Chưa phân loại') ?></span>
                        </div>
                        <div class="mobile-field">
                            <span class="mobile-field-label">Người liên hệ</span>
                            <span class="mobile-field-value"><?= e($supplier['nguoi_lien_he'] ?? 'Chưa cập nhật') ?></span>
                        </div>
                        <div class="mobile-field">
                            <span class="mobile-field-label">Website</span>
                            <span class="mobile-field-value">
                                <?php if (!empty($supplier['website'])): ?>
                                <a class="supplier-link" href="<?= e($supplier['website']) ?>" target="_blank" rel="noreferrer"><?= e($supplier['website']) ?></a>
                                <?php else: ?>
                                Không có
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>

                    <div class="mobile-card-actions">
                        <a class="btn btn-muted" href="<?= e(base_url('items/edit/' . $supplier['id'])) ?>">Sửa</a>
                        <form method="POST" action="<?= e(base_url('items/delete/' . $supplier['id'])) ?>" onsubmit="return confirm('Bạn có chắc muốn xóa nhà cung cấp &quot;<?= e($supplier['ten_ncc']) ?>&quot; không?');">
                            <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                            <button type="submit" class="btn btn-muted">Xóa</button>
                        </form>
                    </div>

                    <div id="supplier-mobile-<?= e((string) $index) ?>" class="mobile-card-detail" data-collapse-item="supplier-mobile" hidden>
                        <div class="mobile-detail-list">
                            <div class="mobile-detail-block">
                                <strong>Group Zalo</strong>
                                <?= e($supplier['nhom_zalo'] ?? 'Chưa cập nhật') ?>
                            </div>
                            <div class="mobile-detail-block">
                                <strong>Sản phẩm NCC</strong>
                                <?= e($supplier['san_pham_ncc'] ?? 'Chưa cập nhật') ?>
                            </div>
                            <div class="mobile-detail-block">
                                <strong>Thương hiệu phân phối</strong>
                                <?= e($supplier['thuong_hieu_phan_phoi'] ?? 'Chưa cập nhật') ?>
                            </div>
                            <div class="mobile-detail-block">
                                <strong>Sản phẩm chi tiết</strong>
                                <?= e($supplier['san_pham_chi_tiet'] ?? 'Chưa cập nhật') ?>
                            </div>
                            <div class="mobile-detail-block">
                                <strong>Ghi chú</strong>
                                <?= e($supplier['ghi_chu'] ?? 'Không có') ?>
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
            $buildLink = static function (int $page) use ($keyword, $categoryId): string {
                $query = ['page=' . $page];

                if ($keyword !== '') {
                    $query[] = 'keyword=' . urlencode($keyword);
                }

                if ((int) $categoryId > 0) {
                    $query[] = 'category_id=' . (int) $categoryId;
                }

                return base_url('items?' . implode('&', $query));
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
