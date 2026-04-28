<?php

namespace App\Models;

use App\Core\App;
use App\Core\Database;
use InvalidArgumentException;
use RuntimeException;

class ProductRelation
{
    private Database $database;

    public function __construct()
    {
        $this->database = App::get('db');
    }

    public function findProduct(int $chiTietId): ?array
    {
        return $this->database->first(
            'SELECT
                ct.id,
                ct.ten_chi_tiet AS ten,
                COALESCE(ct.ma_sku, "") AS sku,
                COALESCE(summary.danh_muc_id, 0) AS danh_muc_id,
                COALESCE(summary.danh_muc, "Chưa phân loại") AS danh_muc,
                COALESCE(summary.gia_ban_le, 0) AS gia_ban_le,
                COALESCE(summary.ton_kho, 0) AS ton_kho
             FROM san_pham_chi_tiet ct
             LEFT JOIN (
                SELECT
                    ps.chi_tiet_id,
                    MIN(hm.id) AS danh_muc_id,
                    MIN(COALESCE(hm.ten_hang_muc, "")) AS danh_muc,
                    MIN(ps.gia) AS gia_ban_le,
                    SUM(ps.ton_kho) AS ton_kho
                FROM ncc_san_pham_chi_tiet ps
                INNER JOIN nha_cung_cap n ON n.id = ps.ncc_id
                LEFT JOIN hang_muc hm ON hm.id = n.id_hang_muc
                GROUP BY ps.chi_tiet_id
             ) summary ON summary.chi_tiet_id = ct.id
             WHERE ct.id = :chi_tiet_id
             LIMIT 1',
            ['chi_tiet_id' => $chiTietId]
        );
    }

    public function searchProducts(
        string $keyword,
        int $categoryId = 0,
        int $limit = 10,
        ?int $excludeChiTietId = null
    ): array {
        $keyword = trim($keyword);
        if ($keyword === '') {
            return [];
        }

        $params = [
            'keyword' => '%' . $keyword . '%',
            'exact_keyword' => $keyword,
            'prefix_keyword' => $keyword . '%',
        ];
        $where = [
            '(ct.ten_chi_tiet LIKE :keyword OR COALESCE(ct.ma_sku, "") LIKE :keyword)',
        ];

        if ($categoryId > 0) {
            $where[] = 'summary.danh_muc_id = :category_id';
            $params['category_id'] = $categoryId;
        }

        if ($excludeChiTietId !== null && $excludeChiTietId > 0) {
            $where[] = 'ct.id <> :exclude_id';
            $params['exclude_id'] = $excludeChiTietId;
        }

        return $this->database->query(
            'SELECT
                ct.id,
                ct.ten_chi_tiet AS ten,
                COALESCE(ct.ma_sku, "") AS sku,
                COALESCE(summary.danh_muc_id, 0) AS danh_muc_id,
                COALESCE(summary.danh_muc, "Chưa phân loại") AS danh_muc,
                COALESCE(summary.gia_ban_le, 0) AS gia_ban_le,
                COALESCE(summary.ton_kho, 0) AS ton_kho
             FROM san_pham_chi_tiet ct
             LEFT JOIN (
                SELECT
                    ps.chi_tiet_id,
                    MIN(hm.id) AS danh_muc_id,
                    MIN(COALESCE(hm.ten_hang_muc, "")) AS danh_muc,
                    MIN(ps.gia) AS gia_ban_le,
                    SUM(ps.ton_kho) AS ton_kho
                FROM ncc_san_pham_chi_tiet ps
                INNER JOIN nha_cung_cap n ON n.id = ps.ncc_id
                LEFT JOIN hang_muc hm ON hm.id = n.id_hang_muc
                GROUP BY ps.chi_tiet_id
             ) summary ON summary.chi_tiet_id = ct.id
             WHERE ' . implode(' AND ', $where) . '
             ORDER BY
                CASE
                    WHEN COALESCE(ct.ma_sku, "") = :exact_keyword THEN 1
                    WHEN COALESCE(ct.ma_sku, "") LIKE :prefix_keyword THEN 2
                    WHEN ct.ten_chi_tiet = :exact_keyword THEN 3
                    WHEN ct.ten_chi_tiet LIKE :prefix_keyword THEN 4
                    ELSE 99
                END,
                ct.ten_chi_tiet ASC
             LIMIT ' . max(1, min($limit, 20)),
            $params
        );
    }

    public function getRelations(int $sourceChiTietId, string $relationType): array
    {
        $this->assertRelationType($relationType);
        $sourceProduct = $this->findProduct($sourceChiTietId);
        $sourcePrice = (float) ($sourceProduct['gia_ban_le'] ?? 0);

        $rows = $this->database->query(
            'SELECT
                pr.id,
                pr.source_chi_tiet_id,
                pr.target_chi_tiet_id,
                pr.relation_type,
                pr.relation_score,
                COALESCE(pr.note, "") AS note,
                pr.gia_mua_kem,
                COALESCE(pr.so_luong_toi_da, 1) AS so_luong_toi_da,
                pr.chi_kich_hoat_khi_con_hang,
                ct.ten_chi_tiet AS ten,
                COALESCE(ct.ma_sku, "") AS sku,
                COALESCE(summary.danh_muc_id, 0) AS danh_muc_id,
                COALESCE(summary.danh_muc, "Chưa phân loại") AS danh_muc,
                COALESCE(summary.gia_ban_le, 0) AS gia_ban_le,
                COALESCE(summary.ton_kho, 0) AS ton_kho
             FROM lien_ket_san_pham pr
             INNER JOIN san_pham_chi_tiet ct ON ct.id = pr.target_chi_tiet_id
             LEFT JOIN (
                SELECT
                    ps.chi_tiet_id,
                    MIN(hm.id) AS danh_muc_id,
                    MIN(COALESCE(hm.ten_hang_muc, "")) AS danh_muc,
                    MIN(ps.gia) AS gia_ban_le,
                    SUM(ps.ton_kho) AS ton_kho
                FROM ncc_san_pham_chi_tiet ps
                INNER JOIN nha_cung_cap n ON n.id = ps.ncc_id
                LEFT JOIN hang_muc hm ON hm.id = n.id_hang_muc
                GROUP BY ps.chi_tiet_id
             ) summary ON summary.chi_tiet_id = pr.target_chi_tiet_id
             WHERE pr.source_chi_tiet_id = :source_chi_tiet_id
               AND pr.relation_type = :relation_type
             ORDER BY pr.relation_score DESC, ct.ten_chi_tiet ASC',
            [
                'source_chi_tiet_id' => $sourceChiTietId,
                'relation_type' => $relationType,
            ]
        );

        foreach ($rows as &$row) {
            $giaBanLe = (float) ($row['gia_ban_le'] ?? 0);
            $giaMuaKem = $row['gia_mua_kem'] !== null ? (float) $row['gia_mua_kem'] : null;
            $row['chenh_lech_gia'] = $giaBanLe - $sourcePrice;
            $row['phan_tram_giam'] = ($giaMuaKem !== null && $giaBanLe > 0)
                ? round((($giaBanLe - $giaMuaKem) / $giaBanLe) * 100, 2)
                : null;
            $row['co_the_kich_hoat'] = (int) ($row['ton_kho'] ?? 0) > 0;
        }
        unset($row);

        return $rows;
    }

    public function addRelation(
        int $sourceChiTietId,
        int $targetChiTietId,
        string $relationType,
        ?float $giaMuaKem = null,
        ?int $soLuongToiDa = null,
        ?string $note = null,
        bool $chiKichHoatKhiConHang = false
    ): array {
        $this->assertRelationType($relationType);

        if ($sourceChiTietId <= 0 || $targetChiTietId <= 0) {
            throw new InvalidArgumentException('Thiếu sản phẩm nguồn hoặc sản phẩm đích.');
        }

        if ($sourceChiTietId === $targetChiTietId) {
            throw new InvalidArgumentException('Không thể liên kết sản phẩm với chính nó.');
        }

        $sourceProduct = $this->findProduct($sourceChiTietId);
        $targetProduct = $this->findProduct($targetChiTietId);

        if ($sourceProduct === null || $targetProduct === null) {
            throw new InvalidArgumentException('Sản phẩm không tồn tại.');
        }

        if (
            $relationType === 'up_sell'
            && (int) ($sourceProduct['danh_muc_id'] ?? 0) > 0
            && (int) ($sourceProduct['danh_muc_id'] ?? 0) !== (int) ($targetProduct['danh_muc_id'] ?? 0)
        ) {
            throw new InvalidArgumentException('Up-sell chỉ được chọn trong cùng danh mục.');
        }

        $existing = $this->database->first(
            'SELECT id
             FROM lien_ket_san_pham
             WHERE source_chi_tiet_id = :source_chi_tiet_id
               AND target_chi_tiet_id = :target_chi_tiet_id
               AND relation_type = :relation_type
             LIMIT 1',
            [
                'source_chi_tiet_id' => $sourceChiTietId,
                'target_chi_tiet_id' => $targetChiTietId,
                'relation_type' => $relationType,
            ]
        );

        if ($existing !== null) {
            throw new RuntimeException('Quan hệ này đã tồn tại.');
        }

        $scoreRow = $this->database->first(
            'SELECT COALESCE(MAX(relation_score), 0) AS max_score
             FROM lien_ket_san_pham
             WHERE source_chi_tiet_id = :source_chi_tiet_id
               AND relation_type = :relation_type',
            [
                'source_chi_tiet_id' => $sourceChiTietId,
                'relation_type' => $relationType,
            ]
        );

        $giaMuaKemValue = $relationType === 'cross_sell' ? $giaMuaKem : null;
        $soLuongToiDaValue = $relationType === 'cross_sell'
            ? max(1, (int) ($soLuongToiDa ?? 1))
            : 1;
        $chiKichHoatValue = $relationType === 'up_sell' && (int) ($targetProduct['ton_kho'] ?? 0) > 0 && $chiKichHoatKhiConHang;

        $this->database->execute(
            'INSERT INTO lien_ket_san_pham (
                source_chi_tiet_id,
                target_chi_tiet_id,
                relation_type,
                relation_score,
                note,
                gia_mua_kem,
                so_luong_toi_da,
                chi_kich_hoat_khi_con_hang
            ) VALUES (
                :source_chi_tiet_id,
                :target_chi_tiet_id,
                :relation_type,
                :relation_score,
                :note,
                :gia_mua_kem,
                :so_luong_toi_da,
                :chi_kich_hoat_khi_con_hang
            )',
            [
                'source_chi_tiet_id' => $sourceChiTietId,
                'target_chi_tiet_id' => $targetChiTietId,
                'relation_type' => $relationType,
                'relation_score' => ((int) ($scoreRow['max_score'] ?? 0)) + 1,
                'note' => $note !== null && trim($note) !== '' ? trim($note) : null,
                'gia_mua_kem' => $giaMuaKemValue,
                'so_luong_toi_da' => $soLuongToiDaValue,
                'chi_kich_hoat_khi_con_hang' => $chiKichHoatValue ? 1 : 0,
            ]
        );

        return $this->findRelationById((int) $this->database->lastInsertId()) ?? [];
    }

    public function deleteRelation(int $id): bool
    {
        return $this->database->execute(
            'DELETE FROM lien_ket_san_pham WHERE id = :id',
            ['id' => $id]
        );
    }

    public function updateNote(int $id, string $note): bool
    {
        return $this->database->execute(
            'UPDATE lien_ket_san_pham
             SET note = :note
             WHERE id = :id',
            [
                'id' => $id,
                'note' => trim($note) !== '' ? trim($note) : null,
            ]
        );
    }

    public function updateCrossSell(int $id, ?float $giaMuaKem, int $soLuongToiDa): ?array
    {
        $relation = $this->findRelationById($id);
        if ($relation === null || $relation['relation_type'] !== 'cross_sell') {
            return null;
        }

        $this->database->execute(
            'UPDATE lien_ket_san_pham
             SET gia_mua_kem = :gia_mua_kem,
                 so_luong_toi_da = :so_luong_toi_da
             WHERE id = :id',
            [
                'id' => $id,
                'gia_mua_kem' => $giaMuaKem,
                'so_luong_toi_da' => max(1, $soLuongToiDa),
            ]
        );

        return $this->findRelationById($id);
    }

    public function updateUpSell(int $id, bool $chiKichHoatKhiConHang): ?array
    {
        $relation = $this->findRelationById($id);
        if ($relation === null || $relation['relation_type'] !== 'up_sell') {
            return null;
        }

        $khaDung = (int) ($relation['ton_kho'] ?? 0) > 0;

        $this->database->execute(
            'UPDATE lien_ket_san_pham
             SET chi_kich_hoat_khi_con_hang = :chi_kich_hoat_khi_con_hang
             WHERE id = :id',
            [
                'id' => $id,
                'chi_kich_hoat_khi_con_hang' => $khaDung && $chiKichHoatKhiConHang ? 1 : 0,
            ]
        );

        return $this->findRelationById($id);
    }

    public function findRelationById(int $id): ?array
    {
        $row = $this->database->first(
            'SELECT
                pr.id,
                pr.source_chi_tiet_id,
                pr.target_chi_tiet_id,
                pr.relation_type,
                pr.relation_score,
                COALESCE(pr.note, "") AS note,
                pr.gia_mua_kem,
                COALESCE(pr.so_luong_toi_da, 1) AS so_luong_toi_da,
                pr.chi_kich_hoat_khi_con_hang,
                ct.ten_chi_tiet AS ten,
                COALESCE(ct.ma_sku, "") AS sku,
                COALESCE(summary.danh_muc_id, 0) AS danh_muc_id,
                COALESCE(summary.danh_muc, "Chưa phân loại") AS danh_muc,
                COALESCE(summary.gia_ban_le, 0) AS gia_ban_le,
                COALESCE(summary.ton_kho, 0) AS ton_kho
             FROM lien_ket_san_pham pr
             INNER JOIN san_pham_chi_tiet ct ON ct.id = pr.target_chi_tiet_id
             LEFT JOIN (
                SELECT
                    ps.chi_tiet_id,
                    MIN(hm.id) AS danh_muc_id,
                    MIN(COALESCE(hm.ten_hang_muc, "")) AS danh_muc,
                    MIN(ps.gia) AS gia_ban_le,
                    SUM(ps.ton_kho) AS ton_kho
                FROM ncc_san_pham_chi_tiet ps
                INNER JOIN nha_cung_cap n ON n.id = ps.ncc_id
                LEFT JOIN hang_muc hm ON hm.id = n.id_hang_muc
                GROUP BY ps.chi_tiet_id
             ) summary ON summary.chi_tiet_id = pr.target_chi_tiet_id
             WHERE pr.id = :id
             LIMIT 1',
            ['id' => $id]
        );

        if ($row === null) {
            return null;
        }

        $sourceProduct = $this->findProduct((int) $row['source_chi_tiet_id']);
        $sourcePrice = (float) ($sourceProduct['gia_ban_le'] ?? 0);
        $giaBanLe = (float) ($row['gia_ban_le'] ?? 0);
        $giaMuaKem = $row['gia_mua_kem'] !== null ? (float) $row['gia_mua_kem'] : null;

        $row['chenh_lech_gia'] = $giaBanLe - $sourcePrice;
        $row['phan_tram_giam'] = ($giaMuaKem !== null && $giaBanLe > 0)
            ? round((($giaBanLe - $giaMuaKem) / $giaBanLe) * 100, 2)
            : null;
        $row['co_the_kich_hoat'] = (int) ($row['ton_kho'] ?? 0) > 0;

        return $row;
    }

    private function assertRelationType(string $relationType): void
    {
        if (!in_array($relationType, ['cross_sell', 'up_sell'], true)) {
            throw new InvalidArgumentException('Loại quan hệ không hợp lệ.');
        }
    }
}
