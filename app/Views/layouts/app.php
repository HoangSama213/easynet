<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    $title = (string) ($title ?? config('app.name'));
    $topbarFilters = $topbarFilters ?? [];
    $topbarButton = $topbarButton ?? [];
    $content = (string) ($content ?? '');
    ?>
    <title><?= e($title) ?></title>
    <?php
    $cssFile = dirname(__DIR__, 3) . '/public/assets/css/app.css';
    $jsFile = dirname(__DIR__, 3) . '/public/assets/js/app.js';
    $themeJsFile = dirname(__DIR__, 3) . '/public/assets/js/theme.js';
    $cssVersion = is_file($cssFile) ? (string) filemtime($cssFile) : '1';
    $jsVersion = is_file($jsFile) ? (string) filemtime($jsFile) : '1';
    $themeJsVersion = is_file($themeJsFile) ? (string) filemtime($themeJsFile) : '1';
    $requestUri = $_SERVER['REQUEST_URI'] ?? '';
    $canManage = function_exists('is_editor') && is_editor();
    $hasTopbarFilters = !empty($topbarFilters);
    $hasTopbarButton = !empty($topbarButton['label']) && !empty($topbarButton['url']);
    $hasSessionUser = (bool) session_get('user');
    $hasDesktopTopbarContent = $hasTopbarFilters || $hasTopbarButton || $hasSessionUser;
    $thongBaoTopbar = $thongBaoTopbar ?? [
        'so_chua_doc' => 0,
        'moi' => [],
        'truoc_do' => [],
    ];

    $isProductCreate = str_contains($requestUri, '/supplier-products/create');
    $isProductHistory = str_contains($requestUri, '/supplier-products/history');
    $isProductMedia = (bool) preg_match('#/supplier-products(?:/\d+)?/media(?:/|$)#', $requestUri);
    $isProductRelations = str_contains($requestUri, '/quan-he-san-pham')
        || (bool) preg_match('#/supplier-products(?:/\d+)?/relations(?:/|$)#', $requestUri);
    $isProductList = str_contains($requestUri, '/supplier-products')
        && !$isProductCreate
        && !$isProductHistory
        && !$isProductMedia
        && !$isProductRelations;

    $navGroups = [
        [
            'label' => 'Tổng quan',
            'url' => base_url(),
            'active' => !str_contains($requestUri, '/items')
                && !str_contains($requestUri, '/supplier-products')
                && !str_contains($requestUri, '/combos')
                && !str_contains($requestUri, '/sales')
                && !str_contains($requestUri, '/thong-bao')
                && !str_contains($requestUri, '/co-dien-dien-nhe')
                && !str_contains($requestUri, '/ha-tang-ict')
                && !str_contains($requestUri, '/smart-solution')
                && !str_contains($requestUri, '/doi-tac')
                && !str_contains($requestUri, '/khach-hang')
                && !str_contains($requestUri, '/quan-he-san-pham'),
        ],
        [
            'label' => 'Nhà cung cấp',
            'url' => base_url('items'),
            'active' => str_contains($requestUri, '/items'),
        ],
        [
            'label' => 'Danh mục',
            'children' => [
                [
                    'label' => 'Cơ điện điện nhẹ',
                    'url' => base_url('co-dien-dien-nhe'),
                    'active' => str_contains($requestUri, '/co-dien-dien-nhe'),
                ],
                [
                    'label' => 'Hạ tầng ICT',
                    'url' => base_url('ha-tang-ict'),
                    'active' => str_contains($requestUri, '/ha-tang-ict'),
                ],
                [
                    'label' => 'Smart Solution',
                    'url' => base_url('smart-solution'),
                    'active' => str_contains($requestUri, '/smart-solution'),
                ],
                [
                    'label' => 'Đối tác',
                    'url' => base_url('doi-tac'),
                    'active' => str_contains($requestUri, '/doi-tac'),
                ],
                [
                    'label' => 'Khách hàng',
                    'url' => base_url('khach-hang'),
                    'active' => str_contains($requestUri, '/khach-hang'),
                ],
            ],
        ],
        [
            'label' => 'Sản phẩm',
            'children' => array_values(array_filter([
                [
                    'label' => 'Danh sách sản phẩm',
                    'url' => base_url('supplier-products'),
                    'active' => $isProductList,
                ],
                [
                    'label' => 'Lịch sử thay đổi giá',
                    'url' => base_url('supplier-products/history'),
                    'active' => $isProductHistory,
                ],
                [
                    'label' => 'Tài nguyên media',
                    'url' => base_url('supplier-products/media'),
                    'active' => $isProductMedia,
                ],
                $canManage ? [
                    'label' => 'Thêm sản phẩm',
                    'url' => base_url('supplier-products/create'),
                    'active' => $isProductCreate,
                ] : null,
            ])),
        ],
        // Tạm ẩn menu Sales cho đến khi có yêu cầu mở lại.
        [
            'label' => 'Thông báo',
            'url' => base_url('thong-bao'),
            'active' => str_contains($requestUri, '/thong-bao'),
        ],
        // Tạm ẩn menu Combo cho đến khi có yêu cầu mở lại.
    ];

    $extraCssFiles = $extraCssFiles ?? [];
    if (is_string($extraCssFiles)) {
        $extraCssFiles = [$extraCssFiles];
    }

    $extraJsFiles = $extraJsFiles ?? [];
    if (is_string($extraJsFiles)) {
        $extraJsFiles = [$extraJsFiles];
    }
    ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="<?= e(asset('assets/js/theme.js') . '?v=' . $themeJsVersion) ?>"></script>
    <link rel="stylesheet" href="<?= e(asset('assets/css/app.css') . '?v=' . $cssVersion) ?>">
    <?php foreach ($extraCssFiles as $extraCssFile): ?>
        <?php
        $extraCssPath = dirname(__DIR__, 3) . '/public/' . ltrim((string) $extraCssFile, '/');
        $extraCssVersion = is_file($extraCssPath) ? (string) filemtime($extraCssPath) : '1';
        ?>
        <link rel="stylesheet" href="<?= e(asset((string) $extraCssFile) . '?v=' . $extraCssVersion) ?>">
    <?php endforeach; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" referrerpolicy="no-referrer">
    <script src="<?= e(asset('assets/js/app.js') . '?v=' . $jsVersion) ?>" defer></script>
    <?php foreach ($extraJsFiles as $extraJsFile): ?>
        <?php
        $extraJsPath = dirname(__DIR__, 3) . '/public/' . ltrim((string) $extraJsFile, '/');
        $extraJsVersion = is_file($extraJsPath) ? (string) filemtime($extraJsPath) : '1';
        ?>
        <script src="<?= e(asset((string) $extraJsFile) . '?v=' . $extraJsVersion) ?>" defer></script>
    <?php endforeach; ?>
</head>

<body>
    <div class="app-shell">
        <div class="nav-backdrop" data-nav-backdrop></div>

        <aside class="sidebar" data-sidebar>
            <div class="sidebar-head">
                <a class="brand" href="<?= e(base_url()) ?>">
                    <span class="brand-mark">
                        <?php if (is_file(dirname(__DIR__, 3) . '/public/logo_easynet.svg')): ?>
                            <img src="<?= e(asset('logo_easynet.svg')) ?>" alt="EasyNet">
                        <?php else: ?>
                            <span class="brand-mark-fallback">E</span>
                        <?php endif; ?>
                    </span>
                    <span class="brand-name">EASYNET</span>
                </a>

                <button type="button" class="burger-btn burger-close" data-nav-close aria-label="Đóng menu">
                    <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                </button>
            </div>

            <nav class="nav-list" aria-label="Điều hướng chính">
                <?php foreach ($navGroups as $index => $group): ?>
                    <?php if (!empty($group['children'])): ?>
                        <?php
                        $groupId = 'nav-group-' . $index;
                        $groupActive = false;
                        foreach ($group['children'] as $child) {
                            if (!empty($child['active'])) {
                                $groupActive = true;
                                break;
                            }
                        }
                        ?>
                        <div class="nav-group">
                            <button
                                type="button"
                                class="nav-group-toggle <?= $groupActive ? 'active' : '' ?>"
                                data-nav-group-toggle
                                data-target="<?= e($groupId) ?>"
                                aria-expanded="false">
                                <span><?= e($group['label']) ?></span>
                                <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                            </button>

                            <div
                                id="<?= e($groupId) ?>"
                                class="nav-group-body"
                                data-nav-group-body
                                hidden>
                                <?php foreach ($group['children'] as $child): ?>
                                    <a class="nav-item nav-subitem <?= !empty($child['active']) ? 'active' : '' ?>" href="<?= e($child['url']) ?>">
                                        <?= e($child['label']) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <a class="nav-item <?= !empty($group['active']) ? 'active' : '' ?>" href="<?= e($group['url']) ?>">
                            <?= e($group['label']) ?>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </nav>

        </aside>

        <main class="content">
            <div class="page-shell">
                <header class="topbar <?= $hasDesktopTopbarContent ? '' : 'topbar-mobile-only' ?>">
                    <button type="button" class="burger-btn" data-nav-toggle aria-label="Mở menu">
                        <i class="fa-solid fa-bars" aria-hidden="true"></i>
                    </button>

                    <div class="topbar-main">
                        <?php if ($hasTopbarFilters): ?>
                            <form method="GET" action="<?= e($topbarFilters['action'] ?? base_url()) ?>" class="topbar-filter-form">
                                <label class="topbar-search" for="topbar-keyword">
                                    <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                                    <input
                                        id="topbar-keyword"
                                        type="text"
                                        name="keyword"
                                        value="<?= e((string) ($topbarFilters['keyword'] ?? '')) ?>"
                                        placeholder="<?= e((string) ($topbarFilters['placeholder'] ?? 'Tìm kiếm')) ?>">
                                </label>

                                <?php if (!empty($topbarFilters['categories'])): ?>
                                    <select name="category_id" aria-label="Lọc theo hạng mục" class="topbar-select">
                                        <option value="0">Tất cả hạng mục</option>
                                        <?php foreach (($topbarFilters['categories'] ?? []) as $category): ?>
                                            <option
                                                value="<?= e((string) $category['id']) ?>"
                                                <?= (int) ($topbarFilters['categoryId'] ?? 0) === (int) $category['id'] ? 'selected' : '' ?>>
                                                <?= e($category['ten_hang_muc']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php endif; ?>

                                <button type="submit" class="btn filter-button">Tìm kiếm</button>
                            </form>
                        <?php else: ?>
                            <div class="topbar-spacer"></div>
                        <?php endif; ?>

                        <div class="topbar-actions">
                            <button type="button" class="theme-toggle" data-theme-toggle aria-label="Chuyển chế độ sáng tối">
                                <span class="theme-toggle-icon" data-theme-icon>🌙</span>
                            </button>
                            <?php if ($hasSessionUser): ?>
                                <div class="notification-shell" data-notification-shell>
                                    <button type="button" class="notification-bell" data-notification-toggle aria-expanded="false" aria-label="Mở thông báo">
                                        <i class="fa-regular fa-bell" aria-hidden="true"></i>
                                        <?php if (($thongBaoTopbar['so_chua_doc'] ?? 0) > 0): ?>
                                            <span class="notification-badge"><?= e((string) $thongBaoTopbar['so_chua_doc']) ?></span>
                                        <?php endif; ?>
                                    </button>

                                    <div class="notification-dropdown" data-notification-dropdown hidden>
                                        <div class="notification-dropdown-head">
                                            <div>
                                                <strong>Thông báo</strong>
                                                <div class="muted">Cập nhật tồn kho sản phẩm</div>
                                            </div>
                                            <?php if (($thongBaoTopbar['so_chua_doc'] ?? 0) > 0): ?>
                                                <span class="notification-pill"><?= e((string) $thongBaoTopbar['so_chua_doc']) ?> chưa đọc</span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="notification-dropdown-body">
                                            <?php if (empty($thongBaoTopbar['moi']) && empty($thongBaoTopbar['truoc_do'])): ?>
                                                <div class="notification-empty">Chưa có thông báo nào.</div>
                                            <?php else: ?>
                                                <?php if (!empty($thongBaoTopbar['moi'])): ?>
                                                    <div class="notification-section">
                                                        <div class="notification-section-title">Mới</div>
                                                        <?php foreach ($thongBaoTopbar['moi'] as $item): ?>
                                                            <a class="notification-item is-unread" href="<?= e(base_url('thong-bao/xem/' . (int) ($item['id'] ?? 0) . '?redirect_to=thong-bao')) ?>">
                                                                <span class="notification-level <?= e((string) ($item['muc_do'] ?? 'info')) ?>"></span>
                                                                <span class="notification-content">
                                                                    <strong><?= e($item['tieu_de'] ?? '') ?></strong>
                                                                    <span><?= e($item['mo_ta_ngan'] ?? '') ?></span>
                                                                    <small><?= e($item['ngay_tao_hien_thi'] ?? '') ?></small>
                                                                </span>
                                                                <span class="notification-unread-dot" aria-hidden="true"></span>
                                                            </a>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if (!empty($thongBaoTopbar['truoc_do'])): ?>
                                                    <div class="notification-section">
                                                        <div class="notification-section-title">Trước đó</div>
                                                        <?php foreach ($thongBaoTopbar['truoc_do'] as $item): ?>
                                                            <a class="notification-item" href="<?= e(base_url('thong-bao')) ?>">
                                                                <span class="notification-level <?= e((string) ($item['muc_do'] ?? 'info')) ?>"></span>
                                                                <span class="notification-content">
                                                                    <strong><?= e($item['tieu_de'] ?? '') ?></strong>
                                                                    <span><?= e($item['mo_ta_ngan'] ?? '') ?></span>
                                                                    <small><?= e($item['ngay_tao_hien_thi'] ?? '') ?></small>
                                                                </span>
                                                            </a>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>

                                        <div class="notification-dropdown-actions">
                                            <?php if (($thongBaoTopbar['so_chua_doc'] ?? 0) > 0): ?>
                                                <form method="POST" action="<?= e(base_url('thong-bao/danh-dau-da-doc')) ?>" class="notification-mark-read-form">
                                                    <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                                                    <input type="hidden" name="redirect_to" value="<?= e(base_url()) ?>">
                                                    <button type="submit" class="notification-all-link notification-action-button">Đánh dấu tất cả đã đọc</button>
                                                </form>
                                            <?php endif; ?>
                                            <a class="notification-all-link" href="<?= e(base_url('thong-bao')) ?>">Xem tất cả thông báo &rarr;</a>
                                        </div>
                                    </div>
                                </div>
                                <a class="topbar-logout" href="<?= e(base_url('logout')) ?>" aria-label="Đăng xuất">
                                    <i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i>
                                    <span>Đăng xuất</span>
                                </a>
                            <?php endif; ?>

                            <?php if ($hasTopbarButton): ?>
                                <a
                                    class="btn <?= ($topbarButton['label'] ?? '') === '+' ? 'btn-icon' : '' ?>"
                                    href="<?= e($topbarButton['url']) ?>"
                                    aria-label="<?= e((string) ($topbarButton['ariaLabel'] ?? 'Đi tới chức năng')) ?>"><?= e($topbarButton['label']) ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </header>

                <?php foreach (($_SESSION['_flash'] ?? []) as $type => $message): ?>
                    <div class="flash <?= e($type) ?>"><?= e((string) $message) ?></div>
                <?php endforeach; ?>
                <?php unset($_SESSION['_flash']); ?>

                <div class="page-content">
                    <?= $content ?>
                </div>

                <footer class="app-footer">
                    <p>&copy; 2026 easynet</p>
                </footer>
            </div>
        </main>
    </div>
</body>

</html>
