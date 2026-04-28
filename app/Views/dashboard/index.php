<section class="card">
    <form method="GET" action="<?= e(base_url()) ?>" class="search-panel">
        <label for="q">Tìm kiếm toàn hệ thống</label>
        <div class="search-row">
            <input
                id="q"
                type="text"
                name="q"
                value="<?= e($searchKeyword) ?>"
                placeholder="Tên NCC, sản phẩm, thương hiệu, website">
            <button type="submit">Tìm kiếm</button>
        </div>
    </form>
</section>

<?php if ($searchKeyword !== ''): ?>
    <section class="card section-space-top">
        <div class="stack section-head">
            <h3 class="section-title-reset">Kết quả tìm kiếm</h3>
            <span class="badge"><?= e((string) count($searchResults)) ?> kết quả</span>
        </div>

        <?php if (empty($searchResults)): ?>
            <p class="muted">Không có dữ liệu phù hợp.</p>
        <?php else: ?>
            <div class="desktop-list">
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Nhà cung cấp</th>
                                <th>Hạng mục</th>
                                <th>Website</th>
                                <th>Người liên hệ</th>
                                <th>Group Zalo</th>
                                <th>Sản phẩm NCC</th>
                                <th>Thương hiệu phân phối</th>
                                <th>Sản phẩm chi tiết</th>
                                <th>Ghi chú</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($searchResults as $result): ?>
                                <tr>
                                    <td><strong><?= e($result['ten_ncc']) ?></strong></td>
                                    <td><?= e($result['ten_hang_muc'] ?? 'Chưa phân loại') ?></td>
                                    <td>
                                        <?php if (!empty($result['website'])): ?>
                                            <a href="<?= e($result['website']) ?>" target="_blank" rel="noreferrer"><?= e($result['website']) ?></a>
                                        <?php else: ?>
                                            <span class="muted">Không có</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= e($result['nguoi_lien_he'] ?? 'Chưa cập nhật') ?></td>
                                    <td><?= e($result['nhom_zalo'] ?? 'Chưa cập nhật') ?></td>
                                    <td><?= e($result['san_pham_ncc'] ?? 'Chưa cập nhật') ?></td>
                                    <td><?= e($result['thuong_hieu_phan_phoi'] ?? 'Chưa cập nhật') ?></td>
                                    <td><?= e($result['san_pham_chi_tiet'] ?? 'Chưa cập nhật') ?></td>
                                    <td><?= e($result['ghi_chu'] ?? 'Không có') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mobile-list">
                <div class="mobile-card-list">
                    <?php foreach ($searchResults as $result): ?>
                        <article class="mobile-card">
                            <div class="mobile-card-head">
                                <div>
                                    <h4 class="mobile-card-title"><?= e($result['ten_ncc']) ?></h4>
                                    <div class="mobile-card-subtitle"><?= e($result['ten_hang_muc'] ?? 'Chưa phân loại') ?></div>
                                </div>
                            </div>

                            <div class="mobile-card-grid">
                                <div class="mobile-field">
                                    <span class="mobile-field-label">Người liên hệ</span>
                                    <span class="mobile-field-value"><?= e($result['nguoi_lien_he'] ?? 'Chưa cập nhật') ?></span>
                                </div>
                                <div class="mobile-field">
                                    <span class="mobile-field-label">Group Zalo</span>
                                    <span class="mobile-field-value"><?= e($result['nhom_zalo'] ?? 'Chưa cập nhật') ?></span>
                                </div>
                            </div>

                            <div class="mobile-detail-list section-space-top-sm">
                                <div class="mobile-detail-block">
                                    <strong>Website</strong>
                                    <?php if (!empty($result['website'])): ?>
                                        <a href="<?= e($result['website']) ?>" target="_blank" rel="noreferrer"><?= e($result['website']) ?></a>
                                    <?php else: ?>
                                        <span class="muted">Không có</span>
                                    <?php endif; ?>
                                </div>
                                <div class="mobile-detail-block">
                                    <strong>Sản phẩm NCC</strong>
                                    <?= e($result['san_pham_ncc'] ?? 'Chưa cập nhật') ?>
                                </div>
                                <div class="mobile-detail-block">
                                    <strong>Thương hiệu phân phối</strong>
                                    <?= e($result['thuong_hieu_phan_phoi'] ?? 'Chưa cập nhật') ?>
                                </div>
                                <div class="mobile-detail-block">
                                    <strong>Sản phẩm chi tiết</strong>
                                    <?= e($result['san_pham_chi_tiet'] ?? 'Chưa cập nhật') ?>
                                </div>
                                <div class="mobile-detail-block">
                                    <strong>Ghi chú</strong>
                                    <?= e($result['ghi_chu'] ?? 'Không có') ?>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </section>
<?php endif; ?>

<section class="card section-space-top">
    <div class="stack section-head">
        <h3 class="section-title-reset">NCC mới cập nhật</h3>
        <a class="btn" href="<?= e(base_url('items')) ?>">Xem thêm</a>
    </div>

    <div class="desktop-list">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nhà cung cấp</th>
                        <th>Hạng mục</th>
                        <th>Người liên hệ</th>
                        <th>Nhóm Zalo</th>
                        <th>Website</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentSuppliers as $supplier): ?>
                        <tr>
                            <td><strong><?= e($supplier['ten_ncc']) ?></strong></td>
                            <td><?= e($supplier['ten_hang_muc'] ?? 'Chưa phân loại') ?></td>
                            <td><?= e($supplier['nguoi_lien_he'] ?? 'Chưa cập nhật') ?></td>
                            <td><?= e($supplier['nhom_zalo'] ?? 'Chưa cập nhật') ?></td>
                            <td>
                                <?php if (!empty($supplier['website'])): ?>
                                    <a href="<?= e($supplier['website']) ?>" target="_blank" rel="noreferrer"><?= e($supplier['website']) ?></a>
                                <?php else: ?>
                                    <span class="muted">Không có</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mobile-list">
        <div class="mobile-card-list">
            <?php foreach ($recentSuppliers as $supplier): ?>
                <article class="mobile-card">
                    <div class="mobile-card-head">
                        <div>
                            <h4 class="mobile-card-title"><?= e($supplier['ten_ncc']) ?></h4>
                            <div class="mobile-card-subtitle"><?= e($supplier['ten_hang_muc'] ?? 'Chưa phân loại') ?></div>
                        </div>
                    </div>

                    <div class="mobile-card-grid">
                        <div class="mobile-field">
                            <span class="mobile-field-label">Người liên hệ</span>
                            <span class="mobile-field-value"><?= e($supplier['nguoi_lien_he'] ?? 'Chưa cập nhật') ?></span>
                        </div>
                        <div class="mobile-field">
                            <span class="mobile-field-label">Nhóm Zalo</span>
                            <span class="mobile-field-value"><?= e($supplier['nhom_zalo'] ?? 'Chưa cập nhật') ?></span>
                        </div>
                    </div>

                    <div class="mobile-detail-list section-space-top-sm">
                        <div class="mobile-detail-block">
                            <strong>Website</strong>
                            <?php if (!empty($supplier['website'])): ?>
                                <a href="<?= e($supplier['website']) ?>" target="_blank" rel="noreferrer"><?= e($supplier['website']) ?></a>
                            <?php else: ?>
                                <span class="muted">Không có</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="grid cols-3 section-space-top">
    <article class="card stat-card">
        <span class="muted">Số NCC</span>
        <strong><?= e((string) $stats['total_ncc']) ?></strong>
    </article>
    <article class="card stat-card">
        <span class="muted">Số sản phẩm</span>
        <strong><?= e((string) $stats['total_san_pham']) ?></strong>
    </article>
    <article class="card stat-card">
        <span class="muted">Số thương hiệu</span>
        <strong><?= e((string) $stats['total_thuong_hieu']) ?></strong>
    </article>
</section>
