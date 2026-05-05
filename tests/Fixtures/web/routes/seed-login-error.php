<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';

$_SESSION['login_error'] = 'Email hoặc mật khẩu không đúng';
$_SESSION['login_old_email'] = 'demo@easynet.vn';

echo 'OK';
