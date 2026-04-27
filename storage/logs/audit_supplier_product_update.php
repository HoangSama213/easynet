<?php

require __DIR__ . '/../../app/Helpers/helpers.php';
require __DIR__ . '/../../app/Core/App.php';
require __DIR__ . '/../../app/Core/Database.php';
require __DIR__ . '/../../app/Models/SupplierProduct.php';

$config = require __DIR__ . '/../../config/database.php';
\App\Core\App::set('db', new \App\Core\Database($config));

$model = new \App\Models\SupplierProduct();
$page = 1;
$perPage = 50;
$failures = [];

do {
    $result = $model->paginate($page, $perPage, '');

    foreach ($result['items'] as $row) {
        $detail = $model->findById((int) $row['id']);

        if (!$detail) {
            $failures[] = [
                'id' => $row['id'],
                'error' => 'NOT_FOUND',
            ];
            continue;
        }

        try {
            $model->update((int) $detail['id'], [
                'ncc_id' => (int) $detail['ncc_id'],
                'san_pham_id' => empty($detail['san_pham_id']) ? null : (int) $detail['san_pham_id'],
                'ma_sku' => $detail['ma_sku'],
                'ten_chi_tiet' => $detail['ten_san_pham_chi_tiet'],
                'thuong_hieu' => $detail['thuong_hieu'],
                'gia' => $detail['gia'],
                'trang_thai' => $detail['trang_thai_raw'],
                'ton_kho' => $detail['ton_kho'],
                'price_note' => '',
            ]);
        } catch (\Throwable $exception) {
            $failures[] = [
                'id' => $detail['id'],
                'sku' => $detail['ma_sku'],
                'ncc' => $detail['ten_ncc'],
                'chi_tiet' => $detail['ten_san_pham_chi_tiet'],
                'error' => get_class($exception) . ': ' . $exception->getMessage(),
            ];
        }
    }

    $page++;
} while ($page <= (int) $result['last_page']);

echo 'TOTAL_FAILURES=' . count($failures) . PHP_EOL;

foreach ($failures as $failure) {
    echo json_encode($failure, JSON_UNESCAPED_UNICODE) . PHP_EOL;
}
