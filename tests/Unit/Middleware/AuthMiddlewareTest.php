<?php

declare(strict_types=1);

namespace Tests\Unit\Middleware;

use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__, 3) . '/middleware/auth.php';

class AuthMiddlewareTest extends TestCase
{
    protected function setUp(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION = [];
    }

    public function testAuthUserSyncsLegacySessionShape(): void
    {
        $_SESSION['id'] = 7;
        $_SESSION['ho_ten'] = 'Nguyễn Văn A';
        $_SESSION['email'] = 'viewer@easynet.vn';
        $_SESSION['role'] = 'viewer';
        $_SESSION['chuc_vu'] = 'Nhân viên';

        $user = auth_user();

        $this->assertSame(7, $user['id']);
        $this->assertSame('viewer', $user['role']);
        $this->assertSame('Nguyễn Văn A', $_SESSION['user']['ho_ten']);
        $this->assertSame('viewer@easynet.vn', $_SESSION['user']['email']);
    }

    public function testRoleHelpersReturnExpectedValues(): void
    {
        $_SESSION['user'] = [
            'id' => 11,
            'ho_ten' => 'Editor',
            'email' => 'editor@easynet.vn',
            'role' => 'editor',
            'chuc_vu' => 'Biên tập viên',
        ];

        $this->assertTrue(is_editor());
        $this->assertFalse(is_viewer());

        $_SESSION['user']['role'] = 'viewer';

        $this->assertFalse(is_editor());
        $this->assertTrue(is_viewer());
    }
}
