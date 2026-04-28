<?php
$loginError = (string) ($loginError ?? '');
$oldEmail = (string) ($oldEmail ?? '');
$logoPath = 'public/logo_easynet.svg';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Đăng nhập') ?></title>
    <link rel="stylesheet" href="<?= e(asset('assets/css/auth-pages.css')) ?>">
    <script src="<?= e(asset('assets/js/login.js')) ?>" defer></script>
</head>
<body class="auth-page">
    <div class="login-shell">
        <section class="login-brand" aria-hidden="true">
            <div class="login-brand-inner">
                <div class="login-logo-box">
                    <?php if (is_file(dirname(__DIR__, 3) . '/public/logo_easynet.svg')): ?>
                        <img src="<?= e(base_url($logoPath)) ?>" alt="EASYNET">
                    <?php else: ?>
                        <div class="login-logo-fallback">E</div>
                    <?php endif; ?>
                </div>
                <h1 class="login-logo-text">EASYNET</h1>
                <p class="login-tagline">Hệ thống quản lý dữ liệu sản phẩm</p>
            </div>
        </section>

        <section class="login-panel">
            <div class="login-form-wrap">
                <h2 class="login-title">Đăng nhập</h2>

                <?php if ($loginError !== ''): ?>
                    <div class="login-alert"><?= e($loginError) ?></div>
                <?php endif; ?>

                <form method="POST" action="<?= e(base_url('login')) ?>">
                    <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">

                    <div class="login-field">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" placeholder="Email" value="<?= e($oldEmail) ?>" required>
                    </div>

                    <div class="login-field">
                        <label for="password">Mật khẩu</label>
                        <input id="password" type="password" name="password" placeholder="Mật khẩu" required>
                    </div>

                    <label class="login-toggle" for="show-password">
                        <input id="show-password" type="checkbox">
                        <span>Hiển thị mật khẩu</span>
                    </label>

                    <button type="submit" class="login-submit">Đăng nhập</button>
                </form>
            </div>
        </section>
    </div>
</body>
</html>
