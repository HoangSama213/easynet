<?php

declare(strict_types=1);

use App\Core\App;
use App\Core\Database;

require dirname(__DIR__, 3) . '/vendor/autoload.php';
require dirname(__DIR__, 3) . '/app/Helpers/helpers.php';
require dirname(__DIR__, 3) . '/middleware/auth.php';

date_default_timezone_set('Asia/Ho_Chi_Minh');

if (!function_exists('fixture_base_url')) {
    function fixture_base_url(): string
    {
        $scheme = 'http';
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            $scheme = 'https';
        }

        return $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? '127.0.0.1');
    }
}

$appConfig = [
    'name' => 'EasyNet Data',
    'base_url' => fixture_base_url(),
    'timezone' => 'Asia/Ho_Chi_Minh',
    'session' => 'easynet_session',
];

App::setConfig([
    'app' => $appConfig,
    'database' => [],
]);

$pdo = new PDO('sqlite::memory:');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$pdo->exec(
    'CREATE TABLE users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        ho_ten TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        role TEXT NOT NULL DEFAULT "viewer",
        chuc_vu TEXT DEFAULT NULL,
        trang_thai TEXT NOT NULL DEFAULT "hoat_dong",
        ngay_tao TEXT DEFAULT CURRENT_TIMESTAMP,
        lan_dang_nhap_cuoi TEXT DEFAULT NULL
    )'
);

$statement = $pdo->prepare(
    'INSERT INTO users (ho_ten, email, password, role, chuc_vu, trang_thai)
     VALUES (:ho_ten, :email, :password, :role, :chuc_vu, :trang_thai)'
);
$statement->execute([
    'ho_ten' => 'Admin',
    'email' => 'admin@easynet.vn',
    'password' => password_hash('Admin@123', PASSWORD_BCRYPT),
    'role' => 'editor',
    'chuc_vu' => 'Quản trị viên',
    'trang_thai' => 'hoat_dong',
]);

$statement->execute([
    'ho_ten' => 'Locked User',
    'email' => 'locked@easynet.vn',
    'password' => password_hash('Locked@123', PASSWORD_BCRYPT),
    'role' => 'viewer',
    'chuc_vu' => 'Nhân viên',
    'trang_thai' => 'khoa',
]);

App::set('db', new Database($pdo));
