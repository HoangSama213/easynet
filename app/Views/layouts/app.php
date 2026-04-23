<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? config('app.name')) ?></title>
    <?php
    $cssFile = dirname(__DIR__, 3) . '/public/assets/css/app.css';
    $jsFile = dirname(__DIR__, 3) . '/public/assets/js/app.js';
    $cssVersion = is_file($cssFile) ? (string) filemtime($cssFile) : '1';
    $jsVersion = is_file($jsFile) ? (string) filemtime($jsFile) : '1';
    ?>
    <link rel="stylesheet" href="<?= e(asset('assets/css/app.css') . '?v=' . $cssVersion) ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        referrerpolicy="no-referrer">
    <script src="<?= e(asset('assets/js/app.js') . '?v=' . $jsVersion) ?>" defer></script>
</head>

<body>
    <?php $requestUri = $_SERVER['REQUEST_URI'] ?? ''; ?>

    <div class="app-shell">
        <div class="nav-backdrop" data-nav-backdrop></div>

        <aside class="sidebar" data-sidebar>
            <div class="sidebar-head">
                <div class="logo-box">
                    <?php if (is_file(dirname(__DIR__, 3) . '/public/logo_easynet.svg')): ?>
                    <img src="<?= e(asset('logo_easynet.svg')) ?>" alt="EasyNet">
                    <?php else: ?>
                    <span class="logo-fallback">EasyNet</span>
                    <?php endif; ?>
                </div>

                <button type="button" class="burger-btn burger-close" data-nav-close aria-label="Đóng menu">
                    <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                </button>
            </div>

            <nav class="nav-list">
                <a class="nav-item <?= !str_contains($requestUri, '/items') && !str_contains($requestUri, '/supplier-products') && !str_contains($requestUri, '/co-dien-dien-nhe') && !str_contains($requestUri, '/ha-tang-ict') && !str_contains($requestUri, '/smart-solution') && !str_contains($requestUri, '/doi-tac') && !str_contains($requestUri, '/khach-hang') ? 'active' : '' ?>"
                    href="<?= e(base_url()) ?>">Tổng quan</a>
                <a class="nav-item <?= str_contains($requestUri, '/items') ? 'active' : '' ?>"
                    href="<?= e(base_url('items')) ?>">Ngành</a>
                <a class="nav-item <?= str_contains($requestUri, '/co-dien-dien-nhe') ? 'active' : '' ?>"
                    href="<?= e(base_url('co-dien-dien-nhe')) ?>">Cơ điện điện nhẹ</a>
                <a class="nav-item <?= str_contains($requestUri, '/ha-tang-ict') ? 'active' : '' ?>"
                    href="<?= e(base_url('ha-tang-ict')) ?>">Hạ tầng ICT</a>
                <a class="nav-item <?= str_contains($requestUri, '/smart-solution') ? 'active' : '' ?>"
                    href="<?= e(base_url('smart-solution')) ?>">Smart Solution</a>
                <a class="nav-item <?= str_contains($requestUri, '/doi-tac') ? 'active' : '' ?>"
                    href="<?= e(base_url('doi-tac')) ?>">Đối tác</a>
                <a class="nav-item <?= str_contains($requestUri, '/khach-hang') ? 'active' : '' ?>"
                    href="<?= e(base_url('khach-hang')) ?>">Khách hàng</a>
                <a class="nav-item <?= str_contains($requestUri, '/supplier-products') ? 'active' : '' ?>"
                    href="<?= e(base_url('supplier-products')) ?>">Sản phẩm</a>
            </nav>
        </aside>

        <main class="content">
            <div class="page-shell">
                <div class="topbar">
                    <button type="button" class="burger-btn" data-nav-toggle aria-label="Mở menu">
                        <i class="fa-solid fa-bars" aria-hidden="true"></i>
                    </button>

                    <div class="topbar-actions">
                        <?php if (!empty($topbarButton['label']) && !empty($topbarButton['url'])): ?>
                        <a class="btn <?= ($topbarButton['label'] ?? '') === '+' ? 'btn-icon' : '' ?>"
                            href="<?= e($topbarButton['url']) ?>"
                            aria-label="Thêm mới"><?= e($topbarButton['label']) ?></a>
                        <?php endif; ?>

                        <?php if (session_get('user')): ?>
                        <form method="POST" action="<?= e(base_url('logout')) ?>">
                            <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                            <button type="submit" class="btn-secondary">Đăng xuất</button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>

                <?php foreach (($_SESSION['_flash'] ?? []) as $type => $message): ?>
                <div class="flash <?= e($type) ?>"><?= e((string) $message) ?></div>
                <?php endforeach; ?>
                <?php unset($_SESSION['_flash']); ?>

                <?= $content ?>
            </div>
        </main>
    </div>
</body>

</html>
