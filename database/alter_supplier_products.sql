ALTER TABLE nha_cung_cap
    ADD COLUMN ma_ncc VARCHAR(20) NULL AFTER id;

UPDATE nha_cung_cap
SET ma_ncc = CONCAT('NCC-', LPAD(id, 3, '0'))
WHERE ma_ncc IS NULL OR ma_ncc = '';

ALTER TABLE nha_cung_cap
    MODIFY COLUMN ma_ncc VARCHAR(20) NOT NULL,
    ADD UNIQUE KEY uq_nha_cung_cap_ma_ncc (ma_ncc);

ALTER TABLE san_pham_chi_tiet
    ADD COLUMN ncc_id INT NULL AFTER id,
    ADD COLUMN san_pham_id INT NULL AFTER ncc_id,
    ADD COLUMN ma_sku VARCHAR(50) NULL AFTER san_pham_id,
    ADD COLUMN thuong_hieu VARCHAR(255) NULL AFTER ten_chi_tiet,
    ADD COLUMN gia DECIMAL(15,2) NULL AFTER thuong_hieu,
    ADD COLUMN trang_thai ENUM('dang_ban', 'ngung') NULL AFTER gia,
    ADD COLUMN ton_kho INT NULL AFTER trang_thai,
    ADD COLUMN ngay_cap_nhat DATETIME NULL DEFAULT CURRENT_TIMESTAMP AFTER ton_kho,
    ADD KEY idx_spct_ncc_id (ncc_id),
    ADD KEY idx_spct_san_pham_id (san_pham_id),
    ADD CONSTRAINT fk_spct_ncc FOREIGN KEY (ncc_id) REFERENCES nha_cung_cap(id) ON DELETE SET NULL,
    ADD CONSTRAINT fk_spct_san_pham FOREIGN KEY (san_pham_id) REFERENCES san_pham_ncc(id) ON DELETE SET NULL;

UPDATE san_pham_chi_tiet
SET
    ma_sku = COALESCE(NULLIF(ma_sku, ''), CONCAT('SKU-', LPAD(id, 4, '0'))),
    gia = COALESCE(gia, CAST((id * 125000) AS DECIMAL(15,2))),
    trang_thai = COALESCE(trang_thai, IF(MOD(id, 5) = 0, 'ngung', 'dang_ban')),
    ton_kho = COALESCE(ton_kho, (id * 3) + 5),
    ngay_cap_nhat = COALESCE(ngay_cap_nhat, NOW());
