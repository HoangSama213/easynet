<?php $canManage = function_exists('is_editor') && is_editor(); ?>

<section class="supplier-page">
    <div class="supplier-page-head">
        <div>
            <h1 class="page-title">Danh sách combo</h1>
        </div>
        <?php if ($canManage): ?>
        <a class="btn btn-icon supplier-add-btn" href="<?= e(base_url('combos/create')) ?>" aria-label="Thêm combo">+</a>
        <?php endif; ?>
    </div>

    <section class="supplier-table-card">
        <div class="desktop-list">
            <div class="table-wrap">
                <table class="product-table product-table-modern">
                    <colgroup>
                        <col style="width: 52px;">
                        <col style="width: 150px;">
                        <col style="width: 28%;">
                        <col style="width: 110px;">
                        <col style="width: 140px;">
                        <col style="width: 140px;">
                        <col style="width: 110px;">
                        <col style="width: 110px;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Mã combo</th>
                            <th>Tên combo</th>
                            <th>Số sản phẩm</th>
                            <th>Giá lẻ</th>
                            <th>Giá combo</th>
                            <th>Tồn kho</th>
                            <th>Chi tiết</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($rows)): ?>
                        <tr>
                            <td colspan="8" class="muted">Chưa có combo nào.</td>
                        </tr>
                        <?php endif; ?>

                        <?php foreach ($rows as $index => $row): ?>
                        <tr class="supplier-row-main">
                            <td>
                                <button
                                    type="button"
                                    class="toggle-btn collapse-toggle"
                                    data-target="combo-row-<?= e((string) $index) ?>"
                                    data-collapse-group="combo-desktop"
                                    aria-expanded="false"
                                    aria-label="Mở chi tiết">
                                    <span class="collapse-icon">
                                        <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                                    </span>
                                </button>
                            </td>
                            <td><strong><?= e($row['ma_combo'] ?? '') ?></strong></td>
                            <td><?= e($row['ten_combo'] ?? '') ?></td>
                            <td><?= e((string) ($row['so_san_pham'] ?? 0)) ?></td>
                            <td><?= $row['gia_le'] !== null ? number_format((float) $row['gia_le'], 0, ',', '.') : '' ?></td>
                            <td><?= $row['gia_combo'] !== null ? number_format((float) $row['gia_combo'], 0, ',', '.') : '' ?></td>
                            <td><?= e((string) ($row['ton_kho_ao'] ?? 0)) ?></td>
                            <td>
                                <a class="btn btn-muted table-action-btn" href="<?= e(base_url('combos/' . $row['id'])) ?>">Xem</a>
                            </td>
                        </tr>
                        <tr id="combo-row-<?= e((string) $index) ?>" class="detail-row" data-collapse-item="combo-desktop" hidden>
                            <td colspan="8">
                                <div class="nested-card product-nested-card list-accordion-card">
                                    <div class="list-accordion-grid list-accordion-head" style="grid-template-columns: repeat(4, 1fr)">
                                        <div>Cập nhật</div>
                                        <div>Tiết kiệm</div>
                                        <div>Chiến dịch</div>
                                        <div>Trạng thái</div>
                                    </div>
                                    <div class="list-accordion-grid list-accordion-body" style="grid-template-columns: repeat(4, 1fr)">
                                        <div><?= e($row['cap_nhat'] ?? '') ?></div>
                                        <div>
                                            <?php
                                            $tietKiem = (float) ($row['gia_le'] ?? 0) - (float) ($row['gia_combo'] ?? 0);
                                            echo $tietKiem > 0 ? number_format($tietKiem, 0, ',', '.') . ' đ' : '-';
                                            ?>
                                        </div>
                                        <div><?= e((string) ($row['so_chien_dich'] ?? 0)) ?></div>
                                        <div><?= ($row['trang_thai'] ?? '') === 'active' ? 'Đang hoạt động' : 'Ngừng' ?></div>
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
                    <div class="muted">Chưa có combo nào.</div>
                </div>
                <?php endif; ?>

                <?php foreach ($rows as $index => $row): ?>
                <article class="mobile-card">
                    <div class="mobile-card-head">
                        <div>
                            <h4 class="mobile-card-title"><?= e($row['ma_combo'] ?? '') ?></h4>
                            <div class="mobile-card-subtitle"><?= e($row['ten_combo'] ?? '') ?></div>
                        </div>
                        <button
                            type="button"
                            class="toggle-btn collapse-toggle"
                            data-target="combo-mobile-<?= e((string) $index) ?>"
                            data-collapse-group="combo-mobile"
                            aria-expanded="false"
                            aria-label="Mở chi tiết">
                            <span class="collapse-icon">
                                <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                            </span>
                        </button>
                    </div>

                    <div class="mobile-card-grid">
                        <div class="mobile-field">
                            <span class="mobile-field-label">Số sản phẩm</span>
                            <span class="mobile-field-value"><?= e((string) ($row['so_san_pham'] ?? 0)) ?></span>
                        </div>
                        <div class="mobile-field">
                            <span class="mobile-field-label">Giá combo</span>
                            <span class="mobile-field-value"><?= $row['gia_combo'] !== null ? number_format((float) $row['gia_combo'], 0, ',', '.') : '' ?></span>
                        </div>
                        <div class="mobile-field">
                            <span class="mobile-field-label">Tồn kho</span>
                            <span class="mobile-field-value"><?= e((string) ($row['ton_kho_ao'] ?? 0)) ?></span>
                        </div>
                    </div>

                    <div class="mobile-card-actions">
                        <a class="btn btn-muted" href="<?= e(base_url('combos/' . $row['id'])) ?>">Xem chi tiết</a>
                    </div>

                    <div id="combo-mobile-<?= e((string) $index) ?>" class="mobile-card-detail" data-collapse-item="combo-mobile" hidden>
                        <div class="mobile-detail-list">
                            <div class="mobile-detail-block">
                                <strong>Cập nhật</strong>
                                <?= e($row['cap_nhat'] ?? '') ?>
                            </div>
                            <div class="mobile-detail-block">
                                <strong>Giá lẻ</strong>
                                <?= $row['gia_le'] !== null ? number_format((float) $row['gia_le'], 0, ',', '.') : '' ?>
                            </div>
                            <div class="mobile-detail-block">
                                <strong>Trạng thái</strong>
                                <?= ($row['trang_thai'] ?? '') === 'active' ? 'Đang hoạt động' : 'Ngừng' ?>
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
                return base_url('combos?page=' . $page . ($keyword !== '' ? '&keyword=' . urlencode($keyword) : ''));
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
