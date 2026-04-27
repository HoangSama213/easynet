<?php

namespace App\Models;

use App\Core\App;
use App\Core\Database;
use Throwable;

class Combo
{
    private Database $database;

    public function __construct()
    {
        $this->database = App::get('db');
    }

    public function paginate(int $page = 1, int $perPage = 10, string $keyword = ''): array
    {
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;
        $params = [];
        $where = '';

        if (trim($keyword) !== '') {
            $params['kw'] = '%' . trim($keyword) . '%';
            $where = 'WHERE c.ma_combo LIKE :kw OR c.ten_combo LIKE :kw';
        }

        $total = (int) ($this->database->first(
            'SELECT COUNT(*) AS total FROM bo_san_pham c ' . $where,
            $params
        )['total'] ?? 0);

        $items = $this->database->query(
            'SELECT
                c.id,
                c.ma_combo,
                c.ten_combo,
                c.gia_le,
                c.gia_combo,
                c.trang_thai,
                DATE_FORMAT(c.updated_at, "%d/%m/%Y") AS cap_nhat,
                COUNT(ci.id) AS so_san_pham,
                COUNT(DISTINCT cc.id) AS so_chien_dich
             FROM bo_san_pham c
             LEFT JOIN chi_tiet_bo_san_pham ci ON ci.combo_id = c.id
             LEFT JOIN chien_dich_bo_san_pham cc ON cc.combo_id = c.id
             ' . $where . '
             GROUP BY c.id
             ORDER BY c.created_at DESC
             LIMIT ' . $perPage . ' OFFSET ' . $offset,
            $params
        );

        foreach ($items as &$item) {
            $item['ton_kho_ao'] = $this->virtualStock((int) $item['id']);
        }
        unset($item);

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
        $combo = $this->database->first(
            'SELECT id, ma_combo, ten_combo, mo_ta, gia_le, gia_combo, trang_thai
             FROM bo_san_pham WHERE id = :id LIMIT 1',
            ['id' => $id]
        );

        if (!$combo) {
            return null;
        }

        $combo['items'] = $this->database->query(
            'SELECT
                ci.id,
                ci.chi_tiet_id,
                ci.so_luong,
                ct.ten_chi_tiet,
                COALESCE(stock.gia_tham_chieu, 0) AS gia,
                COALESCE(stock.ton_kho_thuc_te, 0) AS ton_kho,
                COALESCE(stock.gia_tham_chieu, 0) * ci.so_luong AS thanh_tien
             FROM chi_tiet_bo_san_pham ci
             INNER JOIN san_pham_chi_tiet ct ON ct.id = ci.chi_tiet_id
             LEFT JOIN (
                SELECT
                    chi_tiet_id,
                    SUM(COALESCE(ton_kho, 0)) AS ton_kho_thuc_te,
                    MIN(COALESCE(gia, 0)) AS gia_tham_chieu
                FROM ncc_san_pham_chi_tiet
                GROUP BY chi_tiet_id
             ) stock ON stock.chi_tiet_id = ci.chi_tiet_id
             WHERE ci.combo_id = :combo_id
             ORDER BY ci.id ASC',
            ['combo_id' => $id]
        );

        $combo['campaigns'] = $this->campaigns($id);
        $combo['proposal_history'] = $this->proposalHistory($id);
        $combo['ton_kho_ao'] = $this->virtualStock($id);
        $combo['goi_y_combo'] = $this->comboSuggestions($id, 5);

        return $combo;
    }

    public function create(array $payload): int
    {
        $pdo = $this->database->pdo();

        try {
            $pdo->beginTransaction();

            $this->database->execute(
                'INSERT INTO bo_san_pham (ma_combo, ten_combo, mo_ta, gia_combo, trang_thai)
                 VALUES (:ma_combo, :ten_combo, :mo_ta, :gia_combo, :trang_thai)',
                [
                    'ma_combo' => 'CB-TEMP-' . uniqid(),
                    'ten_combo' => trim($payload['ten_combo']),
                    'mo_ta' => $payload['mo_ta'] ?? null,
                    'gia_combo' => $payload['gia_combo'] ?? null,
                    'trang_thai' => $payload['trang_thai'] ?? 'active',
                ]
            );

            $comboId = (int) $this->database->lastInsertId();
            $this->database->execute(
                'UPDATE bo_san_pham SET ma_combo = :ma_combo WHERE id = :id',
                [
                    'ma_combo' => 'CB-' . str_pad((string) $comboId, 4, '0', STR_PAD_LEFT),
                    'id' => $comboId,
                ]
            );

            $this->syncItems($comboId, $payload['items'] ?? []);
            $this->recalcGiaLe($comboId);

            $pdo->commit();

            return $comboId;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    public function update(int $id, array $payload): bool
    {
        $pdo = $this->database->pdo();

        try {
            $pdo->beginTransaction();

            $this->database->execute(
                'UPDATE bo_san_pham
                 SET ten_combo = :ten_combo,
                     mo_ta = :mo_ta,
                     gia_combo = :gia_combo,
                     trang_thai = :trang_thai
                 WHERE id = :id',
                [
                    'ten_combo' => trim($payload['ten_combo']),
                    'mo_ta' => $payload['mo_ta'] ?? null,
                    'gia_combo' => $payload['gia_combo'] ?? null,
                    'trang_thai' => $payload['trang_thai'] ?? 'active',
                    'id' => $id,
                ]
            );

            $this->syncItems($id, $payload['items'] ?? []);
            $this->recalcGiaLe($id);

            $pdo->commit();

            return true;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        $this->database->execute(
            'DELETE FROM bo_san_pham WHERE id = :id',
            ['id' => $id]
        );

        return true;
    }

    public function productOptions(): array
    {
        return $this->database->query(
            'SELECT
                ct.id,
                ct.ten_chi_tiet,
                COALESCE(stock.gia_tham_chieu, 0) AS gia,
                COALESCE(stock.ton_kho_thuc_te, 0) AS ton_kho
             FROM san_pham_chi_tiet ct
             LEFT JOIN (
                SELECT
                    chi_tiet_id,
                    SUM(COALESCE(ton_kho, 0)) AS ton_kho_thuc_te,
                    MIN(COALESCE(gia, 0)) AS gia_tham_chieu
                FROM ncc_san_pham_chi_tiet
                GROUP BY chi_tiet_id
             ) stock ON stock.chi_tiet_id = ct.id
             ORDER BY ct.ten_chi_tiet ASC'
        );
    }

    public function comboOptions(): array
    {
        return $this->database->query(
            'SELECT id, ma_combo, ten_combo
             FROM bo_san_pham
             ORDER BY ten_combo ASC, id ASC'
        );
    }

    public function comboExists(int $id): bool
    {
        $row = $this->database->first(
            'SELECT id FROM bo_san_pham WHERE id = :id LIMIT 1',
            ['id' => $id]
        );

        return $row !== null;
    }

    public function analyticsSummary(): array
    {
        $rows = $this->database->query(
            'SELECT
                c.id,
                c.ma_combo,
                c.ten_combo,
                c.gia_le,
                c.gia_combo,
                c.trang_thai,
                COUNT(DISTINCT ci.id) AS so_san_pham,
                COUNT(DISTINCT cc.id) AS so_chien_dich
             FROM bo_san_pham c
             LEFT JOIN chi_tiet_bo_san_pham ci ON ci.combo_id = c.id
             LEFT JOIN chien_dich_bo_san_pham cc ON cc.combo_id = c.id
             GROUP BY c.id
             ORDER BY c.updated_at DESC'
        );

        foreach ($rows as &$row) {
            $tonKhoAo = $this->virtualStock((int) $row['id']);
            $giaLe = (float) ($row['gia_le'] ?? 0);
            $giaCombo = (float) ($row['gia_combo'] ?? 0);
            $row['ton_kho_ao'] = $tonKhoAo;
            $row['tiet_kiem'] = max(0, $giaLe - $giaCombo);
            $row['gia_tri_tiem_nang'] = $tonKhoAo * $giaCombo;
            $row['goi_y'] = $this->comboSuggestions((int) $row['id'], 3);
        }
        unset($row);

        return $rows;
    }

    public function salesRows(string $keyword = ''): array
    {
        $keyword = trim($keyword);
        $params = [];
        $where = '';

        if ($keyword !== '') {
            $params['keyword'] = '%' . $keyword . '%';
            $where = 'WHERE
                c.ma_combo LIKE :keyword
                OR c.ten_combo LIKE :keyword
                OR COALESCE(c.mo_ta, "") LIKE :keyword
                OR EXISTS (
                    SELECT 1
                    FROM chi_tiet_bo_san_pham ci
                    INNER JOIN san_pham_chi_tiet ct ON ct.id = ci.chi_tiet_id
                    WHERE ci.combo_id = c.id
                      AND ct.ten_chi_tiet LIKE :keyword
                )
                OR EXISTS (
                    SELECT 1
                    FROM chien_dich_bo_san_pham cc
                    WHERE cc.combo_id = c.id
                      AND cc.ten_chien_dich LIKE :keyword
                )
                OR EXISTS (
                    SELECT 1
                    FROM chi_tiet_bo_san_pham ci
                    INNER JOIN noi_dung_san_pham pc ON pc.chi_tiet_id = ci.chi_tiet_id
                    WHERE ci.combo_id = c.id
                      AND (
                        pc.title LIKE :keyword
                        OR pc.content_body LIKE :keyword
                      )
                )';
        }

        $rows = $this->database->query(
            'SELECT
                c.id,
                c.ma_combo,
                c.ten_combo,
                c.mo_ta,
                c.gia_le,
                c.gia_combo,
                c.trang_thai,
                COUNT(DISTINCT ci.id) AS so_san_pham,
                GROUP_CONCAT(DISTINCT ct.ten_chi_tiet ORDER BY ct.ten_chi_tiet SEPARATOR ", ") AS ten_san_pham,
                GROUP_CONCAT(DISTINCT cc.ten_chien_dich ORDER BY cc.ten_chien_dich SEPARATOR ", ") AS ten_chien_dich
             FROM bo_san_pham c
             LEFT JOIN chi_tiet_bo_san_pham ci ON ci.combo_id = c.id
             LEFT JOIN san_pham_chi_tiet ct ON ct.id = ci.chi_tiet_id
             LEFT JOIN chien_dich_bo_san_pham cc ON cc.combo_id = c.id
             ' . $where . '
             GROUP BY c.id
             ORDER BY c.updated_at DESC, c.id DESC',
            $params
        );

        foreach ($rows as &$row) {
            $comboId = (int) $row['id'];
            $tonKhoAo = $this->virtualStock($comboId);
            $row['ton_kho_ao'] = $tonKhoAo;
            $row['tiet_kiem'] = max(0, (float) ($row['gia_le'] ?? 0) - (float) ($row['gia_combo'] ?? 0));
            $row['canh_bao'] = $this->availabilityWarnings($comboId);
            $row['goi_y'] = $this->comboSuggestions($comboId, 3);
        }
        unset($row);

        return $rows;
    }

    public function campaignRows(string $keyword = ''): array
    {
        $keyword = trim($keyword);
        $params = [];
        $where = '';

        if ($keyword !== '') {
            $params['keyword'] = '%' . $keyword . '%';
            $where = 'WHERE cc.ten_chien_dich LIKE :keyword OR c.ten_combo LIKE :keyword OR c.ma_combo LIKE :keyword';
        }

        return $this->database->query(
            'SELECT
                cc.id,
                cc.ten_chien_dich,
                cc.ghi_chu,
                cc.bat_dau,
                cc.ket_thuc,
                c.id AS combo_id,
                c.ma_combo,
                c.ten_combo,
                c.trang_thai
             FROM chien_dich_bo_san_pham cc
             INNER JOIN bo_san_pham c ON c.id = cc.combo_id
             ' . $where . '
             ORDER BY COALESCE(cc.bat_dau, "9999-12-31") ASC, cc.id DESC',
            $params
        );
    }

    public function campaignFindById(int $id): ?array
    {
        return $this->database->first(
            'SELECT
                cc.id,
                cc.combo_id,
                cc.ten_chien_dich,
                cc.ghi_chu,
                cc.bat_dau,
                cc.ket_thuc,
                c.ma_combo,
                c.ten_combo
             FROM chien_dich_bo_san_pham cc
             INNER JOIN bo_san_pham c ON c.id = cc.combo_id
             WHERE cc.id = :id
             LIMIT 1',
            ['id' => $id]
        );
    }

    public function createCampaign(array $payload): int
    {
        $this->database->execute(
            'INSERT INTO chien_dich_bo_san_pham (combo_id, ten_chien_dich, ghi_chu, bat_dau, ket_thuc)
             VALUES (:combo_id, :ten_chien_dich, :ghi_chu, :bat_dau, :ket_thuc)',
            [
                'combo_id' => $payload['combo_id'],
                'ten_chien_dich' => $payload['ten_chien_dich'],
                'ghi_chu' => $payload['ghi_chu'] ?? null,
                'bat_dau' => $payload['bat_dau'] ?? null,
                'ket_thuc' => $payload['ket_thuc'] ?? null,
            ]
        );

        return (int) $this->database->lastInsertId();
    }

    public function updateCampaign(int $id, array $payload): bool
    {
        $this->database->execute(
            'UPDATE chien_dich_bo_san_pham
             SET combo_id = :combo_id,
                 ten_chien_dich = :ten_chien_dich,
                 ghi_chu = :ghi_chu,
                 bat_dau = :bat_dau,
                 ket_thuc = :ket_thuc
             WHERE id = :id',
            [
                'id' => $id,
                'combo_id' => $payload['combo_id'],
                'ten_chien_dich' => $payload['ten_chien_dich'],
                'ghi_chu' => $payload['ghi_chu'] ?? null,
                'bat_dau' => $payload['bat_dau'] ?? null,
                'ket_thuc' => $payload['ket_thuc'] ?? null,
            ]
        );

        return true;
    }

    public function deleteCampaign(int $id): bool
    {
        $this->database->execute(
            'DELETE FROM chien_dich_bo_san_pham WHERE id = :id',
            ['id' => $id]
        );

        return true;
    }

    public function buildProposal(int $comboId, ?string $customerName = null, ?string $customerChannel = null): array
    {
        $combo = $this->findById($comboId);

        if (!$combo) {
            return [];
        }

        $campaign = $combo['campaigns'][0]['ten_chien_dich'] ?? null;
        $salesKit = $this->database->query(
            'SELECT pc.title, pc.content_body
             FROM chi_tiet_bo_san_pham ci
             INNER JOIN noi_dung_san_pham pc ON pc.chi_tiet_id = ci.chi_tiet_id
             WHERE ci.combo_id = :combo_id
               AND pc.content_type IN ("sales_kit", "mo_ta")
             ORDER BY FIELD(pc.content_type, "sales_kit", "mo_ta"), pc.id ASC
             LIMIT 3',
            ['combo_id' => $comboId]
        );

        $title = 'Đề xuất ' . ($combo['ten_combo'] ?? 'combo');
        $bodyLines = [];
        $bodyLines[] = 'Combo gồm ' . count($combo['items']) . ' sản phẩm với giá ưu đãi ' . number_format((float) ($combo['gia_combo'] ?? 0), 0, ',', '.') . ' đ.';

        if (!empty($combo['mo_ta'])) {
            $bodyLines[] = trim((string) $combo['mo_ta']);
        }

        if ($campaign) {
            $bodyLines[] = 'Chiến dịch áp dụng: ' . $campaign . '.';
        }

        foreach ($salesKit as $kit) {
            $bodyLines[] = trim((string) ($kit['title'] ?? '')) . ': ' . trim((string) ($kit['content_body'] ?? ''));
        }

        $bodyLines[] = 'Tồn kho combo khả dụng hiện tại: ' . $combo['ton_kho_ao'] . '.';
        $bodyLines[] = 'Mức tiết kiệm tham chiếu: ' . number_format(max(0, (float) ($combo['gia_le'] ?? 0) - (float) ($combo['gia_combo'] ?? 0)), 0, ',', '.') . ' đ.';

        return [
            'title' => $title,
            'body' => implode("\n\n", array_filter($bodyLines)),
            'customer_name' => $customerName,
            'customer_channel' => $customerChannel,
            'combo' => $combo,
        ];
    }

    public function availabilityWarnings(int $comboId): array
    {
        return $this->database->query(
            'SELECT
                ct.ten_chi_tiet,
                ci.so_luong,
                COALESCE(stock.ton_kho_thuc_te, 0) AS ton_kho,
                CASE
                    WHEN COALESCE(stock.ton_kho_thuc_te, 0) <= 0 THEN "Hết hàng"
                    WHEN COALESCE(stock.ton_kho_thuc_te, 0) < ci.so_luong THEN "Không đủ cho 1 combo"
                    WHEN COALESCE(stock.ton_kho_thuc_te, 0) <= (ci.so_luong * 3) THEN "Sắp hết"
                    ELSE "Ổn định"
                END AS muc_canh_bao
             FROM chi_tiet_bo_san_pham ci
             INNER JOIN san_pham_chi_tiet ct ON ct.id = ci.chi_tiet_id
             LEFT JOIN (
                SELECT chi_tiet_id, SUM(COALESCE(ton_kho, 0)) AS ton_kho_thuc_te
                FROM ncc_san_pham_chi_tiet
                GROUP BY chi_tiet_id
             ) stock ON stock.chi_tiet_id = ci.chi_tiet_id
             WHERE ci.combo_id = :combo_id
               AND COALESCE(stock.ton_kho_thuc_te, 0) <= (ci.so_luong * 3)
             ORDER BY COALESCE(stock.ton_kho_thuc_te, 0) ASC, ct.ten_chi_tiet ASC',
            ['combo_id' => $comboId]
        );
    }

    public function saveProposal(int $comboId, string $proposalTitle, string $proposalBody, ?string $customerName, ?string $customerChannel): void
    {
        $this->database->execute(
            'INSERT INTO bao_gia_bo_san_pham (
                combo_id,
                proposal_title,
                proposal_body,
                customer_name,
                customer_channel
            ) VALUES (
                :combo_id,
                :proposal_title,
                :proposal_body,
                :customer_name,
                :customer_channel
            )',
            [
                'combo_id' => $comboId,
                'proposal_title' => $proposalTitle,
                'proposal_body' => $proposalBody,
                'customer_name' => $customerName,
                'customer_channel' => $customerChannel,
            ]
        );
    }

    private function proposalHistory(int $comboId): array
    {
        return $this->database->query(
            'SELECT
                proposal_title,
                customer_name,
                customer_channel,
                DATE_FORMAT(created_at, "%d/%m/%Y %H:%i") AS created_at
             FROM bao_gia_bo_san_pham
             WHERE combo_id = :combo_id
             ORDER BY id DESC
             LIMIT 5',
            ['combo_id' => $comboId]
        );
    }

    private function campaigns(int $comboId): array
    {
        return $this->database->query(
            'SELECT
                id,
                ten_chien_dich,
                ghi_chu,
                bat_dau,
                ket_thuc
             FROM chien_dich_bo_san_pham
             WHERE combo_id = :combo_id
             ORDER BY id ASC',
            ['combo_id' => $comboId]
        );
    }

    public function virtualStock(int $comboId): int
    {
        $row = $this->database->first(
            'SELECT
                MIN(
                    FLOOR(
                        COALESCE(stock.ton_kho_thuc_te, 0) / NULLIF(ci.so_luong, 0)
                    )
                ) AS ton_kho_ao
             FROM chi_tiet_bo_san_pham ci
             LEFT JOIN (
                SELECT chi_tiet_id, SUM(COALESCE(ton_kho, 0)) AS ton_kho_thuc_te
                FROM ncc_san_pham_chi_tiet
                GROUP BY chi_tiet_id
             ) stock ON stock.chi_tiet_id = ci.chi_tiet_id
             WHERE ci.combo_id = :combo_id',
            ['combo_id' => $comboId]
        );

        return max(0, (int) ($row['ton_kho_ao'] ?? 0));
    }

    public function comboSuggestions(int $comboId, int $limit = 5): array
    {
        return $this->database->query(
            'SELECT
                pr.target_chi_tiet_id,
                ct.ten_chi_tiet,
                pr.relation_type,
                SUM(pr.relation_score) AS tong_diem
             FROM chi_tiet_bo_san_pham ci
             INNER JOIN lien_ket_san_pham pr ON pr.source_chi_tiet_id = ci.chi_tiet_id
             INNER JOIN san_pham_chi_tiet ct ON ct.id = pr.target_chi_tiet_id
             LEFT JOIN chi_tiet_bo_san_pham ci_existing
                ON ci_existing.combo_id = ci.combo_id
               AND ci_existing.chi_tiet_id = pr.target_chi_tiet_id
             WHERE ci.combo_id = :combo_id
               AND ci_existing.id IS NULL
             GROUP BY pr.target_chi_tiet_id, pr.relation_type, ct.ten_chi_tiet
             ORDER BY tong_diem DESC, ct.ten_chi_tiet ASC
             LIMIT ' . max(1, $limit),
            ['combo_id' => $comboId]
        );
    }

    private function syncItems(int $comboId, array $items): void
    {
        $this->database->execute(
            'DELETE FROM chi_tiet_bo_san_pham WHERE combo_id = :combo_id',
            ['combo_id' => $comboId]
        );

        $merged = [];
        foreach ($items as $item) {
            $chiTietId = (int) ($item['chi_tiet_id'] ?? 0);
            $soLuong = max(1, (int) ($item['so_luong'] ?? 1));
            if ($chiTietId <= 0) {
                continue;
            }
            if (isset($merged[$chiTietId])) {
                $merged[$chiTietId] += $soLuong;
            } else {
                $merged[$chiTietId] = $soLuong;
            }
        }

        foreach ($merged as $chiTietId => $soLuong) {
            $this->database->execute(
                'INSERT INTO chi_tiet_bo_san_pham (combo_id, chi_tiet_id, so_luong)
                 VALUES (:combo_id, :chi_tiet_id, :so_luong)',
                [
                    'combo_id' => $comboId,
                    'chi_tiet_id' => $chiTietId,
                    'so_luong' => $soLuong,
                ]
            );
        }
    }

    private function recalcGiaLe(int $comboId): void
    {
        $this->database->execute(
            'UPDATE bo_san_pham c
             SET c.gia_le = (
                 SELECT COALESCE(SUM(COALESCE(stock.gia_tham_chieu, 0) * ci.so_luong), 0)
                 FROM chi_tiet_bo_san_pham ci
                 LEFT JOIN (
                    SELECT chi_tiet_id, MIN(COALESCE(gia, 0)) AS gia_tham_chieu
                    FROM ncc_san_pham_chi_tiet
                    GROUP BY chi_tiet_id
                 ) stock ON stock.chi_tiet_id = ci.chi_tiet_id
                 WHERE ci.combo_id = c.id
             )
             WHERE c.id = :id',
            ['id' => $comboId]
        );
    }

    private function nextComboCode(): string
    {
        return 'PENDING';
    }
}
