<?php

namespace App\Models;

use App\Core\App;
use App\Core\Database;
use Throwable;

class SupplierProduct
{
    private Database $database;

    public function __construct()
    {
        $this->database = App::get('db');
    }

    public function paginate(int $page = 1, int $perPage = 10, string $keyword = ''): array
    {
        $page = max(1, $page);
        $perPage = max(1, $perPage);
        $offset = ($page - 1) * $perPage;
        $keyword = trim($keyword);
        $params = [];
        $where = '';

        if ($keyword !== '') {
            $params['keyword'] = '%' . $keyword . '%';
            $where = 'WHERE
                n.ma_ncc LIKE :keyword
                OR n.ten_ncc LIKE :keyword
                OR COALESCE(ct.ma_sku, "") LIKE :keyword
                OR ct.ten_chi_tiet LIKE :keyword
                OR COALESCE(ct.thuong_hieu, "") LIKE :keyword
                OR COALESCE(sp.ten_san_pham, "") LIKE :keyword';
        }

        $baseFrom = '
            FROM ncc_lien_ket_chi_tiet lk
            INNER JOIN nha_cung_cap n ON n.id = lk.ncc_id
            INNER JOIN san_pham_chi_tiet ct ON ct.id = lk.chi_tiet_id
            LEFT JOIN san_pham_ncc sp ON sp.id = ct.san_pham_id';

        $totalRow = $this->database->first(
            'SELECT COUNT(*) AS total ' . $baseFrom . ' ' . $where,
            $params
        );

        $items = $this->database->query(
            'SELECT
                lk.ncc_id,
                lk.chi_tiet_id,
                n.ma_ncc,
                n.ten_ncc,
                ct.san_pham_id,
                COALESCE(ct.ma_sku, "") AS ma_sku,
                ct.ten_chi_tiet AS ten_san_pham_chi_tiet,
                COALESCE(ct.thuong_hieu, "") AS thuong_hieu,
                ct.gia,
                CASE
                    WHEN ct.trang_thai = "dang_ban" THEN "Đang bán"
                    WHEN ct.trang_thai = "ngung" THEN "Ngừng"
                    ELSE ""
                END AS trang_thai,
                ct.ton_kho,
                DATE_FORMAT(ct.ngay_cap_nhat, "%d/%m/%Y %H:%i") AS ngay_cap_nhat,
                COALESCE(sp.ten_san_pham, "") AS ten_san_pham_ncc
             ' . $baseFrom . '
             ' . $where . '
             ORDER BY n.ten_ncc ASC, ct.ten_chi_tiet ASC
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

    public function find(int $nccId, int $chiTietId): ?array
    {
        return $this->database->first(
            'SELECT
                lk.ncc_id,
                lk.chi_tiet_id,
                n.ma_ncc,
                n.ten_ncc,
                ct.ncc_id AS current_ncc_id,
                ct.san_pham_id,
                COALESCE(ct.ma_sku, "") AS ma_sku,
                ct.ten_chi_tiet AS ten_san_pham_chi_tiet,
                COALESCE(ct.thuong_hieu, "") AS thuong_hieu,
                ct.gia,
                ct.trang_thai AS trang_thai_raw,
                CASE
                    WHEN ct.trang_thai = "dang_ban" THEN "Đang bán"
                    WHEN ct.trang_thai = "ngung" THEN "Ngừng"
                    ELSE ""
                END AS trang_thai,
                ct.ton_kho,
                ct.ngay_cap_nhat,
                DATE_FORMAT(ct.ngay_cap_nhat, "%d/%m/%Y %H:%i") AS ngay_cap_nhat_hien_thi,
                COALESCE(sp.ten_san_pham, "") AS ten_san_pham_ncc
             FROM ncc_lien_ket_chi_tiet lk
             INNER JOIN nha_cung_cap n ON n.id = lk.ncc_id
             INNER JOIN san_pham_chi_tiet ct ON ct.id = lk.chi_tiet_id
             LEFT JOIN san_pham_ncc sp ON sp.id = ct.san_pham_id
             WHERE lk.ncc_id = :ncc_id AND lk.chi_tiet_id = :chi_tiet_id
             LIMIT 1',
            [
                'ncc_id' => $nccId,
                'chi_tiet_id' => $chiTietId,
            ]
        );
    }

    public function create(array $payload): int
    {
        $pdo = $this->database->pdo();

        try {
            $pdo->beginTransaction();

            $this->database->execute(
                'INSERT INTO san_pham_chi_tiet (
                    ncc_id,
                    san_pham_id,
                    ma_sku,
                    ten_chi_tiet,
                    thuong_hieu,
                    gia,
                    trang_thai,
                    ton_kho,
                    ngay_cap_nhat
                ) VALUES (
                    :ncc_id,
                    :san_pham_id,
                    :ma_sku,
                    :ten_chi_tiet,
                    :thuong_hieu,
                    :gia,
                    :trang_thai,
                    :ton_kho,
                    NOW()
                )',
                [
                    'ncc_id' => $payload['ncc_id'],
                    'san_pham_id' => $payload['san_pham_id'],
                    'ma_sku' => $payload['ma_sku'],
                    'ten_chi_tiet' => $payload['ten_chi_tiet'],
                    'thuong_hieu' => $payload['thuong_hieu'],
                    'gia' => $payload['gia'],
                    'trang_thai' => $payload['trang_thai'],
                    'ton_kho' => $payload['ton_kho'],
                ]
            );

            $chiTietId = (int) $this->database->lastInsertId();

            $this->database->execute(
                'INSERT INTO ncc_lien_ket_chi_tiet (ncc_id, chi_tiet_id)
                 VALUES (:ncc_id, :chi_tiet_id)',
                [
                    'ncc_id' => $payload['ncc_id'],
                    'chi_tiet_id' => $chiTietId,
                ]
            );

            $this->ensureSupplierProductLink((int) $payload['ncc_id'], (int) $payload['san_pham_id']);

            $pdo->commit();

            return $chiTietId;
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $exception;
        }
    }

    public function update(int $nccId, int $chiTietId, array $payload): bool
    {
        $existing = $this->database->first(
            'SELECT ncc_id, san_pham_id, gia
             FROM san_pham_chi_tiet
             WHERE id = :chi_tiet_id
             LIMIT 1',
            ['chi_tiet_id' => $chiTietId]
        );

        if (!$existing) {
            return false;
        }

        $pdo = $this->database->pdo();
        $oldPrice = $existing['gia'];
        $newPrice = $payload['gia'];
        $priceChanged = $this->priceChanged($oldPrice, $newPrice);
        $newNccId = (int) $payload['ncc_id'];
        $newSanPhamId = (int) $payload['san_pham_id'];

        try {
            $pdo->beginTransaction();

            $this->database->execute(
                'UPDATE san_pham_chi_tiet
                 SET ncc_id = :ncc_id,
                     san_pham_id = :san_pham_id,
                     ma_sku = :ma_sku,
                     ten_chi_tiet = :ten_chi_tiet,
                     thuong_hieu = :thuong_hieu,
                     gia = :gia,
                     trang_thai = :trang_thai,
                     ton_kho = :ton_kho,
                     ngay_cap_nhat = NOW()
                 WHERE id = :chi_tiet_id',
                [
                    'ncc_id' => $newNccId,
                    'san_pham_id' => $newSanPhamId,
                    'ma_sku' => $payload['ma_sku'],
                    'ten_chi_tiet' => $payload['ten_chi_tiet'],
                    'thuong_hieu' => $payload['thuong_hieu'],
                    'gia' => $newPrice,
                    'trang_thai' => $payload['trang_thai'],
                    'ton_kho' => $payload['ton_kho'],
                    'chi_tiet_id' => $chiTietId,
                ]
            );

            if ($newNccId !== $nccId) {
                $this->database->execute(
                    'DELETE FROM ncc_lien_ket_chi_tiet
                     WHERE ncc_id = :old_ncc_id AND chi_tiet_id = :chi_tiet_id',
                    [
                        'old_ncc_id' => $nccId,
                        'chi_tiet_id' => $chiTietId,
                    ]
                );

                $this->database->execute(
                    'INSERT INTO ncc_lien_ket_chi_tiet (ncc_id, chi_tiet_id)
                     VALUES (:ncc_id, :chi_tiet_id)
                     ON DUPLICATE KEY UPDATE ncc_id = VALUES(ncc_id)',
                    [
                        'ncc_id' => $newNccId,
                        'chi_tiet_id' => $chiTietId,
                    ]
                );
            }

            $this->ensureSupplierProductLink($newNccId, $newSanPhamId);

            if ($priceChanged) {
                $note = trim((string) ($payload['price_note'] ?? ''));

                if ($note === '') {
                    $note = 'Cập nhật giá sản phẩm';
                }

                if ($oldPrice !== null && $oldPrice !== '') {
                    $note = sprintf('Giá thay đổi từ %s sang %s. %s', $oldPrice, $newPrice, $note);
                }

                $this->database->execute(
                    'INSERT INTO lich_su_gia_san_pham (ncc_id, chi_tiet_id, gia, ghi_chu, thoi_gian_thay_doi)
                     VALUES (:ncc_id, :chi_tiet_id, :gia, :ghi_chu, NOW())',
                    [
                        'ncc_id' => $newNccId,
                        'chi_tiet_id' => $chiTietId,
                        'gia' => $newPrice,
                        'ghi_chu' => $note,
                    ]
                );
            }

            $pdo->commit();

            return true;
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $exception;
        }
    }

    public function priceHistory(int $nccId, int $chiTietId): array
    {
        try {
            return $this->database->query(
                'SELECT
                    gia,
                    DATE_FORMAT(thoi_gian_thay_doi, "%d/%m/%Y %H:%i") AS thoi_gian_thay_doi,
                    ghi_chu
                 FROM lich_su_gia_san_pham
                 WHERE chi_tiet_id = :chi_tiet_id
                 ORDER BY thoi_gian_thay_doi DESC, id DESC',
                [
                    'chi_tiet_id' => $chiTietId,
                ]
            );
        } catch (Throwable $exception) {
            return [];
        }
    }

    public function supplierOptions(): array
    {
        return $this->database->query(
            'SELECT id, ma_ncc, ten_ncc
             FROM nha_cung_cap
             ORDER BY ten_ncc ASC'
        );
    }

    public function productOptions(): array
    {
        return $this->database->query(
            'SELECT id, ten_san_pham
             FROM san_pham_ncc
             ORDER BY ten_san_pham ASC'
        );
    }

    private function ensureSupplierProductLink(int $nccId, int $sanPhamId): void
    {
        if ($nccId <= 0 || $sanPhamId <= 0) {
            return;
        }

        $this->database->execute(
            'INSERT INTO ncc_lien_ket_san_pham (ncc_id, san_pham_id)
             VALUES (:ncc_id, :san_pham_id)
             ON DUPLICATE KEY UPDATE ncc_id = VALUES(ncc_id)',
            [
                'ncc_id' => $nccId,
                'san_pham_id' => $sanPhamId,
            ]
        );
    }

    private function priceChanged(mixed $oldPrice, mixed $newPrice): bool
    {
        if ($oldPrice === null && $newPrice === null) {
            return false;
        }

        if ($oldPrice === null || $newPrice === null) {
            return true;
        }

        return (string) $oldPrice !== (string) $newPrice;
    }
}
