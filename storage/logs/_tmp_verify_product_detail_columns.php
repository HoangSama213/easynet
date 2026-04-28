<?php
$pdo = require __DIR__ . '/../../config/database.php';
$stmt = $pdo->query("SHOW COLUMNS FROM san_pham_chi_tiet");
foreach ($stmt->fetchAll() as $row) {
    if (in_array($row['Field'], ['hinh_anh_san_pham','bao_hanh','mo_ta_ngan','dac_diem','thong_so_ky_thuat','tinh_nang','giai_phap_lien_quan','du_an_lien_quan'], true)) {
        echo $row['Field'] . "\n";
    }
}
