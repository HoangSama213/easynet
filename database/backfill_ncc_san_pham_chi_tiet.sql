INSERT INTO ncc_san_pham_chi_tiet (
    ma_san_pham,
    ncc_id,
    san_pham_id,
    chi_tiet_id,
    ma_sku,
    thuong_hieu,
    gia,
    trang_thai,
    ton_kho,
    ngay_cap_nhat
)
SELECT
    CONCAT('TMP-', lk.ncc_id, '-', lk.chi_tiet_id),
    lk.ncc_id,
    spct.san_pham_id,
    lk.chi_tiet_id,
    ct.ma_sku,
    ct.thuong_hieu,
    ct.gia,
    ct.trang_thai,
    ct.ton_kho,
    ct.ngay_cap_nhat
FROM ncc_lien_ket_chi_tiet lk
INNER JOIN san_pham_chi_tiet ct ON ct.id = lk.chi_tiet_id
LEFT JOIN san_pham_lien_ket_chi_tiet spct ON spct.chi_tiet_id = lk.chi_tiet_id
ON DUPLICATE KEY UPDATE
    san_pham_id = VALUES(san_pham_id),
    ma_sku = VALUES(ma_sku),
    thuong_hieu = VALUES(thuong_hieu),
    gia = VALUES(gia),
    trang_thai = VALUES(trang_thai),
    ton_kho = VALUES(ton_kho),
    ngay_cap_nhat = VALUES(ngay_cap_nhat);

UPDATE ncc_san_pham_chi_tiet
SET ma_san_pham = CONCAT('SP-', LPAD(id, 5, '0'))
WHERE ma_san_pham LIKE 'TMP-%';
