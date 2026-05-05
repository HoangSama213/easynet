<?php

declare(strict_types=1);

use App\Core\App;

require dirname(__DIR__, 2) . '/vendor/autoload.php';
require dirname(__DIR__, 2) . '/app/Helpers/helpers.php';
require dirname(__DIR__, 2) . '/middleware/auth.php';

App::setConfig([
    'app' => [
        'base_url' => 'http://localhost/easynet',
    ],
]);

$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['REQUEST_URI'] = '/items';

register_shutdown_function(static function (): void {
    echo json_encode([
        'status' => http_response_code(),
        'headers' => headers_list(),
        'login_error' => $_SESSION['login_error'] ?? null,
    ], JSON_UNESCAPED_UNICODE);
});

$_SESSION = [];

require_auth();
