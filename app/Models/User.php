<?php

namespace App\Models;

use App\Core\App;
use App\Core\Database;

class User
{
    private Database $database;

    public function __construct()
    {
        $this->database = App::get('db');
    }

    public function findByEmail(string $email): ?array
    {
        return $this->database->first(
            'SELECT id, ho_ten, email, password, role, chuc_vu, trang_thai, ngay_tao, lan_dang_nhap_cuoi
             FROM users
             WHERE email = :email
             LIMIT 1',
            ['email' => $email]
        );
    }

    public function updateLastLogin(int $id): bool
    {
        return $this->database->execute(
            'UPDATE users
             SET lan_dang_nhap_cuoi = NOW()
             WHERE id = :id',
            ['id' => $id]
        );
    }

    public function all(): array
    {
        return $this->database->query(
            'SELECT id, ho_ten, email, role, chuc_vu, trang_thai, ngay_tao, lan_dang_nhap_cuoi
             FROM users
             ORDER BY ngay_tao DESC, id DESC'
        );
    }
}
