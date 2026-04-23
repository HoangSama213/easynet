<?php

namespace App\Models;

use App\Core\App;
use App\Core\Database;

class Customer
{
    private Database $database;

    public function __construct()
    {
        $this->database = App::get('db');
    }

    public function paginate(string $keyword = '', int $page = 1, int $perPage = 10): array
    {
        $page = max(1, $page);
        $perPage = max(1, $perPage);
        $params = [];
        $where = '';

        if ($keyword !== '') {
            $params['keyword'] = '%' . $keyword . '%';
            $where = 'WHERE COALESCE(phan_loai, "") LIKE :keyword
                OR COALESCE(phan_loai_khach_hang, "") LIKE :keyword
                OR COALESCE(khach_hang_tieu_bieu, "") LIKE :keyword
                OR COALESCE(ghi_chu, "") LIKE :keyword';
        }

        $rows = $this->database->query(
            'SELECT id, phan_loai, phan_loai_khach_hang, khach_hang_tieu_bieu, ghi_chu
             FROM khach_hang
             ' . $where . '
             ORDER BY id ASC',
            $params
        );

        $grouped = [];
        foreach ($rows as $row) {
            $key = $row['phan_loai'] ?: 'Chưa phân loại';

            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'phan_loai' => $key,
                    'details' => [],
                ];
            }

            $grouped[$key]['details'][] = [
                'id' => (int) $row['id'],
                'phan_loai_khach_hang' => $row['phan_loai_khach_hang'],
                'khach_hang_tieu_bieu' => $row['khach_hang_tieu_bieu'],
                'ghi_chu' => $row['ghi_chu'],
            ];
        }

        $items = array_values($grouped);
        $total = count($items);
        $offset = ($page - 1) * $perPage;

        return [
            'items' => array_slice($items, $offset, $perPage),
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'last_page' => max(1, (int) ceil($total / $perPage)),
        ];
    }

    public function find(int $id): ?array
    {
        return $this->database->first(
            'SELECT id, phan_loai, phan_loai_khach_hang, khach_hang_tieu_bieu, ghi_chu
             FROM khach_hang
             WHERE id = :id
             LIMIT 1',
            ['id' => $id]
        );
    }

    public function create(array $data): bool
    {
        return $this->database->execute(
            'INSERT INTO khach_hang (phan_loai, phan_loai_khach_hang, khach_hang_tieu_bieu, ghi_chu)
             VALUES (:phan_loai, :phan_loai_khach_hang, :khach_hang_tieu_bieu, :ghi_chu)',
            $data
        );
    }

    public function update(int $id, array $data): bool
    {
        $data['id'] = $id;

        return $this->database->execute(
            'UPDATE khach_hang
             SET phan_loai = :phan_loai,
                 phan_loai_khach_hang = :phan_loai_khach_hang,
                 khach_hang_tieu_bieu = :khach_hang_tieu_bieu,
                 ghi_chu = :ghi_chu
             WHERE id = :id',
            $data
        );
    }

    public function delete(int $id): bool
    {
        return $this->database->execute(
            'DELETE FROM khach_hang WHERE id = :id',
            ['id' => $id]
        );
    }

    public function categoryOptions(): array
    {
        $rows = $this->database->query(
            'SELECT DISTINCT phan_loai
             FROM khach_hang
             WHERE phan_loai IS NOT NULL AND TRIM(phan_loai) <> ""
             ORDER BY phan_loai ASC'
        );

        return array_map(
            static fn(array $row): string => (string) $row['phan_loai'],
            $rows
        );
    }
}
