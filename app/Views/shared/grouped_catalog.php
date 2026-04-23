<section class="card">
    <form method="GET" action="<?= e(base_url($basePath)) ?>" class="search-panel">
        <div class="search-row">
            <input
                id="keyword"
                type="text"
                name="keyword"
                value="<?= e($keyword ?? '') ?>"
                placeholder="Tìm kiếm hệ sinh thái, chi tiết, hãng nổi bật, sản phẩm...">
            <button type="submit">Lọc</button>
        </div>
    </form>
</section>

<section class="card" style="margin-top: 18px;">
    <div class="stack section-head">
        <h3 style="margin: 0;"><?= e($heading) ?></h3>
        <div class="stack">
            <span class="badge"><?= e((string) ($pagination['total'] ?? 0)) ?> <?= e($countLabel ?? 'mục') ?></span>
            <?php if (!empty($createUrl)): ?>
            <a class="btn btn-icon" href="<?= e($createUrl) ?>" aria-label="Thêm mới">+</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="desktop-list">
        <div class="table-wrap">
            <table class="catalog-table">
                <thead>
                    <tr>
                        <th style="width: 64px;"></th>
                        <th>Hệ sinh thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="2" class="muted">Chưa có dữ liệu phù hợp.</td>
                    </tr>
                    <?php endif; ?>

                    <?php foreach ($items as $index => $item): ?>
                    <tr>
                        <td>
                            <button
                                type="button"
                                class="toggle-btn collapse-toggle"
                                data-target="catalog-row-<?= e((string) $index) ?>"
                                data-collapse-group="catalog-desktop"
                                aria-expanded="false"
                                aria-label="Mở chi tiết">
                                <span class="collapse-icon">▸</span>
                            </button>
                        </td>
                        <td>
                            <strong><?= e($item['he_sinh_thai']) ?></strong>
                            <div class="muted" style="margin-top: 4px; font-size: 0.86rem;"><?= e($item['nhom']) ?></div>
                        </td>
                    </tr>
                    <tr id="catalog-row-<?= e((string) $index) ?>" class="detail-row" data-collapse-item="catalog-desktop" hidden>
                        <td colspan="2">
                            <div class="nested-card catalog-nested-card">
                                <div class="table-wrap">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Chi tiết</th>
                                                <th>Hãng nổi bật</th>
                                                <th>Sản phẩm</th>
                                                <th>Nhà phân phối của hãng</th>
                                                <th>Ghi chú</th>
                                                <th>Chức năng</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($item['details'] as $detail): ?>
                                            <tr>
                                                <td><?= e($detail['chi_tiet']) ?></td>
                                                <td><?= e($detail['hang_noi_bat'] ?? 'Chưa cập nhật') ?></td>
                                                <td><?= e($detail['san_pham'] ?? 'Chưa cập nhật') ?></td>
                                                <td><?= e($detail['nha_phan_phoi'] ?? 'Chưa cập nhật') ?></td>
                                                <td><?= e($detail['ghi_chu'] ?? 'Không có') ?></td>
                                                <td>
                                                    <div class="stack">
                                                        <a class="btn btn-muted inline-action" href="<?= e(base_url($basePath . '/edit/' . $detail['table_name'] . '/' . $detail['id'])) ?>">Sửa</a>
                                                        <form method="POST" action="<?= e(base_url($basePath . '/delete/' . $detail['table_name'] . '/' . $detail['id'])) ?>">
                                                            <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                                                            <button type="submit" class="btn btn-muted inline-action" onclick="return confirm('Xóa mục này?')">Xóa</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
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
            <?php if (empty($items)): ?>
            <div class="mobile-card">
                <div class="muted">Chưa có dữ liệu phù hợp.</div>
            </div>
            <?php endif; ?>

            <?php foreach ($items as $index => $item): ?>
            <article class="mobile-card">
                <div class="mobile-card-head">
                    <div>
                        <h4 class="mobile-card-title"><?= e($item['he_sinh_thai']) ?></h4>
                        <div class="mobile-card-subtitle"><?= e($item['nhom']) ?></div>
                    </div>
                    <button
                        type="button"
                        class="toggle-btn collapse-toggle"
                        data-target="catalog-mobile-<?= e((string) $index) ?>"
                        data-collapse-group="catalog-mobile"
                        aria-expanded="false"
                        aria-label="Mở chi tiết">
                        <span class="collapse-icon">▸</span>
                    </button>
                </div>

                <div id="catalog-mobile-<?= e((string) $index) ?>" class="mobile-card-detail" data-collapse-item="catalog-mobile" hidden>
                    <div class="mobile-detail-list">
                        <?php foreach ($item['details'] as $detail): ?>
                        <div class="mobile-detail-block">
                            <strong><?= e($detail['chi_tiet']) ?></strong>
                            <div><span class="muted">Hãng:</span> <?= e($detail['hang_noi_bat'] ?? 'Chưa cập nhật') ?></div>
                            <div><span class="muted">Sản phẩm:</span> <?= e($detail['san_pham'] ?? 'Chưa cập nhật') ?></div>
                            <div><span class="muted">Nhà phân phối:</span> <?= e($detail['nha_phan_phoi'] ?? 'Chưa cập nhật') ?></div>
                            <div><span class="muted">Ghi chú:</span> <?= e($detail['ghi_chu'] ?? 'Không có') ?></div>
                            <div class="mobile-card-actions">
                                <a class="btn btn-muted inline-action" href="<?= e(base_url($basePath . '/edit/' . $detail['table_name'] . '/' . $detail['id'])) ?>">Sửa</a>
                                <form method="POST" action="<?= e(base_url($basePath . '/delete/' . $detail['table_name'] . '/' . $detail['id'])) ?>">
                                    <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                                    <button type="submit" class="btn btn-muted inline-action" onclick="return confirm('Xóa mục này?')">Xóa</button>
                                </form>
                            </div>
                        </div>
                        <?php endforeach; ?>
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
        $buildLink = static function (int $page) use ($keyword, $basePath): string {
            $query = ['page=' . $page];

            if (($keyword ?? '') !== '') {
                $query[] = 'keyword=' . urlencode($keyword);
            }

            return base_url($basePath . '?' . implode('&', $query));
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
