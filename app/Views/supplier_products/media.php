<?php
$selectedProduct = $selectedProduct ?? null;
$mediaData = $mediaData ?? ['media' => ['anh' => null, 'video' => null, 'pdf' => null]];
$pagination = $pagination ?? ['page' => 1, 'last_page' => 1];
$keyword = $keyword ?? '';
$oldMediaItems = $old['media_items'] ?? [];

$fixedMediaItems = [
    'anh' => [
        'media_type' => 'image',
        'title' => 'Nhập ảnh',
        'media_url' => $mediaData['media']['anh']['url'] ?? '',
        'ma_tai_nguyen' => $mediaData['media']['anh']['ma_tai_nguyen'] ?? '',
    ],
    'video' => [
        'media_type' => 'video',
        'title' => 'Nhập video',
        'media_url' => $mediaData['media']['video']['url'] ?? '',
        'ma_tai_nguyen' => $mediaData['media']['video']['ma_tai_nguyen'] ?? '',
    ],
    'pdf' => [
        'media_type' => 'pdf',
        'title' => 'Nhập file',
        'media_url' => $mediaData['media']['pdf']['url'] ?? '',
        'ma_tai_nguyen' => $mediaData['media']['pdf']['ma_tai_nguyen'] ?? '',
    ],
];

foreach ($oldMediaItems as $item) {
    $mediaType = (string) ($item['media_type'] ?? '');
    $key = $mediaType === 'image' ? 'anh' : ($mediaType === 'video' ? 'video' : ($mediaType === 'pdf' ? 'pdf' : ''));
    if ($key !== '' && isset($fixedMediaItems[$key])) {
        $fixedMediaItems[$key]['media_url'] = (string) ($item['media_url'] ?? $fixedMediaItems[$key]['media_url']);
    }
}
?>

<?php if ($selectedProduct): ?>
<section class="card">
    <div class="stack section-head">
        <div>
            <h3 style="margin: 0;">Chỉnh sửa tài nguyên media</h3>
            <div class="muted" style="margin-top: 6px;">
                <?= e($selectedProduct['ma_sku'] ?? '') ?> &middot; <?= e($selectedProduct['ten_san_pham_chi_tiet'] ?? '') ?> &middot; <?= e($selectedProduct['ten_ncc'] ?? '') ?>
            </div>
        </div>
        <a class="btn btn-muted inline-action" href="<?= e(base_url('supplier-products/media')) ?>">Quay về danh sách</a>
    </div>

    <form method="POST" action="<?= e(base_url('supplier-products/' . $selectedProduct['id'] . '/media/update')) ?>" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">

        <?php if (isset($errors['media_items'])): ?><small class="field-error"><?= e($errors['media_items']) ?></small><?php endif; ?>

        <div class="product-media-grid">
            <?php foreach (array_values($fixedMediaItems) as $index => $item): ?>
            <div class="nested-card product-media-card">
                <input type="hidden" name="media_items[<?= e((string) $index) ?>][media_type]" value="<?= e((string) ($item['media_type'] ?? '')) ?>">
                <input type="hidden" name="media_items[<?= e((string) $index) ?>][media_url]" value="<?= e((string) ($item['media_url'] ?? '')) ?>">
                <label><?= e((string) ($item['title'] ?? '')) ?></label>
                <input type="file" name="media_uploads[<?= e((string) $index) ?>]" accept="image/*,video/*,.pdf">
                <?php if (!empty($item['ma_tai_nguyen'])): ?>
                <small class="muted">Mã tài nguyên: <?= e((string) $item['ma_tai_nguyen']) ?></small>
                <?php endif; ?>
                <?php if (!empty($item['media_url'])): ?>
                <small class="muted">Đã có tài nguyên hiện tại.</small>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="action-row" style="margin-top: 16px;">
            <div class="action-group action-group-left">
                <button type="submit" class="inline-action">Lưu tài nguyên media</button>
            </div>
            <div class="action-group action-group-right">
                <a class="btn btn-muted inline-action" href="<?= e(base_url('supplier-products/' . $selectedProduct['id'])) ?>">Xem sản phẩm</a>
            </div>
        </div>
    </form>
</section>
<?php else: ?>
<section class="supplier-page">
    <div class="supplier-page-head">
        <div>
            <h1 class="page-title">Tài nguyên media</h1>
        </div>
        <a class="btn btn-muted inline-action" href="<?= e(base_url('supplier-products')) ?>">Về tab sản phẩm</a>
    </div>

    <section class="supplier-table-card">
        <div class="desktop-list">
            <div class="table-wrap">
                <table class="product-table product-table-modern">
                    <colgroup>
                        <col style="width: 150px;">
                        <col style="width: 28%;">
                        <col style="width: 24%;">
                        <col style="width: 10%;">
                        <col style="width: 10%;">
                        <col style="width: 10%;">
                        <col style="width: 120px;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Mã SKU</th>
                            <th>Tên sản phẩm</th>
                            <th>Nhà cung cấp</th>
                            <th>Ảnh</th>
                            <th>Video</th>
                            <th>PDF</th>
                            <th>Chỉnh sửa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($rows)): ?>
                        <tr>
                            <td colspan="7" class="muted">Chưa có dữ liệu sản phẩm để quản lý media.</td>
                        </tr>
                        <?php endif; ?>

                        <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><strong><?= e($row['ma_sku'] ?? '') ?></strong></td>
                            <td><?= e($row['ten_san_pham_chi_tiet'] ?? '') ?></td>
                            <td><?= e($row['ten_ncc'] ?? '') ?></td>
                            <td><?= !empty($row['so_anh']) ? 'Có' : '-' ?></td>
                            <td><?= !empty($row['so_video']) ? 'Có' : '-' ?></td>
                            <td><?= !empty($row['so_pdf']) ? 'Có' : '-' ?></td>
                            <td>
                                <a class="btn btn-muted table-action-btn" href="<?= e(base_url('supplier-products/' . $row['id'] . '/media')) ?>">Chỉnh sửa</a>
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
                    <div class="muted">Chưa có dữ liệu sản phẩm để quản lý media.</div>
                </div>
                <?php endif; ?>

                <?php foreach ($rows as $row): ?>
                <article class="mobile-card">
                    <div class="mobile-card-head">
                        <div>
                            <h4 class="mobile-card-title"><?= e($row['ma_sku'] ?? '') ?></h4>
                            <div class="mobile-card-subtitle"><?= e($row['ten_san_pham_chi_tiet'] ?? '') ?></div>
                        </div>
                    </div>

                    <div class="mobile-card-grid">
                        <div class="mobile-field">
                            <span class="mobile-field-label">Nhà cung cấp</span>
                            <span class="mobile-field-value"><?= e($row['ten_ncc'] ?? '') ?></span>
                        </div>
                        <div class="mobile-field">
                            <span class="mobile-field-label">Ảnh</span>
                            <span class="mobile-field-value"><?= !empty($row['so_anh']) ? 'Có' : '-' ?></span>
                        </div>
                        <div class="mobile-field">
                            <span class="mobile-field-label">Video</span>
                            <span class="mobile-field-value"><?= !empty($row['so_video']) ? 'Có' : '-' ?></span>
                        </div>
                        <div class="mobile-field">
                            <span class="mobile-field-label">PDF</span>
                            <span class="mobile-field-value"><?= !empty($row['so_pdf']) ? 'Có' : '-' ?></span>
                        </div>
                    </div>

                    <div class="mobile-card-actions">
                        <a class="btn btn-muted" href="<?= e(base_url('supplier-products/' . $row['id'] . '/media')) ?>">Chỉnh sửa</a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php if (($pagination['last_page'] ?? 1) > 1): ?>
    <?php
        $currentPage = (int) $pagination['page'];
        $lastPage = (int) $pagination['last_page'];
        $startPage = max(1, min($currentPage - 1, $lastPage - 2));
        $endPage = min($lastPage, $startPage + 2);
        $startPage = max(1, $endPage - 2);
        $buildLink = static function (int $page) use ($keyword): string {
            return base_url('supplier-products/media?page=' . $page . ($keyword !== '' ? '&keyword=' . urlencode($keyword) : ''));
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
<?php endif; ?>
