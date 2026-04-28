<?php

namespace App\Core;

use PDO;

class Database
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function pdo(): PDO
    {
        return $this->pdo;
    }

    public function query(string $sql, array $params = []): array
    {
        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll();
    }

    public function first(string $sql, array $params = []): ?array
    {
        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);
        $row = $statement->fetch();

        return $row ?: null;
    }

    public function execute(string $sql, array $params = []): bool
    {
        $statement = $this->pdo->prepare($sql);

        return $statement->execute($params);
    }

    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }
}
