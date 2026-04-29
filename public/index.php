<?php

declare(strict_types=1);

use App\Core\App;
use App\Core\Database;
use App\Core\Request;
use App\Core\Router;

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax',
]);

session_name((string) require dirname(__DIR__) . '/config/session_name.php');
session_start();

require dirname(__DIR__) . '/app/Helpers/helpers.php';
require dirname(__DIR__) . '/middleware/auth.php';

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relative = substr($class, strlen($prefix));
    $path = dirname(__DIR__) . '/app/' . str_replace('\\', '/', $relative) . '.php';

    if (is_file($path)) {
        require $path;
    }
});

$config = [
    'app' => require dirname(__DIR__) . '/config/app.php',
    'database' => require dirname(__DIR__) . '/config/database.php',
];

App::setConfig($config);
date_default_timezone_set((string) config('app.timezone', 'Asia/Ho_Chi_Minh'));

try {
    App::set('db', new Database(config('database')));
} catch (Throwable $exception) {
    http_response_code(500);
    exit('Không thể kết nối cơ sở dữ liệu. Vui lòng kiểm tra cấu hình trong config/database.php');
}

$request = new Request();
App::set('request', $request);

if (!in_array($request->uri(), ['/login', '/logout'], true)) {
    require_auth();
}

$router = new Router();
require dirname(__DIR__) . '/routes/web.php';
$router->dispatch($request);
