<?php

declare(strict_types=1);

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

switch ($path) {
    case '/login':
        require __DIR__ . '/routes/login.php';
        break;

    case '/logout':
        require __DIR__ . '/routes/logout.php';
        break;

    case '/redirect-test':
        require __DIR__ . '/routes/redirect-test.php';
        break;

    case '/protected':
        require __DIR__ . '/routes/protected.php';
        break;

    case '/seed-auth':
        require __DIR__ . '/routes/seed-auth.php';
        break;

    case '/seed-login-error':
        require __DIR__ . '/routes/seed-login-error.php';
        break;

    default:
        http_response_code(404);
        echo 'Not Found';
        break;
}
