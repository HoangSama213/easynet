<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\AuthService;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class AuthServiceTest extends TestCase
{
    public function testAuthenticateReturnsSuccessForValidCredentials(): void
    {
        $password = 'Admin@123';
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $statement = $this->createMock(PDOStatement::class);
        $pdo = $this->createMock(PDO::class);

        $pdo->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('FROM users'))
            ->willReturn($statement);

        $statement->expects($this->once())
            ->method('execute')
            ->with(['email' => 'admin@easynet.vn']);

        $statement->expects($this->once())
            ->method('fetch')
            ->willReturn([
                'id' => 1,
                'ho_ten' => 'Admin',
                'email' => 'admin@easynet.vn',
                'password' => $hash,
                'role' => 'editor',
                'chuc_vu' => 'Quản trị viên',
                'trang_thai' => 'hoat_dong',
            ]);

        $service = new AuthService($pdo);
        $result = $service->authenticate('admin@easynet.vn', $password);

        $this->assertSame('success', $result['status']);
        $this->assertIsArray($result['user']);
        $this->assertSame('admin@easynet.vn', $result['user']['email']);
    }

    public function testAuthenticateReturnsLockedForLockedUser(): void
    {
        $statement = $this->createMock(PDOStatement::class);
        $pdo = $this->createMock(PDO::class);

        $pdo->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('FROM users'))
            ->willReturn($statement);

        $statement->expects($this->once())
            ->method('execute')
            ->with(['email' => 'locked@easynet.vn']);

        $statement->expects($this->once())
            ->method('fetch')
            ->willReturn([
                'id' => 2,
                'ho_ten' => 'Locked User',
                'email' => 'locked@easynet.vn',
                'password' => password_hash('secret', PASSWORD_BCRYPT),
                'role' => 'viewer',
                'chuc_vu' => 'Nhân viên',
                'trang_thai' => 'khoa',
            ]);

        $service = new AuthService($pdo);
        $result = $service->authenticate('locked@easynet.vn', 'secret');

        $this->assertSame('locked', $result['status']);
        $this->assertIsArray($result['user']);
        $this->assertSame('locked@easynet.vn', $result['user']['email']);
    }
}
