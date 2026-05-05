<?php
$canManage = function_exists('is_editor') && is_editor();
$items = $items ?? [];
$pagination = $pagination ?? ['page' => 1, 'last_page' => 1];
$keyword = (string) ($keyword ?? '');
$createUrl = (string) ($createUrl ?? '');
?>

<section class="supplier-page">
    <div class="supplier-page-head">
        <div>
            <h1 class="page-title">Quản lý khách hàng</h1>
        </div>
        <?php if ($canManage && !empty($createUrl)): ?>
        <a class="btn btn-icon supplier-add-btn" href="<?= e($createUrl) ?>" aria-label="Thêm khách hàng">+</a>
        <?php endif; ?>
    </div>

    <section class="supplier-table-card">
        <div class="desktop-list">
            <div class="table-wrap">
                <table class="customer-table customer-table-modern">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Phân loại</th>
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
                                    data-target="customer-row-<?= e((string) $index) ?>"
                                    data-collapse-group="customer-desktop"
                                    aria-expanded="false"
                                    aria-label="Mở chi tiết">
                                    <span class="collapse-icon">
                                        <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                                    </span>
                                </button>
                            </td>
                            <td><strong><?= e($item['phan_loai']) ?></strong></td>
                        </tr>
                        <tr id="customer-row-<?= e((string) $index) ?>" class="detail-row" data-collapse-item="customer-desktop" hidden>
                            <td colspan="2">
                                <div class="nested-card customer-nested-card list-accordion-card">
                                    <div class="table-wrap">
                                        <table class="detail-table-modern">
                                            <thead>
                                                <tr>
                                                    <th>Phân loại khách hàng</th>
                                                    <th>Khách hàng tiêu biểu</th>
                                                    <th>Ghi chú</th>
                                                    <th>Chức năng</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($item['details'] as $detail): ?>
                                                <tr>
                                                    <td><?= e($detail['phan_loai_khach_hang'] ?? 'Chưa cập nhật') ?></td>
                                                    <td><?= e($detail['khach_hang_tieu_bieu'] ?? 'Chưa cập nhật') ?></td>
                                                    <td><?= e($detail['ghi_chu'] ?? 'Không có') ?></td>
                                                    <td>
                                                        <?php if ($canManage): ?>
                                                        <div class="table-action-group">
                                                            <a class="btn btn-muted table-action-btn" href="<?= e(base_url('khach-hang/edit/' . $detail['id'])) ?>">Sửa</a>
                                                            <form method="POST" action="<?= e(base_url('khach-hang/delete/' . $detail['id'])) ?>">
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
                            <h4 class="mobile-card-title"><?= e($item['phan_loai']) ?></h4>
                        </div>
                        <button
                            type="button"
                            class="toggle-btn collapse-toggle"
                            data-target="customer-mobile-<?= e((string) $index) ?>"
                            data-collapse-group="customer-mobile"
                            aria-expanded="false"
                            aria-label="Mở chi tiết">
                            <span class="collapse-icon">
                                <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                            </span>
                        </button>
                    </div>

                    <div id="customer-mobile-<?= e((string) $index) ?>" class="mobile-card-detail" data-collapse-item="customer-mobile" hidden>
                        <div class="mobile-detail-list">
                            <?php foreach ($item['details'] as $detail): ?>
                            <div class="mobile-detail-block">
                                <strong><?= e($detail['phan_loai_khach_hang'] ?? 'Chưa cập nhật') ?></strong>
                                <div><span class="muted">Khách hàng:</span> <?= e($detail['khach_hang_tieu_bieu'] ?? 'Chưa cập nhật') ?></div>
                                <div style="margin-top: 6px;"><span class="muted">Ghi chú:</span> <?= e($detail['ghi_chu'] ?? 'Không có') ?></div>
                                <?php if ($canManage): ?>
                                <div class="mobile-card-actions">
                                    <a class="btn btn-muted inline-action" href="<?= e(base_url('khach-hang/edit/' . $detail['id'])) ?>">Sửa</a>
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

                return base_url('khach-hang?' . implode('&', $query));
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
