<?php

namespace App\Models;

use App\Core\App;
use App\Core\Database;

class CatalogEntry
{
    private Database $database;

    public function __construct()
    {
        $this->database = App::get('db');
    }

    public function paginate(array $systems, string $keyword = '', int $page = 1, int $perPage = 10): array
    {
        $page = max(1, $page);
        $perPage = max(1, $perPage);
        $params = [];
        $where = '';
        $baseQuery = $this->buildUnionQuery($systems);

        if ($keyword !== '') {
            $params['keyword'] = '%' . $keyword . '%';
            $where = 'WHERE he_sinh_thai LIKE :keyword
                OR chi_tiet LIKE :keyword
                OR COALESCE(hang_noi_bat, "") LIKE :keyword
                OR COALESCE(san_pham, "") LIKE :keyword
                OR COALESCE(nha_phan_phoi, "") LIKE :keyword
                OR COALESCE(ghi_chu, "") LIKE :keyword';
        }

        $rows = $this->database->query(
            'SELECT *
             FROM (' . $baseQuery . ') ecosystem_items
             ' . $where . '
             ORDER BY nhom_thu_tu ASC, he_sinh_thai ASC, id ASC',
            $params
        );

        $grouped = [];
        foreach ($rows as $row) {
            $key = $row['he_sinh_thai'];

            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'he_sinh_thai' => $row['he_sinh_thai'],
                    'nhom' => $row['nhom'],
                    'nhom_thu_tu' => $row['nhom_thu_tu'],
                    'table_name' => $row['table_name'],
                    'details' => [],
                ];
            }

            $grouped[$key]['details'][] = [
                'id' => (int) $row['id'],
                'table_name' => $row['table_name'],
                'chi_tiet' => $row['chi_tiet'],
                'hang_noi_bat' => $row['hang_noi_bat'],
                'san_pham' => $row['san_pham'],
                'nha_phan_phoi' => $row['nha_phan_phoi'],
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

    public function find(string $table, int $id, array $systems): ?array
    {
        $system = $this->findSystem($systems, $table);
        if (!$system) {
            return null;
        }

        $row = $this->database->first(
            "SELECT id, chi_tiet, hang_noi_bat, san_pham, nha_phan_phoi, ghi_chu
             FROM {$table}
             WHERE id = :id
             LIMIT 1",
            ['id' => $id]
        );

        if (!$row) {
            return null;
        }

        $row['table_name'] = $table;
        $row['he_sinh_thai'] = $system['label'];
        $row['nhom'] = $system['group'];

        return $row;
    }

    public function create(string $table, array $payload, array $systems): bool
    {
        if (!$this->findSystem($systems, $table)) {
            return false;
        }

        return $this->database->execute(
            "INSERT INTO {$table} (chi_tiet, hang_noi_bat, san_pham, nha_phan_phoi, ghi_chu)
             VALUES (:chi_tiet, :hang_noi_bat, :san_pham, :nha_phan_phoi, :ghi_chu)",
            $payload
        );
    }

    public function update(string $table, int $id, array $payload, array $systems): bool
    {
        if (!$this->findSystem($systems, $table)) {
            return false;
        }

        $payload['id'] = $id;

        return $this->database->execute(
            "UPDATE {$table}
             SET chi_tiet = :chi_tiet,
                 hang_noi_bat = :hang_noi_bat,
                 san_pham = :san_pham,
                 nha_phan_phoi = :nha_phan_phoi,
                 ghi_chu = :ghi_chu
             WHERE id = :id",
            $payload
        );
    }

    public function delete(string $table, int $id, array $systems): bool
    {
        if (!$this->findSystem($systems, $table)) {
            return false;
        }

        return $this->database->execute(
            "DELETE FROM {$table} WHERE id = :id",
            ['id' => $id]
        );
    }

    public function systemOptions(array $systems): array
    {
        return array_map(static function (array $system): array {
            return [
                'table' => $system['table'],
                'group' => $system['group'],
                'label' => $system['label'],
            ];
        }, $systems);
    }

    private function findSystem(array $systems, string $table): ?array
    {
        foreach ($systems as $system) {
            if ($system['table'] === $table) {
                return $system;
            }
        }

        return null;
    }

    private function buildUnionQuery(array $systems): string
    {
        $queries = [];
        $collation = 'utf8mb4_0900_ai_ci';

        foreach ($systems as $system) {
            $queries[] = sprintf(
                "SELECT
                    id,
                    CAST('%s' AS CHAR CHARACTER SET utf8mb4) COLLATE %s AS table_name,
                    CAST('%s' AS CHAR CHARACTER SET utf8mb4) COLLATE %s AS nhom,
                    %d AS nhom_thu_tu,
                    CAST('%s' AS CHAR CHARACTER SET utf8mb4) COLLATE %s AS he_sinh_thai,
                    CONVERT(chi_tiet USING utf8mb4) COLLATE %s AS chi_tiet,
                    CONVERT(hang_noi_bat USING utf8mb4) COLLATE %s AS hang_noi_bat,
                    CONVERT(san_pham USING utf8mb4) COLLATE %s AS san_pham,
                    CONVERT(nha_phan_phoi USING utf8mb4) COLLATE %s AS nha_phan_phoi,
                    CONVERT(ghi_chu USING utf8mb4) COLLATE %s AS ghi_chu
                 FROM %s",
                addslashes($system['table']),
                $collation,
                addslashes($system['group']),
                $collation,
                (int) $system['order'],
                addslashes($system['label']),
                $collation,
                $collation,
                $collation,
                $collation,
                $collation,
                $collation,
                $system['table']
            );
        }

        if ($queries === []) {
            return "SELECT
                0 AS id,
                CAST('' AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_0900_ai_ci AS table_name,
                CAST('' AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_0900_ai_ci AS nhom,
                0 AS nhom_thu_tu,
                CAST('' AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_0900_ai_ci AS he_sinh_thai,
                CAST('' AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_0900_ai_ci AS chi_tiet,
                CAST(NULL AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_0900_ai_ci AS hang_noi_bat,
                CAST(NULL AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_0900_ai_ci AS san_pham,
                CAST(NULL AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_0900_ai_ci AS nha_phan_phoi,
                CAST(NULL AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_0900_ai_ci AS ghi_chu
            WHERE 1 = 0";
        }

        return implode(' UNION ALL ', $queries);
    }
}
