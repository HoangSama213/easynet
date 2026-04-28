<?php

namespace App\Models;

use App\Core\App;
use App\Core\Database;

class Supplier
{
    private Database $database;

    public function __construct()
    {
        $this->database = App::get('db');
    }

    public function paginate(string $keyword = '', int $categoryId = 0, int $page = 1, int $perPage = 5): array
    {
        $page = max(1, $page);
        $perPage = max(1, $perPage);
        $offset = ($page - 1) * $perPage;
        $params = [];
        $conditions = [];

        $sanPhamSelect = 'COALESCE(NULLIF(n.san_pham_ncc, ""), sp_rel.san_pham_ncc, "") AS san_pham_ncc';
        $thuongHieuSelect = 'COALESCE(NULLIF(n.thuong_hieu_phan_phoi, ""), NULLIF(th_rel.thuong_hieu_phan_phoi, ""), "") AS thuong_hieu_phan_phoi';
        $chiTietSelect = 'COALESCE(NULLIF(n.san_pham_chi_tiet, ""), ct_rel.san_pham_chi_tiet, "") AS san_pham_chi_tiet';

        if ($keyword !== '') {
            $params['keyword'] = '%' . $keyword . '%';
            $conditions[] = '(n.ten_ncc LIKE :keyword
                OR hm.ten_hang_muc LIKE :keyword
                OR n.website LIKE :keyword
                OR n.nguoi_lien_he LIKE :keyword
                OR n.nhom_zalo LIKE :keyword
                OR COALESCE(NULLIF(n.san_pham_ncc, ""), sp_rel.san_pham_ncc, "") LIKE :keyword
                OR COALESCE(NULLIF(n.thuong_hieu_phan_phoi, ""), NULLIF(th_rel.thuong_hieu_phan_phoi, ""), "") LIKE :keyword
                OR COALESCE(NULLIF(n.san_pham_chi_tiet, ""), ct_rel.san_pham_chi_tiet, "") LIKE :keyword
                OR n.ghi_chu LIKE :keyword)';
        }

        if ($categoryId > 0) {
            $params['category_id'] = $categoryId;
            $conditions[] = 'n.id_hang_muc = :category_id';
        }

        $where = $conditions === [] ? '' : 'WHERE ' . implode(' AND ', $conditions);

        $relationJoins = '
            LEFT JOIN (
                SELECT
                    ps.ncc_id,
                    GROUP_CONCAT(DISTINCT sp.ten_san_pham ORDER BY sp.ten_san_pham SEPARATOR ", ") AS san_pham_ncc
                FROM ncc_san_pham_chi_tiet ps
                INNER JOIN san_pham_ncc sp ON sp.id = ps.san_pham_id
                GROUP BY ps.ncc_id
            ) sp_rel ON sp_rel.ncc_id = n.id
            LEFT JOIN (
                SELECT
                    ps.ncc_id,
                    GROUP_CONCAT(DISTINCT th.ten_thuong_hieu ORDER BY th.ten_thuong_hieu SEPARATOR ", ") AS thuong_hieu_phan_phoi
                FROM ncc_san_pham_chi_tiet ps
                INNER JOIN san_pham_chi_tiet ct ON ct.id = ps.chi_tiet_id
                INNER JOIN thuong_hieu th ON th.id = ct.thuong_hieu_id
                GROUP BY ps.ncc_id
            ) th_rel ON th_rel.ncc_id = n.id
            LEFT JOIN (
                SELECT
                    ps.ncc_id,
                    GROUP_CONCAT(DISTINCT ct.ten_chi_tiet ORDER BY ct.ten_chi_tiet SEPARATOR ", ") AS san_pham_chi_tiet
                FROM ncc_san_pham_chi_tiet ps
                INNER JOIN san_pham_chi_tiet ct ON ct.id = ps.chi_tiet_id
                GROUP BY ps.ncc_id
            ) ct_rel ON ct_rel.ncc_id = n.id';

        $totalRow = $this->database->first(
            'SELECT COUNT(*) AS total
             FROM nha_cung_cap n
             LEFT JOIN hang_muc hm ON hm.id = n.id_hang_muc
             ' . $relationJoins . '
             ' . $where,
            $params
        );

        $items = $this->database->query(
            'SELECT
                n.id,
                n.ma_ncc,
                n.ten_ncc,
                hm.ten_hang_muc,
                n.website,
                n.nguoi_lien_he,
                n.nhom_zalo,
                ' . $sanPhamSelect . ',
                ' . $thuongHieuSelect . ',
                ' . $chiTietSelect . ',
                n.ghi_chu
             FROM nha_cung_cap n
             LEFT JOIN hang_muc hm ON hm.id = n.id_hang_muc
             ' . $relationJoins . '
             ' . $where . '
             ORDER BY n.id DESC
             LIMIT ' . $perPage . ' OFFSET ' . $offset,
            $params
        );

        $total = (int) ($totalRow['total'] ?? 0);

        return [
            'items' => $items,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'last_page' => max(1, (int) ceil($total / $perPage)),
        ];
    }

    public function categories(): array
    {
        return $this->database->query(
            'SELECT id, ten_hang_muc
             FROM hang_muc
             ORDER BY ten_hang_muc ASC'
        );
    }

    public function create(array $data): void
    {
        $data['ma_ncc'] = $data['ma_ncc'] ?? $this->nextSupplierCode();

        $this->database->execute(
            'INSERT INTO nha_cung_cap (
                ma_ncc,
                ten_ncc,
                id_hang_muc,
                hang_muc,
                website,
                nguoi_lien_he,
                nhom_zalo,
                san_pham_ncc,
                thuong_hieu_phan_phoi,
                san_pham_chi_tiet,
                ghi_chu
            ) VALUES (
                :ma_ncc,
                :ten_ncc,
                :id_hang_muc,
                :hang_muc,
                :website,
                :nguoi_lien_he,
                :nhom_zalo,
                :san_pham_ncc,
                :thuong_hieu_phan_phoi,
                :san_pham_chi_tiet,
                :ghi_chu
            )',
            $data
        );
    }

    public function find(int $id): ?array
    {
        return $this->database->first(
            'SELECT
                n.id,
                n.ma_ncc,
                n.ten_ncc,
                n.id_hang_muc,
                n.website,
                n.nguoi_lien_he,
                n.nhom_zalo,
                n.san_pham_ncc,
                n.thuong_hieu_phan_phoi,
                n.san_pham_chi_tiet,
                n.ghi_chu
             FROM nha_cung_cap n
             WHERE n.id = :id
             LIMIT 1',
            ['id' => $id]
        );
    }

    public function update(int $id, array $data): void
    {
        $data['id'] = $id;

        $this->database->execute(
            'UPDATE nha_cung_cap
             SET
                ten_ncc = :ten_ncc,
                id_hang_muc = :id_hang_muc,
                hang_muc = :hang_muc,
                website = :website,
                nguoi_lien_he = :nguoi_lien_he,
                nhom_zalo = :nhom_zalo,
                san_pham_ncc = :san_pham_ncc,
                thuong_hieu_phan_phoi = :thuong_hieu_phan_phoi,
                san_pham_chi_tiet = :san_pham_chi_tiet,
                ghi_chu = :ghi_chu
             WHERE id = :id',
            $data
        );
    }

    public function delete(int $id): void
    {
        $this->database->execute(
            'DELETE FROM nha_cung_cap
             WHERE id = :id',
            ['id' => $id]
        );
    }

    public function categoryById(int $id): ?array
    {
        return $this->database->first(
            'SELECT id, ten_hang_muc
             FROM hang_muc
             WHERE id = :id
             LIMIT 1',
            ['id' => $id]
        );
    }

    public function options(): array
    {
        return $this->database->query(
            'SELECT id, ma_ncc, ten_ncc
             FROM nha_cung_cap
             ORDER BY ten_ncc ASC'
        );
    }

    private function nextSupplierCode(): string
    {
        $row = $this->database->first(
            'SELECT MAX(CAST(SUBSTRING(ma_ncc, 5) AS UNSIGNED)) AS max_code
             FROM nha_cung_cap
             WHERE ma_ncc LIKE "NCC-%"'
        );

        $nextNumber = ((int) ($row['max_code'] ?? 0)) + 1;

        return 'NCC-' . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
