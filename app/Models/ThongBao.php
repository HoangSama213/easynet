<?php

namespace App\Models;

use App\Core\App;
use App\Core\Database;

class ThongBao
{
    private const MAX_NOTIFICATION_ITEMS = 50;

    private Database $database;

    public function __construct()
    {
        $this->database = App::get('db');
    }

    public function dongBoTonKho(): void
    {
        $rows = $this->database->query(
            'SELECT
                ct.id AS chi_tiet_id,
                ct.ten_chi_tiet,
                MIN(COALESCE(ct.ma_sku, "")) AS ma_sku,
                SUM(COALESCE(ps.ton_kho, 0)) AS ton_kho_hien_tai
             FROM san_pham_chi_tiet ct
             INNER JOIN ncc_san_pham_chi_tiet ps ON ps.chi_tiet_id = ct.id
             GROUP BY ct.id, ct.ten_chi_tiet'
        );

        $desiredByProduct = [];

        foreach ($rows as $row) {
            $chiTietId = (int) ($row['chi_tiet_id'] ?? 0);
            $tonKho = (int) ($row['ton_kho_hien_tai'] ?? 0);

            if ($chiTietId <= 0) {
                continue;
            }

            $payload = $this->xacDinhThongBaoTonKho($row, $tonKho);
            if ($payload === null) {
                continue;
            }

            $desiredByProduct[$chiTietId] = $payload;
        }

        $existingAlerts = $this->database->query(
            'SELECT id, chi_tiet_id, loai_thong_bao, muc_do, tieu_de, noi_dung, ton_kho_hien_tai, trang_thai
             FROM thong_bao
             WHERE chi_tiet_id IS NOT NULL
               AND loai_thong_bao IN ("het_hang", "sap_het_hang", "ton_kho_thap")'
        );

        foreach ($existingAlerts as $existing) {
            $chiTietId = (int) ($existing['chi_tiet_id'] ?? 0);

            if (!isset($desiredByProduct[$chiTietId])) {
                $this->database->execute(
                    'DELETE FROM thong_bao WHERE id = :id',
                    ['id' => (int) $existing['id']]
                );
            }
        }

        foreach ($desiredByProduct as $chiTietId => $payload) {
            $this->database->execute(
                'DELETE FROM thong_bao
                 WHERE chi_tiet_id = :chi_tiet_id
                   AND loai_thong_bao IN ("het_hang", "sap_het_hang", "ton_kho_thap")
                   AND loai_thong_bao <> :loai_thong_bao',
                [
                    'chi_tiet_id' => $chiTietId,
                    'loai_thong_bao' => $payload['loai_thong_bao'],
                ]
            );

            $existing = $this->database->first(
                'SELECT id, muc_do, tieu_de, noi_dung, ton_kho_hien_tai, trang_thai
                 FROM thong_bao
                 WHERE chi_tiet_id = :chi_tiet_id
                   AND loai_thong_bao = :loai_thong_bao
                 LIMIT 1',
                [
                    'chi_tiet_id' => $chiTietId,
                    'loai_thong_bao' => $payload['loai_thong_bao'],
                ]
            );

            if (!$existing) {
                $this->database->execute(
                    'INSERT INTO thong_bao (
                        chi_tiet_id,
                        loai_thong_bao,
                        muc_do,
                        tieu_de,
                        noi_dung,
                        ton_kho_hien_tai,
                        trang_thai,
                        ngay_tao,
                        updated_at
                    ) VALUES (
                        :chi_tiet_id,
                        :loai_thong_bao,
                        :muc_do,
                        :tieu_de,
                        :noi_dung,
                        :ton_kho_hien_tai,
                        "chua_xem",
                        NOW(),
                        NOW()
                    )',
                    $payload
                );
                continue;
            }

            $hasChanged =
                (string) ($existing['muc_do'] ?? '') !== (string) $payload['muc_do']
                || (string) ($existing['tieu_de'] ?? '') !== (string) $payload['tieu_de']
                || (string) ($existing['noi_dung'] ?? '') !== (string) $payload['noi_dung']
                || (int) ($existing['ton_kho_hien_tai'] ?? 0) !== (int) $payload['ton_kho_hien_tai'];

            $this->database->execute(
                'UPDATE thong_bao
                 SET muc_do = :muc_do,
                     tieu_de = :tieu_de,
                     noi_dung = :noi_dung,
                     ton_kho_hien_tai = :ton_kho_hien_tai,
                     trang_thai = :trang_thai,
                     ngay_tao = CASE WHEN :lam_moi = 1 THEN NOW() ELSE ngay_tao END,
                     updated_at = NOW()
                 WHERE id = :id',
                [
                    'id' => (int) $existing['id'],
                    'muc_do' => $payload['muc_do'],
                    'tieu_de' => $payload['tieu_de'],
                    'noi_dung' => $payload['noi_dung'],
                    'ton_kho_hien_tai' => $payload['ton_kho_hien_tai'],
                    'trang_thai' => $hasChanged ? 'chua_xem' : (string) ($existing['trang_thai'] ?? 'chua_xem'),
                    'lam_moi' => $hasChanged ? 1 : 0,
                ]
            );
        }
    }

    public function topbarData(): array
    {
        $rows = $this->database->query(
            'SELECT
                id,
                loai_thong_bao,
                muc_do,
                tieu_de,
                noi_dung,
                trang_thai,
                DATE_FORMAT(ngay_tao, "%d/%m/%Y %H:%i") AS ngay_tao_hien_thi
             FROM thong_bao
             ORDER BY ngay_tao DESC, id DESC
             LIMIT 5'
        );

        $moi = [];
        $truocDo = [];

        foreach ($rows as $row) {
            $row['mo_ta_ngan'] = $this->rutGonNoiDung((string) ($row['noi_dung'] ?? ''));

            if (($row['trang_thai'] ?? 'chua_xem') === 'chua_xem') {
                $moi[] = $row;
                continue;
            }

            $truocDo[] = $row;
        }

        return [
            'so_chua_doc' => $this->soChuaDoc(),
            'moi' => $moi,
            'truoc_do' => $truocDo,
        ];
    }

    public function paginate(int $page = 1, int $perPage = 12): array
    {
        $page = max(1, $page);
        $perPage = max(1, $perPage);
        $perPage = min($perPage, self::MAX_NOTIFICATION_ITEMS);
        $offset = ($page - 1) * $perPage;

        $total = (int) (($this->database->first('SELECT LEAST(COUNT(*), ' . self::MAX_NOTIFICATION_ITEMS . ') AS total FROM thong_bao')['total'] ?? 0));

        if ($offset >= self::MAX_NOTIFICATION_ITEMS) {
            return [
                'items' => [],
                'total' => $total,
                'page' => $page,
                'per_page' => $perPage,
                'last_page' => max(1, (int) ceil($total / $perPage)),
            ];
        }

        $limit = min($perPage, self::MAX_NOTIFICATION_ITEMS - $offset);

        $items = $this->database->query(
            'SELECT
                tb.id,
                tb.loai_thong_bao,
                tb.muc_do,
                tb.tieu_de,
                tb.noi_dung,
                tb.trang_thai,
                tb.ton_kho_hien_tai,
                tb.chi_tiet_id,
                DATE_FORMAT(tb.ngay_tao, "%d/%m/%Y %H:%i") AS ngay_tao_hien_thi,
                COALESCE(ct.ten_chi_tiet, "") AS ten_chi_tiet
             FROM (
                SELECT *
                FROM thong_bao
                ORDER BY ngay_tao DESC, id DESC
                LIMIT ' . self::MAX_NOTIFICATION_ITEMS . '
             ) tb
             LEFT JOIN san_pham_chi_tiet ct ON ct.id = tb.chi_tiet_id
             ORDER BY tb.ngay_tao DESC, tb.id DESC
             LIMIT ' . $limit . ' OFFSET ' . $offset
        );

        foreach ($items as &$item) {
            $item['mo_ta_ngan'] = $this->rutGonNoiDung((string) ($item['noi_dung'] ?? ''), 140);
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

    public function danhDauTatCaDaDoc(): void
    {
        $this->database->execute(
            'UPDATE thong_bao
             SET trang_thai = "da_xem",
                 updated_at = NOW()
             WHERE trang_thai = "chua_xem"'
        );
    }

    public function danhDauDaDoc(int $id): void
    {
        if ($id <= 0) {
            return;
        }

        $this->database->execute(
            'UPDATE thong_bao
             SET trang_thai = "da_xem",
                 updated_at = NOW()
             WHERE id = :id',
            ['id' => $id]
        );
    }

    public function soChuaDoc(): int
    {
        $row = $this->database->first(
            'SELECT COUNT(*) AS total
             FROM thong_bao
             WHERE trang_thai = "chua_xem"'
        );

        return (int) ($row['total'] ?? 0);
    }

    private function xacDinhThongBaoTonKho(array $row, int $tonKho): ?array
    {
        $maSku = trim((string) ($row['ma_sku'] ?? ''));
        $tenChiTiet = trim((string) ($row['ten_chi_tiet'] ?? 'Sản phẩm'));
        $prefix = $maSku !== '' ? ($maSku . ' - ') : '';

        if ($tonKho <= 0) {
            return [
                'chi_tiet_id' => (int) $row['chi_tiet_id'],
                'loai_thong_bao' => 'het_hang',
                'muc_do' => 'critical',
                'tieu_de' => 'Sản phẩm đã hết hàng',
                'noi_dung' => $prefix . $tenChiTiet . ' hiện đã hết hàng trong kho.',
                'ton_kho_hien_tai' => $tonKho,
            ];
        }

        if ($tonKho <= 5) {
            return [
                'chi_tiet_id' => (int) $row['chi_tiet_id'],
                'loai_thong_bao' => 'sap_het_hang',
                'muc_do' => 'warning',
                'tieu_de' => 'Sản phẩm sắp hết hàng',
                'noi_dung' => $prefix . $tenChiTiet . ' chỉ còn ' . $tonKho . ' sản phẩm trong kho.',
                'ton_kho_hien_tai' => $tonKho,
            ];
        }

        if ($tonKho <= 10) {
            return [
                'chi_tiet_id' => (int) $row['chi_tiet_id'],
                'loai_thong_bao' => 'ton_kho_thap',
                'muc_do' => 'info',
                'tieu_de' => 'Tồn kho thấp',
                'noi_dung' => $prefix . $tenChiTiet . ' đang ở mức tồn kho thấp: ' . $tonKho . ' sản phẩm.',
                'ton_kho_hien_tai' => $tonKho,
            ];
        }

        return null;
    }

    private function rutGonNoiDung(string $content, int $limit = 90): string
    {
        $content = trim(preg_replace('/\s+/', ' ', $content) ?? '');

        if ($content === '') {
            return '';
        }

        if (function_exists('mb_strimwidth')) {
            return mb_strimwidth($content, 0, $limit, '...', 'UTF-8');
        }

        if (strlen($content) <= $limit) {
            return $content;
        }

        return substr($content, 0, max(0, $limit - 3)) . '...';
    }
}
