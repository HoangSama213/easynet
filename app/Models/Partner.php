<?php

namespace App\Models;

use App\Core\App;
use App\Core\Database;

class Partner
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
            $where = 'WHERE linh_vuc LIKE :keyword
                OR COALESCE(doi_tac_tieu_bieu, "") LIKE :keyword
                OR COALESCE(ghi_chu, "") LIKE :keyword';
        }

        $rows = $this->database->query(
            'SELECT id, linh_vuc, doi_tac_tieu_bieu, ghi_chu
             FROM doi_tac
             ' . $where . '
             ORDER BY id ASC',
            $params
        );

        $items = [];
        foreach ($rows as $row) {
            $items[] = [
                'linh_vuc' => $row['linh_vuc'],
                'details' => [[
                    'id' => (int) $row['id'],
                    'doi_tac_tieu_bieu' => $row['doi_tac_tieu_bieu'],
                    'ghi_chu' => $row['ghi_chu'],
                ]],
            ];
        }

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
            'SELECT id, linh_vuc, doi_tac_tieu_bieu, ghi_chu
             FROM doi_tac
             WHERE id = :id
             LIMIT 1',
            ['id' => $id]
        );
    }

    public function create(array $data): bool
    {
        return $this->database->execute(
            'INSERT INTO doi_tac (linh_vuc, doi_tac_tieu_bieu, ghi_chu)
             VALUES (:linh_vuc, :doi_tac_tieu_bieu, :ghi_chu)',
            $data
        );
    }

    public function update(int $id, array $data): bool
    {
        $data['id'] = $id;

        return $this->database->execute(
            'UPDATE doi_tac
             SET linh_vuc = :linh_vuc,
                 doi_tac_tieu_bieu = :doi_tac_tieu_bieu,
                 ghi_chu = :ghi_chu
             WHERE id = :id',
            $data
        );
    }

    public function delete(int $id): bool
    {
        return $this->database->execute(
            'DELETE FROM doi_tac WHERE id = :id',
            ['id' => $id]
        );
    }

    public function fieldOptions(): array
    {
        $rows = $this->database->query(
            'SELECT DISTINCT linh_vuc
             FROM doi_tac
             WHERE linh_vuc IS NOT NULL AND TRIM(linh_vuc) <> ""
             ORDER BY linh_vuc ASC'
        );

        return array_map(
            static fn(array $row): string => (string) $row['linh_vuc'],
            $rows
        );
    }
}
