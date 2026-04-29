<?php

declare(strict_types=1);

require_once __DIR__ . '/middleware/auth.php';

$message = (string) ($_SESSION['auth_error'] ?? 'Bạn không có quyền truy cập');
unset($_SESSION['auth_error']);

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403</title>
    <?php
    $themeJsFile = __DIR__ . '/public/assets/js/theme.js';
    $themeJsVersion = is_file($themeJsFile) ? (string) filemtime($themeJsFile) : '1';
    ?>
    <script src="<?= e('public/assets/js/theme.js?v=' . $themeJsVersion) ?>"></script>
    <link rel="stylesheet" href="public/assets/css/auth-pages.css">
</head>
<body class="auth-page">
    <button type="button" class="auth-theme-toggle" data-theme-toggle aria-label="Chuyển chế độ sáng tối">
        <span class="theme-toggle-icon" data-theme-icon>🌙</span>
    </button>
    <div class="forbidden-shell">
        <div class="forbidden-card">
            <h1 class="forbidden-title">403</h1>
            <p class="forbidden-message"><?= e($message) ?></p>
            <a class="auth-link" href="<?= e(project_url()) ?>">Quay lại hệ thống</a>
        </div>
    </div>
</body>
</html>
