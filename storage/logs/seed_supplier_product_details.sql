UPDATE san_pham_chi_tiet
SET
    ma_sku = COALESCE(NULLIF(ma_sku, ''), CONCAT('SKU-', LPAD(id, 4, '0'))),
    gia = COALESCE(gia, CAST((id * 125000) AS DECIMAL(15,2))),
    trang_thai = COALESCE(trang_thai, IF(MOD(id, 5) = 0, 'ngung', 'dang_ban')),
    ton_kho = COALESCE(ton_kho, (id * 3) + 5),
    ngay_cap_nhat = COALESCE(ngay_cap_nhat, NOW());
