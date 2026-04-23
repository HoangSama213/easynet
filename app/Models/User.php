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
        return $this->database->first('SELECT * FROM users WHERE email = :email LIMIT 1', [
            'email' => $email,
        ]);
    }

    public function all(): array
    {
        return $this->database->query('SELECT id, name, email, role, status, created_at FROM users ORDER BY created_at DESC');
    }
}
