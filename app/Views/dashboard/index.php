<section class="card">
    <form method="GET" action="<?= e(base_url()) ?>" class="search-panel">
        <label for="q">Tìm kiếm toàn hệ thống</label>
        <div class="search-row">
            <input id="q" type="text" name="q" value="<?= e($searchKeyword) ?>"
                placeholder="Tên NCC, sản phẩm, thương hiệu, website">
            <button type="submit">Tìm kiếm</button>
        </div>
    </form>
</section>

<?php if ($searchKeyword !== ''): ?>
<section class="card" style="margin-top: 18px;">
    <div class="stack"
        style="justify-content: space-between; align-items: center; margin-bottom: 14px;">
        <h3 style="margin: 0;">Kết quả tìm kiếm</h3>
        <span class="badge"><?= e((string) count($searchResults)) ?> kết quả</span>
    </div>
    <?php if (empty($searchResults)): ?>
    <p class="muted">Không có dữ liệu phù hợp.</p>
    <?php else: ?>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nhà cung cấp

                    </th>
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
                        <a href="<?= e($result['website']) ?>" target="_blank"
                            rel="noreferrer"><?= e($result['website']) ?></a>
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
    <?php endif; ?>
</section>
<?php endif; ?>

<section class="card" style="margin-top: 18px;">
    <div class="stack"
        style="justify-content: space-between; align-items: center; margin-bottom: 14px;">
        <h3 style="margin: 0;">NCC mới cập nhật</h3>
        <a class="btn" href="<?= e(base_url('items')) ?>">Xem thêm</a>
    </div>
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
                        <a href="<?= e($supplier['website']) ?>" target="_blank"
                            rel="noreferrer"><?= e($supplier['website']) ?></a>
                        <?php else: ?>
                        <span class="muted">Không có</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<section class="grid cols-3" style="margin-top: 18px;">
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