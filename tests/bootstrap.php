<?php

declare(strict_types=1);

date_default_timezone_set('Asia/Ho_Chi_Minh');

require dirname(__DIR__) . '/vendor/autoload.php';

$_SERVER['HTTP_HOST'] = $_SERVER['HTTP_HOST'] ?? 'localhost';
$_SERVER['SCRIPT_NAME'] = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
$_SERVER['REQUEST_METHOD'] = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if (session_status() === PHP_SESSION_ACTIVE) {
    $_SESSION = [];
}
