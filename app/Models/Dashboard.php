<?php

namespace App\Models;

use App\Core\App;
use App\Core\Database;

class Dashboard
{
    private Database $database;

    public function __construct()
    {
        $this->database = App::get('db');
    }

    public function stats(): array
    {
        $counts = $this->database->first(
            'SELECT
                (SELECT COUNT(*) FROM nha_cung_cap) AS total_ncc,
                (SELECT COUNT(*) FROM san_pham_ncc) AS total_san_pham,
                (SELECT COUNT(*) FROM thuong_hieu) AS total_thuong_hieu'
        ) ?: [];

        return [
            'total_ncc' => (int) ($counts['total_ncc'] ?? 0),
            'total_san_pham' => (int) ($counts['total_san_pham'] ?? 0),
            'total_thuong_hieu' => (int) ($counts['total_thuong_hieu'] ?? 0),
        ];
    }

    public function recentSuppliers(int $limit = 8): array
    {
        return $this->database->query(
            'SELECT n.id, n.ten_ncc, n.website, n.nguoi_lien_he, n.nhom_zalo, hm.ten_hang_muc
             FROM nha_cung_cap n
             LEFT JOIN hang_muc hm ON hm.id = n.id_hang_muc
             ORDER BY n.id DESC
             LIMIT ' . (int) $limit
        );
    }

    public function globalSearch(string $keyword, int $limit = 6): array
    {
        $keyword = trim($keyword);

        if ($keyword === '') {
            return [];
        }

        $like = '%' . $keyword . '%';

        return $this->database->query(
            'WITH sp AS (
                SELECT
                    lk.ncc_id,
                    GROUP_CONCAT(DISTINCT p.ten_san_pham ORDER BY p.ten_san_pham SEPARATOR ", ") AS san_pham_ncc_lk
                FROM ncc_lien_ket_san_pham lk
                INNER JOIN san_pham_ncc p ON p.id = lk.san_pham_id
                GROUP BY lk.ncc_id
            ),
            th AS (
                SELECT
                    lk.ncc_id,
                    GROUP_CONCAT(DISTINCT t.ten_thuong_hieu ORDER BY t.ten_thuong_hieu SEPARATOR ", ") AS thuong_hieu_lk
                FROM ncc_lien_ket_thuong_hieu lk
                INNER JOIN thuong_hieu t ON t.id = lk.thuong_hieu_id
                GROUP BY lk.ncc_id
            ),
            ct AS (
                SELECT
                    lk.ncc_id,
                    GROUP_CONCAT(DISTINCT c.ten_chi_tiet ORDER BY c.ten_chi_tiet SEPARATOR ", ") AS san_pham_chi_tiet_lk
                FROM ncc_lien_ket_chi_tiet lk
                INNER JOIN san_pham_chi_tiet c ON c.id = lk.chi_tiet_id
                GROUP BY lk.ncc_id
            )
            SELECT
                n.id,
                n.ten_ncc,
                hm.ten_hang_muc,
                n.website,
                n.nguoi_lien_he,
                n.nhom_zalo,
                COALESCE(NULLIF(sp.san_pham_ncc_lk, ""), n.san_pham_ncc) AS san_pham_ncc,
                COALESCE(NULLIF(th.thuong_hieu_lk, ""), n.thuong_hieu_phan_phoi) AS thuong_hieu_phan_phoi,
                COALESCE(NULLIF(ct.san_pham_chi_tiet_lk, ""), n.san_pham_chi_tiet) AS san_pham_chi_tiet,
                n.ghi_chu
            FROM nha_cung_cap n
            LEFT JOIN hang_muc hm ON hm.id = n.id_hang_muc
            LEFT JOIN sp ON sp.ncc_id = n.id
            LEFT JOIN th ON th.ncc_id = n.id
            LEFT JOIN ct ON ct.ncc_id = n.id
            WHERE n.ten_ncc LIKE :keyword
               OR hm.ten_hang_muc LIKE :keyword
               OR n.website LIKE :keyword
               OR n.nguoi_lien_he LIKE :keyword
               OR n.nhom_zalo LIKE :keyword
               OR COALESCE(sp.san_pham_ncc_lk, n.san_pham_ncc, "") LIKE :keyword
               OR COALESCE(th.thuong_hieu_lk, n.thuong_hieu_phan_phoi, "") LIKE :keyword
               OR COALESCE(ct.san_pham_chi_tiet_lk, n.san_pham_chi_tiet, "") LIKE :keyword
               OR n.ghi_chu LIKE :keyword
            ORDER BY n.ten_ncc ASC
            LIMIT ' . (int) $limit,
            ['keyword' => $like]
        );
    }
}
