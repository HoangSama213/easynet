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
        $orderBy = 'ORDER BY n.ten_ncc ASC, ct.ten_chi_tiet ASC, ps.id ASC';

        if ($keyword !== '') {
            $params['keyword'] = '%' . $keyword . '%';
            $params['exact_keyword'] = $keyword;
            $params['prefix_keyword'] = $keyword . '%';
            $where = 'WHERE
                COALESCE(ct.ma_sku, "") LIKE :keyword
                OR COALESCE(ps.ma_san_pham, "") LIKE :keyword
                OR COALESCE(n.ma_ncc, "") LIKE :keyword
                OR n.ten_ncc LIKE :keyword
                OR ct.ten_chi_tiet LIKE :keyword
                OR COALESCE(th.ten_thuong_hieu, "") LIKE :keyword
                OR COALESCE(sp.ten_san_pham, "") LIKE :keyword';

            $orderBy = 'ORDER BY
                CASE
                    WHEN ct.ten_chi_tiet = :exact_keyword THEN 1
                    WHEN ct.ten_chi_tiet LIKE :prefix_keyword THEN 2
                    WHEN ct.ten_chi_tiet LIKE :keyword THEN 3
                    WHEN COALESCE(ct.ma_sku, "") = :exact_keyword THEN 4
                    WHEN COALESCE(ct.ma_sku, "") LIKE :prefix_keyword THEN 5
                    WHEN n.ten_ncc = :exact_keyword THEN 6
                    WHEN n.ten_ncc LIKE :prefix_keyword THEN 7
                    WHEN COALESCE(th.ten_thuong_hieu, "") = :exact_keyword THEN 8
                    WHEN COALESCE(th.ten_thuong_hieu, "") LIKE :prefix_keyword THEN 9
                    WHEN COALESCE(sp.ten_san_pham, "") = :exact_keyword THEN 10
                    WHEN COALESCE(sp.ten_san_pham, "") LIKE :prefix_keyword THEN 11
                    ELSE 99
                END,
                ct.ten_chi_tiet ASC,
                n.ten_ncc ASC,
                ps.id ASC';
        }

        $baseFrom = '
            FROM ncc_san_pham_chi_tiet ps
            INNER JOIN nha_cung_cap n ON n.id = ps.ncc_id
            INNER JOIN san_pham_chi_tiet ct ON ct.id = ps.chi_tiet_id
            LEFT JOIN thuong_hieu th ON th.id = ct.thuong_hieu_id
            LEFT JOIN san_pham_ncc sp ON sp.id = ps.san_pham_id';

        $totalRow = $this->database->first(
            'SELECT COUNT(*) AS total ' . $baseFrom . ' ' . $where,
            $params
        );

        $items = $this->database->query(
            'SELECT
                ps.id,
                ps.ma_san_pham,
                ps.ncc_id,
                ps.chi_tiet_id,
                ps.san_pham_id,
                n.ma_ncc,
                n.ten_ncc,
                COALESCE(ct.ma_sku, "") AS ma_sku,
                ct.ten_chi_tiet AS ten_san_pham_chi_tiet,
                COALESCE(th.ten_thuong_hieu, "") AS thuong_hieu,
                ps.gia,
                CASE
                    WHEN ps.trang_thai = "dang_ban" THEN "Đang bán"
                    WHEN ps.trang_thai = "ngung" THEN "Ngừng"
                    ELSE ""
                END AS trang_thai,
                ps.trang_thai AS trang_thai_raw,
                ps.ton_kho,
                DATE_FORMAT(ps.ngay_cap_nhat, "%d/%m/%Y %H:%i") AS ngay_cap_nhat,
                COALESCE(sp.ten_san_pham, "") AS ten_san_pham_ncc
            ' . $baseFrom . '
            ' . $where . '
            ' . $orderBy . '
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

    public function findById(int $id): ?array
    {
        return $this->database->first(
            'SELECT
                ps.id,
                ps.ma_san_pham,
                ps.ncc_id,
                ps.chi_tiet_id,
                ps.san_pham_id,
                n.ma_ncc,
                n.ten_ncc,
                COALESCE(ct.ma_sku, "") AS ma_sku,
                ct.ten_chi_tiet AS ten_san_pham_chi_tiet,
                COALESCE(th.ten_thuong_hieu, "") AS thuong_hieu,
                ps.gia,
                ps.trang_thai AS trang_thai_raw,
                CASE
                    WHEN ps.trang_thai = "dang_ban" THEN "Đang bán"
                    WHEN ps.trang_thai = "ngung" THEN "Ngừng"
                    ELSE ""
                END AS trang_thai,
                ps.ton_kho,
                ps.ngay_cap_nhat,
                DATE_FORMAT(ps.ngay_cap_nhat, "%d/%m/%Y %H:%i") AS ngay_cap_nhat_hien_thi,
                COALESCE(sp.ten_san_pham, "") AS ten_san_pham_ncc
             FROM ncc_san_pham_chi_tiet ps
             INNER JOIN nha_cung_cap n ON n.id = ps.ncc_id
             INNER JOIN san_pham_chi_tiet ct ON ct.id = ps.chi_tiet_id
             LEFT JOIN thuong_hieu th ON th.id = ct.thuong_hieu_id
             LEFT JOIN san_pham_ncc sp ON sp.id = ps.san_pham_id
             WHERE ps.id = :id
             LIMIT 1',
            ['id' => $id]
        );
    }

    public function create(array $payload): int
    {
        $pdo = $this->database->pdo();

        try {
            $pdo->beginTransaction();

            $chiTietId = $this->resolveDetailId(
                $payload['ten_chi_tiet'],
                $payload['ma_sku'] ?? null,
                $payload['thuong_hieu'] ?? null
            );
            $sanPhamId = $this->normalizeProductId($payload['san_pham_id']);

            $this->database->execute(
                'INSERT INTO ncc_san_pham_chi_tiet (
                    ma_san_pham,
                    ncc_id,
                    san_pham_id,
                    chi_tiet_id,
                    gia,
                    trang_thai,
                    ton_kho,
                    ngay_cap_nhat
                ) VALUES (
                    :ma_san_pham,
                    :ncc_id,
                    :san_pham_id,
                    :chi_tiet_id,
                    :gia,
                    :trang_thai,
                    :ton_kho,
                    NOW()
                )',
                [
                    'ma_san_pham' => $this->nextProductCode(),
                    'ncc_id' => $payload['ncc_id'],
                    'san_pham_id' => $sanPhamId,
                    'chi_tiet_id' => $chiTietId,
                    'gia' => $payload['gia'],
                    'trang_thai' => $payload['trang_thai'],
                    'ton_kho' => $payload['ton_kho'],
                ]
            );

            $productId = (int) $this->database->lastInsertId();
            $this->syncLegacyLinks((int) $payload['ncc_id'], $sanPhamId, $chiTietId);
            $this->syncContent($chiTietId, $payload['content_items'] ?? []);

            $pdo->commit();

            return $productId;
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $exception;
        }
    }

    public function update(int $id, array $payload): bool
    {
        $existing = $this->findById($id);

        if (!$existing) {
            return false;
        }

        $pdo = $this->database->pdo();
        $oldPrice = $existing['gia'];
        $newPrice = $payload['gia'];
        $priceChanged = $this->priceChanged($oldPrice, $newPrice);

        try {
            $pdo->beginTransaction();

            $chiTietId = $this->resolveDetailId(
                $payload['ten_chi_tiet'],
                $payload['ma_sku'] ?? null,
                $payload['thuong_hieu'] ?? null,
                (int) ($existing['chi_tiet_id'] ?? 0)
            );
            $sanPhamId = $this->normalizeProductId($payload['san_pham_id']);

            $this->database->execute(
                'UPDATE ncc_san_pham_chi_tiet
                 SET ncc_id = :ncc_id,
                     san_pham_id = :san_pham_id,
                     chi_tiet_id = :chi_tiet_id,
                     gia = :gia,
                     trang_thai = :trang_thai,
                     ton_kho = :ton_kho,
                     ngay_cap_nhat = NOW()
                 WHERE id = :id',
                [
                    'ncc_id' => $payload['ncc_id'],
                    'san_pham_id' => $sanPhamId,
                    'chi_tiet_id' => $chiTietId,
                    'gia' => $newPrice,
                    'trang_thai' => $payload['trang_thai'],
                    'ton_kho' => $payload['ton_kho'],
                    'id' => $id,
                ]
            );

            $this->syncLegacyLinks((int) $payload['ncc_id'], $sanPhamId, $chiTietId);
            $this->syncContent($chiTietId, $payload['content_items'] ?? []);

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
                        'ncc_id' => (int) $payload['ncc_id'],
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

    public function delete(int $id): bool
    {
        $existing = $this->findById($id);

        if (!$existing) {
            return false;
        }

        $pdo = $this->database->pdo();

        try {
            $pdo->beginTransaction();

            $this->database->execute(
                'DELETE FROM ncc_san_pham_chi_tiet WHERE id = :id',
                ['id' => $id]
            );

            $remainingSupplierUsage = $this->database->first(
                'SELECT COUNT(*) AS total
                 FROM ncc_san_pham_chi_tiet
                 WHERE ncc_id = :ncc_id AND chi_tiet_id = :chi_tiet_id',
                [
                    'ncc_id' => $existing['ncc_id'],
                    'chi_tiet_id' => $existing['chi_tiet_id'],
                ]
            );

            if ((int) ($remainingSupplierUsage['total'] ?? 0) === 0) {
                $this->database->execute(
                    'DELETE FROM ncc_lien_ket_chi_tiet
                     WHERE ncc_id = :ncc_id AND chi_tiet_id = :chi_tiet_id',
                    [
                        'ncc_id' => $existing['ncc_id'],
                        'chi_tiet_id' => $existing['chi_tiet_id'],
                    ]
                );
            }

            $remainingProductUsage = $this->database->first(
                'SELECT COUNT(*) AS total
                 FROM ncc_san_pham_chi_tiet
                 WHERE ncc_id = :ncc_id AND san_pham_id = :san_pham_id',
                [
                    'ncc_id' => $existing['ncc_id'],
                    'san_pham_id' => $existing['san_pham_id'],
                ]
            );

            if ((int) ($remainingProductUsage['total'] ?? 0) === 0 && !empty($existing['san_pham_id'])) {
                $this->database->execute(
                    'DELETE FROM ncc_lien_ket_san_pham
                     WHERE ncc_id = :ncc_id AND san_pham_id = :san_pham_id',
                    [
                        'ncc_id' => $existing['ncc_id'],
                        'san_pham_id' => $existing['san_pham_id'],
                    ]
                );
            }

            $remainingDetailUsage = $this->database->first(
                'SELECT COUNT(*) AS total
                 FROM ncc_san_pham_chi_tiet
                 WHERE chi_tiet_id = :chi_tiet_id',
                ['chi_tiet_id' => $existing['chi_tiet_id']]
            );

            if ((int) ($remainingDetailUsage['total'] ?? 0) === 0) {
                $this->database->execute(
                    'DELETE FROM san_pham_lien_ket_chi_tiet WHERE chi_tiet_id = :chi_tiet_id',
                    ['chi_tiet_id' => $existing['chi_tiet_id']]
                );

                $this->database->execute(
                    'DELETE FROM san_pham_chi_tiet WHERE id = :chi_tiet_id',
                    ['chi_tiet_id' => $existing['chi_tiet_id']]
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

    public function priceHistory(int $id): array
    {
        $product = $this->findById($id);

        if (!$product) {
            return [];
        }

        try {
            return $this->database->query(
                'SELECT
                    gia,
                    DATE_FORMAT(thoi_gian_thay_doi, "%d/%m/%Y %H:%i") AS thoi_gian_thay_doi,
                    ghi_chu
                 FROM lich_su_gia_san_pham
                 WHERE ncc_id = :ncc_id AND chi_tiet_id = :chi_tiet_id
                 ORDER BY thoi_gian_thay_doi DESC, id DESC',
                [
                    'ncc_id' => $product['ncc_id'],
                    'chi_tiet_id' => $product['chi_tiet_id'],
                ]
            );
        } catch (Throwable $exception) {
            return [];
        }
    }

    public function priceHistoryRows(string $keyword = ''): array
    {
        $keyword = trim($keyword);
        $params = [];
        $where = '';

        if ($keyword !== '') {
            $params['keyword'] = '%' . $keyword . '%';
            $where = 'WHERE
                COALESCE(ct.ma_sku, "") LIKE :keyword
                OR ct.ten_chi_tiet LIKE :keyword
                OR n.ten_ncc LIKE :keyword
                OR COALESCE(ls.ghi_chu, "") LIKE :keyword';
        }

        return $this->database->query(
            'SELECT
                ls.id,
                COALESCE(ct.ma_sku, "") AS ma_sku,
                ct.ten_chi_tiet AS ten_san_pham_chi_tiet,
                n.ma_ncc,
                n.ten_ncc,
                ls.gia,
                COALESCE(ls.ghi_chu, "") AS ghi_chu,
                DATE_FORMAT(ls.thoi_gian_thay_doi, "%d/%m/%Y %H:%i") AS thoi_gian_thay_doi
             FROM lich_su_gia_san_pham ls
             INNER JOIN nha_cung_cap n ON n.id = ls.ncc_id
             INNER JOIN san_pham_chi_tiet ct ON ct.id = ls.chi_tiet_id
             LEFT JOIN ncc_san_pham_chi_tiet ps
                ON ps.ncc_id = ls.ncc_id
               AND ps.chi_tiet_id = ls.chi_tiet_id
             ' . $where . '
             GROUP BY
                ls.id,
                ct.ma_sku,
                ct.ten_chi_tiet,
                n.ma_ncc,
                n.ten_ncc,
                ls.gia,
                ls.ghi_chu,
                ls.thoi_gian_thay_doi
             ORDER BY ls.thoi_gian_thay_doi DESC, ls.id DESC',
            $params
        );
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

    public function detailOptions(): array
    {
        return $this->database->query(
            'SELECT id, ten_chi_tiet
             FROM san_pham_chi_tiet
             ORDER BY ten_chi_tiet ASC'
        );
    }

    public function hubDataByProductId(int $productId): array
    {
        $product = $this->findById($productId);

        if (!$product) {
            return [
                'media' => [],
                'content' => [],
                'cross_sell' => [],
                'up_sell' => [],
            ];
        }

        $chiTietId = (int) $product['chi_tiet_id'];

        return [
            'media' => $this->productMedia($chiTietId),
            'content' => $this->productContent($chiTietId),
            'cross_sell' => $this->productRelations($chiTietId, 'cross_sell'),
            'up_sell' => $this->productRelations($chiTietId, 'up_sell'),
        ];
    }

    public function paginateRelationRows(int $page = 1, int $perPage = 10, string $keyword = ''): array
    {
        $page = max(1, $page);
        $perPage = max(1, $perPage);
        $offset = ($page - 1) * $perPage;
        $keyword = trim($keyword);
        $params = [];
        $where = '';

        if ($keyword !== '') {
            $params['keyword'] = '%' . $keyword . '%';
            $where = 'WHERE COALESCE(ct.ma_sku, "") LIKE :keyword
                OR ct.ten_chi_tiet LIKE :keyword
                OR n.ten_ncc LIKE :keyword';
        }

        $baseFrom = '
             FROM ncc_san_pham_chi_tiet ps
             INNER JOIN san_pham_chi_tiet ct ON ct.id = ps.chi_tiet_id
             INNER JOIN nha_cung_cap n ON n.id = ps.ncc_id
        ';

        $totalRow = $this->database->first(
            'SELECT COUNT(*) AS total ' . $baseFrom . ' ' . $where,
            $params
        );

        $items = $this->database->query(
            'SELECT
                ps.id,
                COALESCE(ct.ma_sku, "") AS ma_sku,
                ct.ten_chi_tiet AS ten_san_pham_chi_tiet,
                n.ten_ncc,
                (
                    SELECT COUNT(*)
                    FROM lien_ket_san_pham pr
                    WHERE pr.source_chi_tiet_id = ps.chi_tiet_id
                      AND pr.relation_type = "cross_sell"
                ) AS so_cross_sell,
                (
                    SELECT COUNT(*)
                    FROM lien_ket_san_pham pr
                    WHERE pr.source_chi_tiet_id = ps.chi_tiet_id
                      AND pr.relation_type = "up_sell"
                ) AS so_up_sell
             ' . $baseFrom . '
             ' . $where . '
             ORDER BY ct.ten_chi_tiet ASC, n.ten_ncc ASC, ps.id ASC
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

    public function paginateMediaRows(int $page = 1, int $perPage = 10, string $keyword = ''): array
    {
        $page = max(1, $page);
        $perPage = max(1, $perPage);
        $offset = ($page - 1) * $perPage;
        $keyword = trim($keyword);
        $params = [];
        $where = '';

        if ($keyword !== '') {
            $params['keyword'] = '%' . $keyword . '%';
            $where = 'WHERE COALESCE(ct.ma_sku, "") LIKE :keyword
                OR ct.ten_chi_tiet LIKE :keyword
                OR n.ten_ncc LIKE :keyword';
        }

        $baseFrom = '
             FROM ncc_san_pham_chi_tiet ps
             INNER JOIN san_pham_chi_tiet ct ON ct.id = ps.chi_tiet_id
             INNER JOIN nha_cung_cap n ON n.id = ps.ncc_id
             LEFT JOIN tai_nguyen_media tm ON tm.chi_tiet_id = ps.chi_tiet_id
        ';

        $totalRow = $this->database->first(
            'SELECT COUNT(*) AS total
             FROM (
                SELECT ps.id
                ' . $baseFrom . '
                ' . $where . '
                GROUP BY ps.id
             ) media_rows',
            $params
        );

        $items = $this->database->query(
            'SELECT
                ps.id,
                COALESCE(ct.ma_sku, "") AS ma_sku,
                ct.ten_chi_tiet AS ten_san_pham_chi_tiet,
                n.ten_ncc,
                SUM(CASE WHEN tm.loai = "anh" THEN 1 ELSE 0 END) AS so_anh,
                SUM(CASE WHEN tm.loai = "video" THEN 1 ELSE 0 END) AS so_video,
                SUM(CASE WHEN tm.loai = "pdf" THEN 1 ELSE 0 END) AS so_pdf
             ' . $baseFrom . '
             ' . $where . '
             GROUP BY ps.id, ct.ma_sku, ct.ten_chi_tiet, n.ten_ncc
             ORDER BY ct.ten_chi_tiet ASC, n.ten_ncc ASC, ps.id ASC
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

    public function mediaDataByProductId(int $productId): array
    {
        $product = $this->findById($productId);

        if (!$product) {
            return [];
        }

        return [
            'product' => $product,
            'media' => $this->productMediaNew((int) $product['chi_tiet_id']),
        ];
    }

    public function updateMediaByProductId(int $productId, array $items): bool
    {
        $product = $this->findById($productId);

        if (!$product) {
            return false;
        }

        $chiTietId = (int) $product['chi_tiet_id'];
        $pdo = $this->database->pdo();

        try {
            $pdo->beginTransaction();
            $this->syncMediaNew($chiTietId, $items);
            $pdo->commit();

            return true;
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $exception;
        }
    }

    public function relationDataByProductId(int $productId): array
    {
        $product = $this->findById($productId);

        if (!$product) {
            return [];
        }

        $chiTietId = (int) $product['chi_tiet_id'];

        return [
            'product' => $product,
            'cross_sell' => $this->productRelations($chiTietId, 'cross_sell'),
            'up_sell' => $this->productRelations($chiTietId, 'up_sell'),
        ];
    }

    public function updateRelationsByProductId(int $productId, array $crossSellItems, array $upSellItems): bool
    {
        $product = $this->findById($productId);

        if (!$product) {
            return false;
        }

        $chiTietId = (int) $product['chi_tiet_id'];
        $pdo = $this->database->pdo();

        try {
            $pdo->beginTransaction();
            $this->syncRelations($chiTietId, 'cross_sell', $crossSellItems);
            $this->syncRelations($chiTietId, 'up_sell', $upSellItems);
            $pdo->commit();

            return true;
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $exception;
        }
    }

    public function skuExists(string $sku, ?int $ignoreId = null): bool
    {
        $params = ['ma_sku' => trim($sku)];
        $where = 'WHERE ma_sku = :ma_sku';

        if ($ignoreId !== null) {
            $where .= ' AND id <> :id';
            $params['id'] = $ignoreId;
        }

        $row = $this->database->first(
            'SELECT id
             FROM san_pham_chi_tiet
             ' . $where . '
             LIMIT 1',
            $params
        );

        return $row !== null;
    }

    private function normalizeProductId(?int $sanPhamId): ?int
    {
        if ($sanPhamId === null || $sanPhamId <= 0) {
            return null;
        }

        $row = $this->database->first(
            'SELECT id
             FROM san_pham_ncc
             WHERE id = :id
             LIMIT 1',
            ['id' => $sanPhamId]
        );

        return $row ? (int) $row['id'] : null;
    }

    private function resolveDetailId(string $detailName, ?string $maSku = null, ?string $thuongHieu = null, ?int $existingId = null): int
    {
        $detailName = trim($detailName);
        $maSku = trim((string) $maSku);
        $thuongHieuId = $this->resolveBrandId($thuongHieu);

        if ($existingId !== null && $existingId > 0) {
            $this->database->execute(
                'UPDATE san_pham_chi_tiet
                 SET ma_sku = :ma_sku,
                     ten_chi_tiet = :ten_chi_tiet,
                     thuong_hieu_id = :thuong_hieu_id,
                     ngay_cap_nhat = NOW()
                 WHERE id = :id',
                [
                    'id' => $existingId,
                    'ma_sku' => $maSku === '' ? null : $maSku,
                    'ten_chi_tiet' => $detailName,
                    'thuong_hieu_id' => $thuongHieuId,
                ]
            );

            return $existingId;
        }

        $existing = $this->database->first(
            'SELECT id
             FROM san_pham_chi_tiet
             WHERE ten_chi_tiet = :ten_chi_tiet
             LIMIT 1',
            ['ten_chi_tiet' => $detailName]
        );

        if ($existing) {
            $existingId = (int) $existing['id'];

            $this->database->execute(
                'UPDATE san_pham_chi_tiet
                 SET ma_sku = :ma_sku,
                     thuong_hieu_id = :thuong_hieu_id,
                     ngay_cap_nhat = NOW()
                 WHERE id = :id',
                [
                    'id' => $existingId,
                    'ma_sku' => $maSku === '' ? null : $maSku,
                    'thuong_hieu_id' => $thuongHieuId,
                ]
            );

            return $existingId;
        }

        $this->database->execute(
            'INSERT INTO san_pham_chi_tiet (ma_sku, ten_chi_tiet, thuong_hieu_id, ngay_cap_nhat)
             VALUES (:ma_sku, :ten_chi_tiet, :thuong_hieu_id, NOW())',
            [
                'ma_sku' => $maSku === '' ? null : $maSku,
                'ten_chi_tiet' => $detailName,
                'thuong_hieu_id' => $thuongHieuId,
            ]
        );

        return (int) $this->database->lastInsertId();
    }

    private function resolveBrandId(?string $brandName): ?int
    {
        $brandName = trim((string) $brandName);

        if ($brandName === '') {
            return null;
        }

        $existing = $this->database->first(
            'SELECT id
             FROM thuong_hieu
             WHERE ten_thuong_hieu = :ten_thuong_hieu
             LIMIT 1',
            ['ten_thuong_hieu' => $brandName]
        );

        if ($existing) {
            return (int) $existing['id'];
        }

        $this->database->execute(
            'INSERT INTO thuong_hieu (ten_thuong_hieu)
             VALUES (:ten_thuong_hieu)',
            ['ten_thuong_hieu' => $brandName]
        );

        return (int) $this->database->lastInsertId();
    }

    private function syncLegacyLinks(int $nccId, ?int $sanPhamId, int $chiTietId): void
    {
        $this->database->execute(
            'INSERT INTO ncc_lien_ket_chi_tiet (ncc_id, chi_tiet_id)
             VALUES (:ncc_id, :chi_tiet_id)
             ON DUPLICATE KEY UPDATE ncc_id = VALUES(ncc_id)',
            [
                'ncc_id' => $nccId,
                'chi_tiet_id' => $chiTietId,
            ]
        );

        if ($sanPhamId !== null && $sanPhamId > 0) {
            $this->database->execute(
                'INSERT INTO san_pham_lien_ket_chi_tiet (san_pham_id, chi_tiet_id)
                 VALUES (:san_pham_id, :chi_tiet_id)
                 ON DUPLICATE KEY UPDATE san_pham_id = VALUES(san_pham_id)',
                [
                    'san_pham_id' => $sanPhamId,
                    'chi_tiet_id' => $chiTietId,
                ]
            );

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
    }

    private function syncMedia(int $chiTietId, array $items): void
    {
        $this->database->execute(
            'DELETE FROM tai_nguyen_san_pham WHERE chi_tiet_id = :chi_tiet_id',
            ['chi_tiet_id' => $chiTietId]
        );

        $sortOrder = 0;
        foreach ($items as $item) {
            $title = trim((string) ($item['title'] ?? ''));
            $mediaUrl = trim((string) ($item['media_url'] ?? ''));
            $mediaType = trim((string) ($item['media_type'] ?? 'image'));

            if ($title === '' && $mediaUrl === '') {
                continue;
            }

            if ($title === '' || $mediaUrl === '') {
                continue;
            }

            if (!in_array($mediaType, ['image', 'video', 'pdf', 'link'], true)) {
                $mediaType = 'image';
            }

            $this->database->execute(
                'INSERT INTO tai_nguyen_san_pham (
                    chi_tiet_id,
                    media_type,
                    title,
                    media_url,
                    is_primary,
                    sort_order
                ) VALUES (
                    :chi_tiet_id,
                    :media_type,
                    :title,
                    :media_url,
                    :is_primary,
                    :sort_order
                )',
                [
                    'chi_tiet_id' => $chiTietId,
                    'media_type' => $mediaType,
                    'title' => $title,
                    'media_url' => $mediaUrl,
                    'is_primary' => !empty($item['is_primary']) ? 1 : 0,
                    'sort_order' => $sortOrder++,
                ]
            );
        }
    }

    private function syncContent(int $chiTietId, array $items): void
    {
        $this->database->execute(
            'DELETE FROM noi_dung_san_pham WHERE chi_tiet_id = :chi_tiet_id',
            ['chi_tiet_id' => $chiTietId]
        );

        foreach ($items as $item) {
            $title = trim((string) ($item['title'] ?? ''));
            $body = trim((string) ($item['content_body'] ?? ''));
            $contentType = trim((string) ($item['content_type'] ?? 'mo_ta'));

            if ($title === '' && $body === '') {
                continue;
            }

            if ($title === '' || $body === '') {
                continue;
            }

            if (!in_array($contentType, ['mo_ta', 'sales_kit', 'huong_dan', 'marketing_note'], true)) {
                $contentType = 'mo_ta';
            }

            $this->database->execute(
                'INSERT INTO noi_dung_san_pham (chi_tiet_id, content_type, title, content_body)
                 VALUES (:chi_tiet_id, :content_type, :title, :content_body)',
                [
                    'chi_tiet_id' => $chiTietId,
                    'content_type' => $contentType,
                    'title' => $title,
                    'content_body' => $body,
                ]
            );
        }
    }

    private function syncRelations(int $chiTietId, string $relationType, array $items): void
    {
        $this->database->execute(
            'DELETE FROM lien_ket_san_pham
             WHERE source_chi_tiet_id = :source_chi_tiet_id AND relation_type = :relation_type',
            [
                'source_chi_tiet_id' => $chiTietId,
                'relation_type' => $relationType,
            ]
        );

        $merged = [];
        foreach ($items as $item) {
            $targetId = (int) ($item['target_chi_tiet_id'] ?? 0);
            $score = max(1, (int) ($item['relation_score'] ?? 1));
            $note = trim((string) ($item['note'] ?? ''));

            if ($targetId <= 0 || $targetId === $chiTietId) {
                continue;
            }

            $merged[$targetId] = [
                'target_chi_tiet_id' => $targetId,
                'relation_score' => $score,
                'note' => $note,
            ];
        }

        foreach ($merged as $relation) {
            $this->database->execute(
                'INSERT INTO lien_ket_san_pham (
                    source_chi_tiet_id,
                    target_chi_tiet_id,
                    relation_type,
                    relation_score,
                    note
                ) VALUES (
                    :source_chi_tiet_id,
                    :target_chi_tiet_id,
                    :relation_type,
                    :relation_score,
                    :note
                )',
                [
                    'source_chi_tiet_id' => $chiTietId,
                    'target_chi_tiet_id' => $relation['target_chi_tiet_id'],
                    'relation_type' => $relationType,
                    'relation_score' => $relation['relation_score'],
                    'note' => $relation['note'] === '' ? null : $relation['note'],
                ]
            );
        }
    }

    private function syncMediaNew(int $chiTietId, array $items): void
    {
        foreach ($items as $item) {
            $loai = trim((string) ($item['loai'] ?? ''));
            $url = trim((string) ($item['url'] ?? ''));

            if (!in_array($loai, ['anh', 'video', 'pdf'], true)) {
                continue;
            }

            if ($url === '') {
                continue;
            }

            $existing = $this->database->first(
                'SELECT id
                 FROM tai_nguyen_media
                 WHERE chi_tiet_id = :chi_tiet_id AND loai = :loai
                 LIMIT 1',
                [
                    'chi_tiet_id' => $chiTietId,
                    'loai' => $loai,
                ]
            );

            if ($existing) {
                $this->database->execute(
                    'UPDATE tai_nguyen_media
                     SET url = :url,
                         updated_at = NOW()
                     WHERE id = :id',
                    [
                        'url' => $url,
                        'id' => (int) $existing['id'],
                    ]
                );
                continue;
            }

            $this->database->execute(
                'INSERT INTO tai_nguyen_media (ma_tai_nguyen, chi_tiet_id, url, loai, created_at, updated_at)
                 VALUES (:ma_tai_nguyen, :chi_tiet_id, :url, :loai, NOW(), NOW())',
                [
                    'ma_tai_nguyen' => $this->nextMediaCode(),
                    'chi_tiet_id' => $chiTietId,
                    'url' => $url,
                    'loai' => $loai,
                ]
            );
        }
    }

    private function productMedia(int $chiTietId): array
    {
        return $this->database->query(
            'SELECT id, media_type, title, media_url, is_primary, sort_order
             FROM tai_nguyen_san_pham
             WHERE chi_tiet_id = :chi_tiet_id
             ORDER BY sort_order ASC, id ASC',
            ['chi_tiet_id' => $chiTietId]
        );
    }

    private function productMediaNew(int $chiTietId): array
    {
        $rows = $this->database->query(
            'SELECT id, ma_tai_nguyen, loai, url
             FROM tai_nguyen_media
             WHERE chi_tiet_id = :chi_tiet_id
             ORDER BY id ASC',
            ['chi_tiet_id' => $chiTietId]
        );

        $result = [
            'anh' => null,
            'video' => null,
            'pdf' => null,
        ];

        foreach ($rows as $row) {
            $loai = (string) ($row['loai'] ?? '');
            if (isset($result[$loai])) {
                $result[$loai] = $row;
            }
        }

        return $result;
    }

    private function productContent(int $chiTietId): array
    {
        return $this->database->query(
            'SELECT id, content_type, title, content_body
             FROM noi_dung_san_pham
             WHERE chi_tiet_id = :chi_tiet_id
             ORDER BY id ASC',
            ['chi_tiet_id' => $chiTietId]
        );
    }

    private function productRelations(int $chiTietId, string $relationType): array
    {
        return $this->database->query(
            'SELECT
                pr.id,
                pr.target_chi_tiet_id,
                pr.relation_score,
                pr.note,
                ct.ten_chi_tiet AS target_name
             FROM lien_ket_san_pham pr
             INNER JOIN san_pham_chi_tiet ct ON ct.id = pr.target_chi_tiet_id
             WHERE pr.source_chi_tiet_id = :chi_tiet_id
               AND pr.relation_type = :relation_type
             ORDER BY pr.relation_score DESC, ct.ten_chi_tiet ASC',
            [
                'chi_tiet_id' => $chiTietId,
                'relation_type' => $relationType,
            ]
        );
    }

    private function nextProductCode(): string
    {
        $row = $this->database->first(
            'SELECT id
             FROM ncc_san_pham_chi_tiet
             ORDER BY id DESC
             LIMIT 1'
        );

        return 'SP-' . str_pad((string) (((int) ($row['id'] ?? 0)) + 1), 5, '0', STR_PAD_LEFT);
    }

    private function nextMediaCode(): string
    {
        return 'TNM-' . strtoupper(bin2hex(random_bytes(4)));
    }

    private function priceChanged(mixed $oldPrice, mixed $newPrice): bool
    {
        if ($oldPrice === null && $newPrice === null) {
            return false;
        }

        if ($oldPrice === null || $newPrice === null) {
            return true;
        }

        return (float) $oldPrice !== (float) $newPrice;
    }
}
