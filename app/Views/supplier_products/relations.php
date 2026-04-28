<?php
$selectedProduct = $selectedProduct ?? null;
$relationData = $relationData ?? ['cross_sell' => [], 'up_sell' => []];
$pagination = $pagination ?? ['page' => 1, 'last_page' => 1];
$keyword = $keyword ?? '';
$detailOptions = $detailOptions ?? [];
$oldCrossSell = $old['cross_sell_items'] ?? ($relationData['cross_sell'] ?? []);
$oldUpSell = $old['up_sell_items'] ?? ($relationData['up_sell'] ?? []);

if ($selectedProduct && empty($oldCrossSell)) {
    $oldCrossSell = [['target_chi_tiet_id' => '', 'relation_score' => 1, 'note' => '']];
}

if ($selectedProduct && empty($oldUpSell)) {
    $oldUpSell = [['target_chi_tiet_id' => '', 'relation_score' => 1, 'note' => '']];
}
?>

<?php if (!$selectedProduct): ?>
<section class="supplier-page">
    <div class="supplier-page-head">
        <div>
            <h1 class="page-title">Quản lý Cross-sell / Up-sell</h1>
        </div>
    </div>

    <section class="supplier-table-card">
        <div class="desktop-list">
            <div class="table-wrap">
                <table class="product-table product-table-modern">
                    <colgroup>
                        <col style="width: 14%;">
                        <col style="width: 30%;">
                        <col style="width: 22%;">
                        <col style="width: 12%;">
                        <col style="width: 12%;">
                        <col style="width: 10%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Mã SKU</th>
                            <th>Tên sản phẩm</th>
                            <th>Nhà cung cấp</th>
                            <th>Cross-sell</th>
                            <th>Up-sell</th>
                            <th>Chỉnh sửa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($rows)): ?>
                        <tr>
                            <td colspan="6" class="muted">Chưa có dữ liệu sản phẩm để quản lý quan hệ.</td>
                        </tr>
                        <?php endif; ?>
                        <?php foreach (($rows ?? []) as $row): ?>
                        <tr>
                            <td><strong><?= e($row['ma_sku'] ?? '') ?></strong></td>
                            <td><?= e($row['ten_san_pham_chi_tiet'] ?? '') ?></td>
                            <td><?= e($row['ten_ncc'] ?? '') ?></td>
                            <td><?= e((string) ($row['so_cross_sell'] ?? 0)) ?></td>
                            <td><?= e((string) ($row['so_up_sell'] ?? 0)) ?></td>
                            <td>
                                <a class="btn btn-muted table-action-btn" href="<?= e(base_url('supplier-products/' . $row['id'] . '/relations')) ?>">Mở</a>
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
                    <div class="muted">Chưa có dữ liệu sản phẩm để quản lý quan hệ.</div>
                </div>
                <?php endif; ?>
                <?php foreach (($rows ?? []) as $row): ?>
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
                            <span class="mobile-field-label">Cross-sell</span>
                            <span class="mobile-field-value"><?= e((string) ($row['so_cross_sell'] ?? 0)) ?></span>
                        </div>
                        <div class="mobile-field">
                            <span class="mobile-field-label">Up-sell</span>
                            <span class="mobile-field-value"><?= e((string) ($row['so_up_sell'] ?? 0)) ?></span>
                        </div>
                    </div>
                    <div class="mobile-card-actions">
                        <a class="btn btn-muted" href="<?= e(base_url('supplier-products/' . $row['id'] . '/relations')) ?>">Mở để chỉnh sửa</a>
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
            return base_url('supplier-products/relations?page=' . $page . ($keyword !== '' ? '&keyword=' . urlencode($keyword) : ''));
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
<?php else: ?>
<section class="supplier-page">
    <div class="supplier-page-head">
        <div>
            <h1 class="page-title">Chỉnh sửa Cross-sell / Up-sell</h1>
            <div class="muted" style="margin-top: 6px;"><?= e($selectedProduct['ma_sku'] ?? '') ?> · <?= e($selectedProduct['ten_san_pham_chi_tiet'] ?? '') ?> · <?= e($selectedProduct['ten_ncc'] ?? '') ?></div>
        </div>
        <div class="stack">
            <a class="btn btn-muted inline-action" href="<?= e(base_url('supplier-products/' . ($selectedProduct['id'] ?? ''))) ?>">Xem sản phẩm</a>
        </div>
    </div>

    <section class="card">
        <form method="POST" action="<?= e(base_url('supplier-products/' . $selectedProduct['id'] . '/relations/update')) ?>">
            <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">

            <div class="grid cols-2">
                <section class="nested-card" style="padding: 16px;">
                    <div class="stack section-head">
                        <h3 style="margin: 0;">Cross-sell</h3>
                    </div>
                    <?php if (isset($errors['cross_sell_items'])): ?><small class="field-error"><?= e($errors['cross_sell_items']) ?></small><?php endif; ?>
                    <div id="cross-sell-items" class="stack" style="flex-direction: column; gap: 12px;">
                        <?php foreach ($oldCrossSell as $index => $item): ?>
                        <div class="nested-card relation-item-row" style="padding: 14px;">
                            <div>
                                <label>Sản phẩm gợi ý bán kèm</label>
                                <select name="cross_sell_items[<?= e((string) $index) ?>][target_chi_tiet_id]">
                                    <option value="">Chọn sản phẩm chi tiết</option>
                                    <?php foreach ($detailOptions as $detailOption): ?>
                                    <option
                                        value="<?= e((string) $detailOption['id']) ?>"
                                        <?= (string) ($item['target_chi_tiet_id'] ?? '') === (string) $detailOption['id'] ? 'selected' : '' ?>>
                                        <?= e($detailOption['ten_chi_tiet'] ?? '') ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="grid cols-2">
                                <div>
                                    <label>Điểm ưu tiên</label>
                                    <input type="number" min="1" name="cross_sell_items[<?= e((string) $index) ?>][relation_score]" value="<?= e((string) ($item['relation_score'] ?? 1)) ?>">
                                </div>
                                <div>
                                    <label>Ghi chú</label>
                                    <input type="text" name="cross_sell_items[<?= e((string) $index) ?>][note]" value="<?= e((string) ($item['note'] ?? '')) ?>">
                                </div>
                            </div>
                            <button type="button" class="btn btn-muted inline-action relation-remove-item" style="margin-top: 12px;">Xóa dòng</button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <section class="nested-card" style="padding: 16px;">
                    <div class="stack section-head">
                        <h3 style="margin: 0;">Up-sell</h3>
                    </div>
                    <?php if (isset($errors['up_sell_items'])): ?><small class="field-error"><?= e($errors['up_sell_items']) ?></small><?php endif; ?>
                    <div id="up-sell-items" class="stack" style="flex-direction: column; gap: 12px;">
                        <?php foreach ($oldUpSell as $index => $item): ?>
                        <div class="nested-card relation-item-row" style="padding: 14px;">
                            <div>
                                <label>Sản phẩm nâng cấp gợi ý</label>
                                <select name="up_sell_items[<?= e((string) $index) ?>][target_chi_tiet_id]">
                                    <option value="">Chọn sản phẩm chi tiết</option>
                                    <?php foreach ($detailOptions as $detailOption): ?>
                                    <option
                                        value="<?= e((string) $detailOption['id']) ?>"
                                        <?= (string) ($item['target_chi_tiet_id'] ?? '') === (string) $detailOption['id'] ? 'selected' : '' ?>>
                                        <?= e($detailOption['ten_chi_tiet'] ?? '') ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="grid cols-2">
                                <div>
                                    <label>Điểm ưu tiên</label>
                                    <input type="number" min="1" name="up_sell_items[<?= e((string) $index) ?>][relation_score]" value="<?= e((string) ($item['relation_score'] ?? 1)) ?>">
                                </div>
                                <div>
                                    <label>Ghi chú</label>
                                    <input type="text" name="up_sell_items[<?= e((string) $index) ?>][note]" value="<?= e((string) ($item['note'] ?? '')) ?>">
                                </div>
                            </div>
                            <button type="button" class="btn btn-muted inline-action relation-remove-item" style="margin-top: 12px;">Xóa dòng</button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            </div>

            <div class="action-row" style="margin-top: 16px;">
                <div class="action-group action-group-left">
                    <button type="submit" class="inline-action">Lưu quan hệ</button>
                </div>
                <div class="action-group action-group-right">
                    <a class="btn btn-muted inline-action" href="<?= e(base_url('supplier-products/relations')) ?>">Quay về danh sách</a>
                </div>
            </div>
        </form>
    </section>
</section>

<template id="relation-item-template">
    <div class="nested-card relation-item-row" style="padding: 14px;">
        <div>
            <label>Sản phẩm chi tiết</label>
            <select data-name="target_chi_tiet_id">
                <option value="">Chọn sản phẩm chi tiết</option>
                <?php foreach ($detailOptions as $detailOption): ?>
                <option value="<?= e((string) $detailOption['id']) ?>"><?= e($detailOption['ten_chi_tiet'] ?? '') ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="grid cols-2">
            <div>
                <label>Điểm ưu tiên</label>
                <input type="number" min="1" value="1" data-name="relation_score">
            </div>
            <div>
                <label>Ghi chú</label>
                <input type="text" data-name="note">
            </div>
        </div>
        <button type="button" class="btn btn-muted inline-action relation-remove-item" style="margin-top: 12px;">Xóa dòng</button>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const bindIndexedList = ({ containerId, addButtonId, namePrefix }) => {
        const container = document.getElementById(containerId);
        const addButton = document.getElementById(addButtonId);
        const template = document.getElementById('relation-item-template');

        if (!container || !addButton || !template) {
            return;
        }

        const syncNames = () => {
            container.querySelectorAll('.relation-item-row').forEach((row, index) => {
                row.querySelectorAll('[data-name]').forEach((field) => {
                    field.name = `${namePrefix}[${index}][${field.getAttribute('data-name')}]`;
                });
            });
        };

        const ensureOneRow = () => {
            if (container.querySelectorAll('.relation-item-row').length === 0) {
                container.appendChild(template.content.firstElementChild.cloneNode(true));
                syncNames();
            }
        };

        addButton.addEventListener('click', () => {
            container.appendChild(template.content.firstElementChild.cloneNode(true));
            syncNames();
        });

        container.addEventListener('click', (event) => {
            const button = event.target.closest('.relation-remove-item');
            if (!button) {
                return;
            }

            const row = button.closest('.relation-item-row');
            if (row) {
                row.remove();
                ensureOneRow();
                syncNames();
            }
        });

        syncNames();
    };

    bindIndexedList({
        containerId: 'cross-sell-items',
        addButtonId: 'add-cross-sell-item',
        namePrefix: 'cross_sell_items'
    });

    bindIndexedList({
        containerId: 'up-sell-items',
        addButtonId: 'add-up-sell-item',
        namePrefix: 'up_sell_items'
    });
});
</script>
<?php endif; ?>
