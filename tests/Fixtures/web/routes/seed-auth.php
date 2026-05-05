<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';

$_SESSION['id'] = 1;
$_SESSION['ho_ten'] = 'Admin';
$_SESSION['email'] = 'admin@easynet.vn';
$_SESSION['role'] = 'editor';
$_SESSION['chuc_vu'] = 'Quản trị viên';
$_SESSION['user'] = [
    'id' => 1,
    'name' => 'Admin',
    'ho_ten' => 'Admin',
    'email' => 'admin@easynet.vn',
    'role' => 'editor',
    'chuc_vu' => 'Quản trị viên',
];

echo 'OK';
