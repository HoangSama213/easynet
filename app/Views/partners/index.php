<?php $canManage = function_exists('is_editor') && is_editor(); ?>

<section class="supplier-page">
    <div class="supplier-page-head">
        <div>
            <h1 class="page-title">Quản lý đối tác</h1>
        </div>
        <?php if ($canManage && !empty($createUrl)): ?>
        <a class="btn btn-icon supplier-add-btn" href="<?= e($createUrl) ?>" aria-label="Thêm đối tác">+</a>
        <?php endif; ?>
    </div>

    <section class="supplier-table-card">
        <div class="desktop-list">
            <div class="table-wrap">
                <table class="partner-table partner-table-modern">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Lĩnh vực</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($items)): ?>
                        <tr>
                            <td colspan="2" class="muted">Chưa có dữ liệu phù hợp.</td>
                        </tr>
                        <?php endif; ?>

                        <?php foreach ($items as $index => $item): ?>
                        <tr class="supplier-row-main">
                            <td>
                                <button
                                    type="button"
                                    class="toggle-btn collapse-toggle"
                                    data-target="partner-row-<?= e((string) $index) ?>"
                                    data-collapse-group="partner-desktop"
                                    aria-expanded="false"
                                    aria-label="Mở chi tiết">
                                    <span class="collapse-icon">
                                        <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                                    </span>
                                </button>
                            </td>
                            <td><strong><?= e($item['linh_vuc']) ?></strong></td>
                        </tr>
                        <tr id="partner-row-<?= e((string) $index) ?>" class="detail-row" data-collapse-item="partner-desktop" hidden>
                            <td colspan="2">
                                <div class="nested-card partner-nested-card list-accordion-card">
                                    <div class="table-wrap">
                                        <table class="detail-table-modern">
                                            <thead>
                                                <tr>
                                                    <th>Đối tác tiêu biểu</th>
                                                    <th>Ghi chú</th>
                                                    <th>Chức năng</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($item['details'] as $detail): ?>
                                                <tr>
                                                    <td><?= e($detail['doi_tac_tieu_bieu'] ?? 'Chưa cập nhật') ?></td>
                                                    <td><?= e($detail['ghi_chu'] ?? 'Không có') ?></td>
                                                    <td>
                                                        <?php if ($canManage): ?>
                                                        <div class="table-action-group">
                                                            <a class="btn btn-muted table-action-btn" href="<?= e(base_url('doi-tac/edit/' . $detail['id'])) ?>">Sửa</a>
                                                            <form method="POST" action="<?= e(base_url('doi-tac/delete/' . $detail['id'])) ?>">
                                                                <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                                                                <button type="submit" class="btn btn-muted table-action-btn" onclick="return confirm('Xóa mục này?')">Xóa</button>
                                                            </form>
                                                        </div>
                                                        <?php else: ?>
                                                        <span class="muted">Chỉ xem</span>
                                                        <?php endif; ?>
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
                            <h4 class="mobile-card-title"><?= e($item['linh_vuc']) ?></h4>
                        </div>
                        <button
                            type="button"
                            class="toggle-btn collapse-toggle"
                            data-target="partner-mobile-<?= e((string) $index) ?>"
                            data-collapse-group="partner-mobile"
                            aria-expanded="false"
                            aria-label="Mở chi tiết">
                            <span class="collapse-icon">
                                <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                            </span>
                        </button>
                    </div>

                    <div id="partner-mobile-<?= e((string) $index) ?>" class="mobile-card-detail" data-collapse-item="partner-mobile" hidden>
                        <div class="mobile-detail-list">
                            <?php foreach ($item['details'] as $detail): ?>
                            <div class="mobile-detail-block">
                                <strong>Đối tác tiêu biểu</strong>
                                <div><?= e($detail['doi_tac_tieu_bieu'] ?? 'Chưa cập nhật') ?></div>
                                <div style="margin-top: 6px;"><span class="muted">Ghi chú:</span> <?= e($detail['ghi_chu'] ?? 'Không có') ?></div>
                                <?php if ($canManage): ?>
                                <div class="mobile-card-actions">
                                    <a class="btn btn-muted inline-action" href="<?= e(base_url('doi-tac/edit/' . $detail['id'])) ?>">Sửa</a>
                                </div>
                                <?php endif; ?>
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
            $buildLink = static function (int $page) use ($keyword): string {
                $query = ['page=' . $page];

                if (($keyword ?? '') !== '') {
                    $query[] = 'keyword=' . urlencode($keyword);
                }

                return base_url('doi-tac?' . implode('&', $query));
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
