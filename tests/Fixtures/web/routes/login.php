<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Core\Request;

require dirname(__DIR__) . '/bootstrap.php';

$controller = new AuthController(new Request());

if (strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $controller->login();
    return;
}

$controller->showLogin();
