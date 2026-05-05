<?php

declare(strict_types=1);

use App\Core\App;
use App\Core\Request;
use App\Core\Router;

require dirname(__DIR__, 2) . '/vendor/autoload.php';
require dirname(__DIR__, 2) . '/app/Helpers/helpers.php';
require dirname(__DIR__, 2) . '/middleware/auth.php';

App::setConfig([
    'app' => require dirname(__DIR__, 2) . '/config/app.php',
    'database' => [],
]);

$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/login';
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['SCRIPT_NAME'] = '/index.php';

$request = new Request();
$router = new Router();

ob_start();
require dirname(__DIR__, 2) . '/routes/web.php';
$router->dispatch($request);
$output = ob_get_clean();

echo json_encode([
    'status' => http_response_code(),
    'contains_login_title' => str_contains($output, 'Đăng nhập'),
    'contains_demo_account' => str_contains($output, 'Tài khoản demo'),
], JSON_UNESCAPED_UNICODE);
