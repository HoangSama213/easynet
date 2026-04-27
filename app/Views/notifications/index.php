<section class="supplier-page">
    <div class="supplier-page-head">
        <div>
            <h1 class="page-title">Thông báo</h1>
        </div>
        <form method="POST" action="<?= e(base_url('thong-bao/danh-dau-da-doc')) ?>">
            <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="redirect_to" value="<?= e($_SERVER['REQUEST_URI'] ?? base_url('thong-bao')) ?>">
            <button type="submit" class="btn btn-muted inline-action">Đánh dấu đã đọc</button>
        </form>
    </div>

    <section class="supplier-table-card">
        <div class="desktop-list">
            <div class="table-wrap">
                <table class="product-table product-table-modern notification-table">
                    <colgroup>
                        <col style="width: 52px;">
                        <col style="width: 20%;">
                        <col style="width: 34%;">
                        <col style="width: 12%;">
                        <col style="width: 14%;">
                        <col style="width: 10%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Mức độ</th>
                            <th>Tiêu đề</th>
                            <th>Nội dung</th>
                            <th>Tồn kho</th>
                            <th>Ngày tạo</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($rows)): ?>
                        <tr>
                            <td colspan="6" class="muted">Chưa có thông báo nào.</td>
                        </tr>
                        <?php endif; ?>

                        <?php foreach (($rows ?? []) as $row): ?>
                        <tr class="<?= ($row['trang_thai'] ?? '') === 'chua_xem' ? 'notification-row-unread' : '' ?>">
                            <td>
                                <span class="notification-level <?= e((string) ($row['muc_do'] ?? 'info')) ?>"></span>
                            </td>
                            <td>
                                <strong><?= e($row['tieu_de'] ?? '') ?></strong>
                                <?php if (!empty($row['ten_chi_tiet'])): ?>
                                <div class="muted" style="margin-top: 4px;"><?= e($row['ten_chi_tiet']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td><?= e($row['mo_ta_ngan'] ?? '') ?></td>
                            <td><?= e((string) ($row['ton_kho_hien_tai'] ?? '-')) ?></td>
                            <td><?= e($row['ngay_tao_hien_thi'] ?? '') ?></td>
                            <td>
                                <span class="notification-status <?= ($row['trang_thai'] ?? '') === 'chua_xem' ? 'is-unread' : 'is-read' ?>">
                                    <?= ($row['trang_thai'] ?? '') === 'chua_xem' ? 'Chưa xem' : 'Đã xem' ?>
                                </span>
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
                    <div class="muted">Chưa có thông báo nào.</div>
                </div>
                <?php endif; ?>

                <?php foreach (($rows ?? []) as $row): ?>
                <article class="mobile-card notification-mobile-card <?= ($row['trang_thai'] ?? '') === 'chua_xem' ? 'is-unread' : '' ?>">
                    <div class="mobile-card-head">
                        <div class="notification-mobile-title">
                            <span class="notification-level <?= e((string) ($row['muc_do'] ?? 'info')) ?>"></span>
                            <div>
                                <h4 class="mobile-card-title"><?= e($row['tieu_de'] ?? '') ?></h4>
                                <?php if (!empty($row['ten_chi_tiet'])): ?>
                                <div class="mobile-card-subtitle"><?= e($row['ten_chi_tiet']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if (($row['trang_thai'] ?? '') === 'chua_xem'): ?>
                        <span class="notification-unread-dot" aria-hidden="true"></span>
                        <?php endif; ?>
                    </div>

                    <div class="mobile-detail-block" style="margin-top: 12px;">
                        <?= e($row['mo_ta_ngan'] ?? '') ?>
                    </div>

                    <div class="mobile-card-grid">
                        <div class="mobile-field">
                            <span class="mobile-field-label">Tồn kho</span>
                            <span class="mobile-field-value"><?= e((string) ($row['ton_kho_hien_tai'] ?? '-')) ?></span>
                        </div>
                        <div class="mobile-field">
                            <span class="mobile-field-label">Ngày tạo</span>
                            <span class="mobile-field-value"><?= e($row['ngay_tao_hien_thi'] ?? '') ?></span>
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
            $buildLink = static function (int $page): string {
                return base_url('thong-bao?page=' . $page);
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
