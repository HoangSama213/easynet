<?php

namespace App\Services;

use PDO;

class AuthService
{
    public function __construct(private PDO $pdo)
    {
    }

    public function authenticate(string $email, string $password): array
    {
        $statement = $this->pdo->prepare(
            'SELECT id, ho_ten, email, password, role, chuc_vu, trang_thai
             FROM users
             WHERE email = :email
             LIMIT 1'
        );
        $statement->execute(['email' => $email]);
        $user = $statement->fetch();

        if (!$user) {
            return [
                'status' => 'invalid_credentials',
                'user' => null,
            ];
        }

        if (($user['trang_thai'] ?? '') === 'khoa') {
            return [
                'status' => 'locked',
                'user' => $user,
            ];
        }

        if (!password_verify($password, (string) $user['password'])) {
            return [
                'status' => 'invalid_credentials',
                'user' => $user,
            ];
        }

        return [
            'status' => 'success',
            'user' => $user,
        ];
    }

    public function updateLastLogin(int $userId): void
    {
        $statement = $this->pdo->prepare(
            'UPDATE users
             SET lan_dang_nhap_cuoi = CURRENT_TIMESTAMP
             WHERE id = :id'
        );
        $statement->execute(['id' => $userId]);
    }
}
